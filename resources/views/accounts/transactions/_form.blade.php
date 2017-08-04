<form action="{{ $record->exists ? route('accounts.transactions.update', ['account' => $account->id, 'transaction' => $record->id]) : route('accounts.transactions.create', $account->id) }}"
      method="post">
    {{ csrf_field() }}

    <div class="form-group{{ $errors->has('date') ? ' has-error' : '' }}">
        <label for="date" class="control-label">Date</label>
        <input type="date" class="form-control" id="date" name="date"
               value="{{ old('date', (is_null($record->date)) ? null : $record->date->format('Y-m-d')) }}">
        @if($error = $errors->first('date'))
            <span class="help-block">{{ $error }}</span>
        @endif
    </div>

    <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
        <label for="description" class="control-label">Description</label>
        <div class="input-group">
            <input type="text" class="form-control suggest" id="description" name="description" placeholder="Miscellaneous" value="{{ old('description', $record->description) }}" />
            <span class="input-group-btn">
                <button class="btn btn-success suggest-category" type="button" data-query-source="#description" data-result-target="#category_id">Suggest Category</button>
            </span>
        </div>
        @if($error = $errors->first('description'))
            <span class="help-block">{{ $error }}</span>
        @endif
    </div>

    <div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
        <label for="amount" class="control-label">Amount ({{$account->currency}})</label>
        <input type="text" class="form-control" id="amount" name="amount" placeholder="0.00"
               value="{{ old('amount', $record->getAmount()) }}">
        @if($error = $errors->first('amount'))
            <span class="help-block">{{ $error }}</span>
        @endif
    </div>

    <div class="form-group{{ $errors->has('notes') ? ' has-error' : '' }}">
        <label for="notes" class="control-label">Notes</label>
        <textarea name="notes" id="notes" class="form-control">{{ old('notes', $record->notes) }}</textarea>
        @if($error = $errors->first('notes'))
            <span class="help-block">{{ $error }}</span>
        @endif
    </div>

    <div class="form-group{{ $errors->has('transaction_type') ? ' has-error' : '' }}">
        <label for="transaction_type" class="control-label">Transaction Type</label>

        @if(count($transactionTypes) > 0)
            <select data-placeholder="Please select a transaction type" class="form-control chosen-select"
                    name="transaction_type">
                @foreach($transactionTypes as  $name)
                    <option value="{{$name}}" {{ (old('transaction_type', $record->transaction_type) == $name) ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
        @else
            <input type="text" class="form-control" id="transaction_type" name="transaction_type"
                   value="{{ old('transaction_type', $record->transaction_type) }}">
        @endif

        @if($error = $errors->first('category_id'))
            <span class="help-block">{{ $error }}</span>
        @endif
    </div>

    <div class="form-group{{ $errors->has('category_id') ? ' has-error' : '' }}">
        <label for="category_id" class="control-label">Category</label>

        <select data-placeholder="Please select a category" class="form-control chosen-select" name="category_id" id="category_id"
                data-create-route="'/categories/create'">
            @foreach($categories as $id => $name)
                <option value="{{$id}}" {{ (old('category_id', $record->category_id) == $id) ? 'selected' : '' }}>{{ $name }}</option>
            @endforeach
        </select>

        @if($error = $errors->first('category_id'))
            <span class="help-block">{{ $error }}</span>
        @endif
    </div>
    <button type="submit" class="btn btn-default">{{ $record->exists ? 'Update' : 'Create' }}</button>
</form>