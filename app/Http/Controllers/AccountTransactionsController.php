<?php

namespace App\Http\Controllers;

use App\Account;
use App\Repositories\CategoryRepository;
use App\Repositories\TransactionRepository;
use App\Transaction;
use Illuminate\Http\Request;

use App\Http\Requests;

class AccountTransactionsController extends Controller
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;
    /**
     * @var TransactionRepository
     */
    private $transactionRepository;

    public function __construct(CategoryRepository $categoryRepository, TransactionRepository $transactionRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->transactionRepository = $transactionRepository;
    }

    public function index (Account $account)
    {
        return view('accounts.transactions.index')
            ->with('account', $account)
            ->with('records', $account->transactions('DESC')->get())
            ->with('actionButtons', [
                ['class' => 'btn-default', 'href' => route('accounts.index'), 'text' => 'Back'],
                ['class' => 'btn-default', 'href' => route('accounts.transactions.import', $account->id), 'text' => 'Import'],
                ['class' => 'btn-primary', 'href' => route('accounts.transactions.create', $account->id), 'text' => 'Create']
            ]);
    }

    public function create(Account $account)
    {
        return view('accounts.transactions.create')
            ->with('account', $account)
            ->with('record', $this->transactionRepository->getNew())
            ->with('transactionTypes', $this->transactionRepository->getTransactionTypeList())
            ->with('categories', $this->categoryRepository->getCategoryList())
            ->with('actionButtons', [
                ['class' => 'btn-default', 'href' => route('accounts.transactions', $account->id), 'text' => 'Back']
            ]);
    }

    public function store(Account $account, Request $request)
    {
        $this->validate($request, [
            'date' => 'required',
            'transaction_type' => 'required',
            'description' => 'required|max:255',
            'amount' => 'required|numeric',
            'category_id' => 'required|exists:categories,id'
        ]);

        /** @var Transaction $record */
        $record = $this->transactionRepository->getNewByRequest($request);
        $record->setAmount($request->get('amount'));
        $record->sha1 = $this->transactionRepository->getHashForModel($record);
        $account->attachTransaction($record);

        return redirect()->route('accounts.transactions', $account->id)
            ->with('success', 'Transaction was successfully added.');
    }

    public function edit(Account $account, Transaction $transaction)
    {
        return view('accounts.transactions.edit')
            ->with('account', $account)
            ->with('record', $transaction)
            ->with('transactionTypes', $this->transactionRepository->getTransactionTypeList())
            ->with('categories', $this->categoryRepository->getCategoryList())
            ->with('actionButtons', [
                ['class' => 'btn-default', 'href' => route('accounts.transactions', $account->id), 'text' => 'Back']
            ]);
    }

    public function update(Account $account, Transaction $transaction, Request $request)
    {
        $this->validate($request, [
            'date' => 'required',
            'transaction_type' => 'required',
            'description' => 'required|max:255',
            'amount' => 'required|numeric',
            'category_id' => 'required|exists:categories,id'
        ]);

        $transaction->fill($request->only($transaction->getFillable()));
        $transaction->setAmount($request->get('amount'));
        $transaction->save();

        $account->modifyAttachedTransaction($transaction);

        return redirect()->back()
            ->with('success', 'Transaction was successfully updated.');
    }
}
