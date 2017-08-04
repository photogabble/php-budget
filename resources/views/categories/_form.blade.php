<form action="{{ $record->exists ? route('categories.update', $record->id) : route('categories.store') }}" method="post">
    {{ csrf_field() }}
    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
        <label for="name" class="control-label">Category Name</label>
        <input type="text" class="form-control" id="name" name="name" placeholder="Miscellaneous" value="{{ old('name', $record->name) }}">
        @if($error = $errors->first('name'))
            <span class="help-block">{{ $error }}</span>
        @endif
    </div>

    <div class="checkbox">
        <label>
            <input type="checkbox" name="default" {{ old('default', $record->default) == true ? 'CHECKED' : '' }}> Is default category
        </label>
    </div>

    <button type="submit" class="btn btn-default">{{ $record->exists ? 'Update' : 'Create' }}</button>
</form>