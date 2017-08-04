@extends('_layouts.default', ['pageTitle' => 'Import Transactions for ' . $account->name])

@section('content')

    <form action="{{ route('accounts.transactions.import.begin', $account->id) }}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('import_file') ? ' has-error' : '' }}">
            <label for="import_file" class="control-label">CSV File to import</label>

            <div class="input-group">
                <label class="input-group-btn">
                    <span class="btn btn-primary">
                        Browse&hellip; <input type="file" name="import_file" style="display: none;" accept=".csv">
                    </span>
                </label>
                <input type="text" class="form-control" readonly>
                <span class="input-group-btn">
                    <button type="submit" class="btn btn-default">Import</button>
                </span>
            </div>

            @if($error = $errors->first('import_file'))
                <span class="help-block">{{ $error }}</span>
            @endif
        </div>
    </form>
@stop
