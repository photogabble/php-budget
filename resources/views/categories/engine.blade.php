@extends('_layouts.default', ['pageTitle' => 'Category Engine ' . (($isPrimed) ? 'Primed' : 'Not Primed')])

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>Test Suggestion Engine</strong>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label for="description" class="control-label">Description</label>
                <input type="text" class="form-control suggest" id="description" name="description" value="" data-result-target="#description__suggest">
                <span id="description__suggest" class="help-block"></span>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading clearfix">
            <div class="pull-left">
                <strong>Suggestion Model: </strong>
            </div>

            <div class="pull-right btn-group btn-group-xs" role="group">
                <a href="{{ route('categories.engine.wipe') }}" class="btn btn-danger btn-xs">Wipe</a>
                <a href="{{ route('categories.engine.teach') }}" class="btn btn-primary btn-xs">Teach</a>
            </div>

        </div>
        <div class="panel-body">

            @if (count($records) > 0)
            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

                <?php
                    $n = 0;
                    foreach ($records as $category => $model) {
                ?>
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="{{ 'heading_' . $n }}">
                            <h4 class="panel-title">
                                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#{{ 'collapse_' . $n }}" aria-expanded="true" aria-controls="{{ 'collapse_' . $n }}">
                                    {{ ucfirst($category) . ' {'. count($model) .'}' }}
                                </a>
                            </h4>
                        </div>
                        <div id="{{ 'collapse_' . $n }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="{{ 'heading_' . $n }}">
                            <div class="panel-body">
                                <?php var_dump($model); ?>
                            </div>
                        </div>
                    </div>
                <?php
                        $n++;
                    }
                ?>
            </div>
            @else
                Suggestion engine is not primed.
            @endif
        </div>

        <div class="panel-footer">
            <small>
                The category suggestion engine is primed with <strong>{{ count($records) }}</strong> mappings. The more mappings that you
                provide it the more accurate it will become.
            </small>
        </div>
    </div>
@stop