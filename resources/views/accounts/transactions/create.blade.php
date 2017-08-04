@extends('_layouts.default', ['pageTitle' => 'Creating Transaction for ' . $account->name])

@section('content')

    <div class="panel panel-default">
        <div class="panel-body">
            @include('accounts.transactions._form')
        </div>
    </div>

@stop
