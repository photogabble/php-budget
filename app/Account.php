<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;

/**
 * Class Account
 * @package App
 * @property int $id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $last_transaction
 * @property string $name
 * @property int $starting_balance
 * @property int $current_balance
 *
 * @property Transaction[]|Builder|null $transactions
 */
class Account extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'starting_balance'];

    /** @var Statistics */
    private $accountStatistics;

    /**
     * @param Statistics $accountStatistics
     */
    public function setAccountStatistics(Statistics $accountStatistics)
    {
        $this->accountStatistics = $accountStatistics;
    }
    /**
     * @return Statistics
     */
    public function getAccountStatistics()
    {
        return $this->accountStatistics;
    }

    /**
     * @return Transaction|static
     * @throws \Exception
     */
    public function getLatestTransaction()
    {
        if (!$this->exists) {
            throw new \Exception("Account record needs to exist before latest transaction can be looked up.");
        }

        return $this->transactions()->first();
    }

    /**
     * @param string $order
     * @return Transaction[]|HasMany|Builder
     */
    public function transactions($order = 'DESC')
    {
        return $this->hasMany(Transaction::class, 'account_id')
            ->orderBy('date', $order)
            ->orderBy('id', $order);
    }

    public function getStartingBalanceAttribute()
    {
        return isset($this->attributes['starting_balance']) ? ($this->attributes['starting_balance'] / 100) : null;
    }

    public function setStartingBalanceAttribute($value)
    {
        $this->attributes['starting_balance'] = (int)(string)($value * 100);
    }

    public function getCurrentBalanceAttribute()
    {
        return isset($this->attributes['current_balance']) ? ($this->attributes['current_balance'] / 100) : null;
    }

    public function setCurrentBalanceAttribute($value)
    {
        $this->attributes['current_balance'] = (int)(string)($value * 100);
    }

    public function save(array $options = [])
    {
        if ($this->exists === false) {
            $this->attributes['current_balance'] = $this->attributes['starting_balance'];
        } else {
            if ($this->isDirty('starting_balance')) {
                $difference = $this->attributes['starting_balance'] - $this->original['starting_balance'];
                $this->modifyCurrentBalance($difference);
            }
        }
        return parent::save($options);
    }

    //
    // Add or subtract from current balance
    //
    public function modifyCurrentBalance($amount)
    {
        if (!is_int($amount)) {
            throw new \Exception("You can only modify the current balance with an integer!");
        }

        if ($this->sign($amount) === 1) {
            $this->attributes['current_balance'] += abs($amount);
            $this->transactions()->increment('balance', abs($amount));
        } elseif ($this->sign($amount) === -1) {
            $this->attributes['current_balance'] -= abs($amount);
            $this->transactions()->decrement('balance', abs($amount));
        }
    }

    private function sign($number)
    {
        return ($number > 0) ? 1 : (($number < 0) ? -1 : 0);
    }

    public function attachTransaction(Transaction $transaction)
    {
        if (!$this->exists) {
            throw new \Exception("Transactions can only be attached to accounts that exist.");
        }

        $latestTransaction = $this->getLatestTransaction();

        // Rebuild all transactions if this transaction is not newer than the latest transaction.
        if ($latestTransaction && $transaction->date->format('U') < $latestTransaction->date->format('U')) {
            /** @var Transaction $transaction */
            $this->transactions()->save($transaction);
            $this->rebuildTransactionsBalance();
            return $this->transactions()->find($transaction->id);
        }

        // If this transaction is newer than the last transaction in the database then its being appended
        $this->modifyCurrentBalance($transaction->getIntegerAmount());
        $transaction->attributes['balance'] = $this->attributes['current_balance'];
        $this->transactions()->save($transaction);
        $this->save();

        return $transaction;
    }

    public function modifyAttachedTransaction(Transaction $transaction)
    {
        if (!$this->exists) {
            throw new \Exception("Transactions can only be modified on accounts that exist.");
        }
        if (!$transaction->exists) {
            throw new \Exception("Transaction records must exist before they can be modified.");
        }

        // Rebuild all transactions, maybe check to see if we are the latest transaction and just update for that
        // case, but really rebuild is simpler :)
        $this->rebuildTransactionsBalance();
        return $this->transactions()->find($transaction->id);
    }

    public function rebuildTransactionsBalance()
    {
        if (!$this->exists) {
            throw new \Exception("Account record must exist for its transactions to be rebuilt");
        }

        $balance = $this->attributes['starting_balance'];

        /** @var Transaction $record */
        foreach ($this->transactions('ASC')->get() as $record) {
            if ($record->isPaidIn()) {
                $balance += $record->getIntegerAmount(true);
            } else {
                $balance -= $record->getIntegerAmount(true);
            }
            $record->attributes['balance'] = $balance; // $balance is a signed integer so we can assign this attribute directly
            $record->save();
            $this->attributes['last_transaction'] = $record->date->format('U');
        }
        unset($record);

        $this->attributes['current_balance'] = $balance;
        $this->save();
    }
}
