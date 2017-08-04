@extends('_layouts.default', ['pageTitle' => 'Create New Report'])

@section('content')

    {!! $report->render() !!}

@stop