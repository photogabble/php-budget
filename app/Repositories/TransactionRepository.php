<?php namespace App\Repositories;

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
}