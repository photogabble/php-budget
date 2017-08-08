@extends('_layouts.default', ['pageTitle' => 'Transactions for ' . $account->name . ' <small>(Starting Balance: £'. $account->starting_balance .')</small>', 'navBar' => 'accounts.transactions._navbar'])

@section('content')
    <table class="table table-condensed table-striped">
        <thead>
        <tr>
            <th width="10px">&nbsp;</th>
            <th width="7%" class="text-left">Date</th>
            <th width="15%" class="text-left">Type</th>
            <th>Description</th>
            <th width="15%" class="text-right">Category</th>
            <th width="5%" class="text-right">Amount</th>
            <th width="8%" class="text-right">Balance</th>
        </tr>
        </thead>
        <tbody>
        @if($records->count() < 1)
            <tr>
                <td colspan="7">
                    @if(strlen($searchTerm) > 0)
                    No transactions where found in the database to match the search term <em>"{{ $searchTerm }}"</em>.
                    @else
                    No transactions where found in the database, why not create one.
                    @endif
                </td>
            </tr>
        @else
            @foreach($records as $record)
                <tr>
                    <td class="text-center" style="width:10px;"><input type="checkbox" name="selectedRow" class="selectedRowSelector" value="{{ $record->id }}" /></td>
                    <td class="text-left">{{ $record->date->format('jS M Y') }}</td>
                    <td class="text-left">{{ $record->transaction_type }}</td>
                    <td>
                        <a href="{{ route('accounts.transactions.edit', ['account' => $account->id, 'transaction' => $record->id]) }}">
                            {{ $record->description }}
                        </a>
                    </td>
                    <td class="text-right">{{ $record->category->name }}</td>
                    <td class="text-right">
                        <span class="{{ $record->isPaidOut() ? 'text-danger' : 'text-success' }}">
                            {{ $account->currency . number_format($record->getAmount(), 2) }}
                        </span>
                    </td>
                    <td class="text-right">
                        {{ $account->currency . number_format($record->balance, 2) }}
                        <span class="{{ $record->isPaidOut() ? 'text-danger' : 'text-success' }}">
                            {{ $record->isPaidOut() ? '&#9660;' : '&#9650;' }}
                        </span>
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
@stop
