@extends('_layouts.default', ['pageTitle' => 'Create new category', 'actionButtons' => [['class' => 'btn-default', 'href' => route('categories.index'), 'text' => 'Back']]])

@section('content')

    <div class="panel panel-default">
        <div class="panel-body">
            @include('categories._form')
        </div>
    </div>

@stop