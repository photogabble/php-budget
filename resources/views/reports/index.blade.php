@extends('_layouts.default', ['pageTitle' => 'Reports'])

@section('content')
    <table class="table table-condensed table-striped">
        <thead>
        <tr>
            <th>Name</th>
            <th width="10%" class="text-center">Last Generated</th>
        </tr>
        </thead>
        <tbody>
        @if($records->count() < 1)
            <tr>
                <td colspan="2">No saved reports where found in the database, why not create one.</td>
            </tr>
        @else
            @foreach($records as $record)
                <tr>
                    <td>
                        <a href="{{ route('categories.edit', $record->id) }}">
                            {{ $record->name }}
                        </a>
                    </td>
                    <td class="text-center">{{ $record->last_updated }}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>

    <hr/>

    <div class="panel panel-default">

        <div class="panel-heading">
            <strong>Create new report</strong>
        </div>

        <table class="table table-condensed table-striped">
            <thead>
            <tr>
                <th>Name</th>
            </tr>
            </thead>
            <tbody>
            @if(count($reports) < 1)
                <tr>
                    <td colspan="1">Sorry there are no base reports configured.</td>
                </tr>
            @else
                @foreach($reports as $report)
                    <tr>
                        <td>
                            <a href="{{ route('reports.create', $report->getBase64EncodedClassName()) }}">
                                {{ $report->getName() }}
                            </a>
                            <br>
                            {{ $report->getDescription() }}
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>

@stop