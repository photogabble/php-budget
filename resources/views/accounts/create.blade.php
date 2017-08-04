@extends('_layouts.default', ['pageTitle' => 'Create new account', 'actionButtons' => [['class' => 'btn-default', 'href' => route('accounts.index'), 'text' => 'Back']]])

@section('content')

    <div class="panel panel-default">
        <div class="panel-body">
            @include('accounts._form')
        </div>
    </div>

@stop