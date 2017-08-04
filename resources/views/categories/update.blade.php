@extends('_layouts.default', ['pageTitle' => 'Update category ' . $record->name])

@section('content')

    <div class="panel panel-default">
        <div class="panel-body">
            @include('categories._form')
        </div>
    </div>

@stop