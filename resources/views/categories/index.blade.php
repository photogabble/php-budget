@extends('_layouts.default', ['pageTitle' => 'Categories'])

@section('content')

    <table class="table table-condensed table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th width="5%" class="text-center">Transactions</th>
            </tr>
        </thead>
        <tbody>
        @if($records->count() < 1)
            <tr>
                <td colspan="2">No categories where found in the database, why not create one.</td>
            </tr>
        @else
            @foreach($records as $record)
                <tr>
                    <td>
                        <a href="{{ route('categories.edit', $record->id) }}">
                            {!! $record->default == true ? "<strong>{$record->name}</strong>" : $record->name !!}
                        </a>
                    </td>
                    <td class="text-center">{{ $record->transactions->count() }}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>

@stop