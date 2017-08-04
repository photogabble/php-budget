@extends('_layouts.default', ['pageTitle' => 'Imported ' . $records->count() .' Transactions for ' . $account->name])

@section('content')

    <table class="table table-condensed table-striped">
        <thead>
        <tr>
            <th width="5%" class="text-left">Date</th>
            <th width="10%" class="text-left">Type</th>
            <th>Description</th>
            <th width="8%" class="text-center">Category</th>
            <th width="5%" class="text-center">Amount</th>
            <th width="8%" class="text-right">Balance</th>
        </tr>
        </thead>
        <tbody>
        @if($records->count() < 1)
            <tr>
                <td colspan="6">No transactions where found in the database, why not create one.</td>
            </tr>
        @else
            @foreach($records as $record)
                <tr>
                    <td class="text-left">{{ $record->date }}</td>
                    <td class="text-left">{{ $record->transaction_type }}</td>
                    <td>{{ $record->description }}</td>
                    <td class="text-center">{{ $record->category->name }}</td>
                    <td class="text-center">
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
