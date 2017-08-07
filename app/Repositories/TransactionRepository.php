<?php namespace App\Repositories;

use App\Account;
use App\Transaction;
use Illuminate\Database\Eloquent\Builder;

class TransactionRepository extends Repository
{
    /**
     * @var Transaction|Builder
     */
    protected $model;

    public function __construct(Transaction $model)
    {
        parent::__construct($model);
    }

    public function getTransactionTypeList()
    {
        return $this->model->groupBy('transaction_type')->pluck('transaction_type');
    }

    public function findTransactionsForAccount(Account $account, $filter = []) {
        $transactions = $account->transactions('DESC');

        if (isset($filter['s']) && strlen($filter['s']) > 0) {
            $transactions = $transactions->where('description', 'LIKE', '%'.$filter['s'].'%');
        }

        return $transactions->get();
    }
}