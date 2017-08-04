<?php

namespace App\Http\Controllers;

use App\Account;
use App\Repositories\AccountRepository;
use Illuminate\Http\Request;

use App\Http\Requests;

class AccountsController extends Controller
{
    /**
     * @var AccountRepository
     */
    private $accountRepository;

    /**
     * AccountsController constructor.
     * @param AccountRepository $accountRepository
     */
    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function index()
    {
        return view('accounts.index')
            ->with('records', $this->accountRepository->all());
    }

    public function create()
    {
        return view('accounts.create')
            ->with('record', new Account);
    }

    public function store(Request $request, Account $category)
    {
        $this->validate($request, [
            'name' => 'required|unique:accounts|max:255',
            'starting_balance' => 'required|numeric'
        ]);

        Account::create($request->only(['name', 'starting_balance']));

        return redirect()->route('accounts.index')
            ->with('success', 'New account ' . $request->get('name') . ' was successfully created.');
    }

    public function edit(Account $account)
    {
        return view('accounts.update')
            ->with('record', $account);
    }

    public function update(Account $account, Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:accounts,id,'.$account->id .'|max:255',
            'starting_balance' => 'required|numeric'
        ]);

        $account->fill($request->only($account->getFillable()));
        $account->save();

        return redirect()->back()
            ->with('success', 'Account ' . $request->get('name') . ' was successfully updated.');
    }

    public function destroy(Account $account)
    {

    }
}
