@extends('_layouts.default', ['pageTitle' => 'Editing Transaction for ' . $account->name . ' <small>(Starting Balance: £'. $account->starting_balance .')</small>'])

@section('content')

    <div class="panel panel-default">
        <div class="panel-body">
            @include('accounts.transactions._form')
        </div>
    </div>

@stop
