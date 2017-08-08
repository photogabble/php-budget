<form action="{{ $record->exists ? route('accounts.update', $record->id) : route('accounts.store') }}" method="post">
    {{ csrf_field() }}
    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
        <label for="name" class="control-label">Account Name</label>
        <input type="text" class="form-control" id="name" name="name" placeholder="Nationwide Account" value="{{ old('name', $record->name) }}">
        @if($error = $errors->first('name'))
            <span class="help-block">{{ $error }}</span>
        @endif
    </div>

    <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
        <label for="description" class="control-label">Description</label>
        <textarea class="form-control" name="description" id="description" cols="30" rows="3">{{ old('description', $record->description) }}</textarea>
        @if($error = $errors->first('description'))
            <span class="help-block">{{ $error }}</span>
        @endif
    </div>

    <div class="form-group{{ $errors->has('starting_balance') ? ' has-error' : '' }}">
        <label for="starting_balance" class="control-label">Starting Balance</label>
        <div class="input-group">
            <span class="input-group-addon">£</span>
            <input type="text" class="form-control" id="starting_balance" name="starting_balance" placeholder="1000.00" value="{{ old('starting_balance', $record->starting_balance) }}">
        </div>
        @if($error = $errors->first('starting_balance'))
            <span class="help-block">{{ $error }}</span>
        @endif
    </div>

    <button type="submit" class="btn btn-default">{{ $record->exists ? 'Update' : 'Create' }}</button>
</form>