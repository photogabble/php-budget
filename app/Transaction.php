<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Transaction
 * @package App
 * @property int $id
 * @property int $account_id
 * @property int $category_id
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property int $date
 * @property string $transaction_type
 * @property string $description
 * @property int $paid_out
 * @property int $paid_in
 * @property int $balance
 * @property string $notes
 * @property string $sha1
 *
 * @property Account|null $account
 * @property Category|null $category
 */
class Transaction extends Model
{
    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'category'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['date', 'category_id', 'transaction_type', 'description', 'paid_out', 'paid_in', 'balance'];

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    //
    // Value should be DD-MM-YYYY but we need to make sure that it is correct, so some basic heuristics check to make sure
    //
    public function setDateAttribute($value)
    {
        if ($value instanceof Carbon) {
            $this->attributes['date'] = $value->format('U');
            return;
        }
        $dateFormats = [
            'U',            // Unix time
            'd/m/Y',        // DD/MM/YYYY
            'd M Y',        // DD Jan YYYY
            'd-M-y',        // DD-Jan-YY
            'j-M-y',        // D-Jan-YY
            'j-M-Y',        // D-Jan-YYYY
            'Y-m-d',        // YYYY-MM-DD
            'd-m-y',        // DD-MM-YY
            'd-m-Y',        // DD-MM-YYYY
            'j-m-y',        // D-MM-YY
            'j-m-Y',        // D-MM-YYYY
            'j-n-y',        // D-M-YY
            'j-n-Y',        // D-M-YYYY
        ];
        // If the created $date is equal to the input value then we have the right format.
        foreach($dateFormats as $format) {
            try{
                $carbon = Carbon::createFromFormat($format, $value);
                $carbon->setTime(12,0,0); // All dates need to have the same time for the purpose of hashing
                $this->attributes['date'] = $carbon->format('U');
                return;
            }catch(\InvalidArgumentException $e) {
                // ...
            }
        }
        throw new \Exception('Unknown date format used.');
    }
    public function getDateAttribute()
    {
        return isset($this->attributes['date']) ? Carbon::createFromFormat('U', $this->attributes['date']) : null;
    }
    public function getPaidInAttribute()
    {
        return isset($this->attributes['paid_in']) ? ($this->attributes['paid_in'] / 100) : null;
    }
    public function getPaidOutAttribute()
    {
        return isset($this->attributes['paid_out']) ? ($this->attributes['paid_out'] / 100) : null;
    }
    public function getPaidOutAttributeInt()
    {
        return isset($this->attributes['paid_out']) ? (int)(string)$this->attributes['paid_out'] : 0;
    }
    public function getPaidInAttributeInt()
    {
        return isset($this->attributes['paid_in']) ? (int)(string)$this->attributes['paid_in'] : 0;
    }
    public function setPaidInAttribute($value)
    {
        $this->attributes['paid_in'] = (int)(string)($value * 100);
    }
    public function setPaidOutAttribute($value)
    {
        $this->attributes['paid_out'] = (int)(string)($value * 100);
    }
    public function setBalanceAttribute($value)
    {
        $this->attributes['balance'] = (int)(string)($value * 100);
    }
    public function getBalanceAttribute()
    {
        return isset($this->attributes['balance']) ? ($this->attributes['balance'] / 100) : null;
    }
    //
    // The input on this can be a float/string/int it gets converted correctly by the mutator's above.
    // Actually no transformation of type should be done outside of this class on numbers that are being
    // passed to it, for risk of floating point errors.
    //
    public function setAmount($amount)
    {
        if ($this->sign($amount) === 1) {
            $this->paid_in = abs($amount);
        } elseif ($this->sign($amount) === -1) {
            $this->paid_out = abs($amount);
        }
    }
    //
    // For times when you need the amount as an integer, such as if you are updating via SQL
    // Set $abs as true if you want the absolute number.
    //
    public function getIntegerAmount($abs = false)
    {
        if ($this->isPaidIn()) {
            $value = (int)(string)$this->attributes['paid_in'];
        }else{
            $value = (int)(string)$this->attributes['paid_out'] * -1;
        }
        return ($abs === true) ? abs($value) : $value;
    }
    public function getAmount()
    {
        if (! $this->exists) { return 0;}
        if ($this->isPaidOut()) {
            return $this->paid_out * -1;
        }
        return $this->paid_in;
    }
    public function isPaidOut()
    {
        return $this->attributes['paid_out'] > 0;
    }
    public function isPaidIn()
    {
        return $this->attributes['paid_in'] > 0;
    }
    public function hasNote()
    {
        return !empty($this->notes);
    }
    public function getHashableAttributes()
    {
        return ['date', 'transaction_type', 'description', 'paid_out', 'paid_in'];
    }

    //
    // Add or subtract from balance
    // @todo this should accept an int only?
    //
    public function modifyBalance($amount)
    {
        if ($this->sign($amount) === 1) {
            $this->attributes['balance'] += abs($amount);
        } elseif ($this->sign($amount) === -1) {
            $this->attributes['balance'] -= abs($amount);
        }
    }

    private function sign ( $number ) {
        return ( $number > 0 ) ? 1 : ( ( $number < 0 ) ? -1 : 0 );
    }
}
