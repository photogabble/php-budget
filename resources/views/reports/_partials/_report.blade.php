<div class="panel panel-default">

    <div class="panel-heading">
        <strong>{{ $reportName }}</strong>
        <div class="pull-right">
            <button class="btn btn-default btn-xs" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><span class="glyphicon glyphicon-wrench"></span></button>
        </div>
    </div>

    <div class="panel-heading collapse in" id="collapseExample">
        <form action="{{ $reportModel->exists ? '' : route('reports.store', $reportClassName) }}" data-preview-route="{{ route('reports.preview', $reportClassName) }}" method="post" class="form-inline clearfix report-form">
            {{ csrf_field() }}

            <input type="hidden" name="date_range" id="date_range" value=""/>

            <div class="pull-left">
                <a href="#" class="btn btn-default" title="Compare report"><span class="glyphicon glyphicon-indent-left"></span></a>
            </div>

            <div class="pull-right report-form-toolbar-item with-bar">
                <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-floppy-disk"></span></button>
            </div>

            <div id="reportrange" class="reportrange pull-right report-form-toolbar-item with-bar" data-value="{{$reportConfiguration->date_range}}" data-input-id="#date_range" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">
                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                <span></span> <b class="caret"></b>
            </div>

            <div class="pull-right report-form-toolbar-item">
                <div class="btn-group" data-toggle="buttons" role="group" aria-label="...">
                    <label class="btn btn-default active">
                        <input type="radio" name="output_type" id="option1" value="table" autocomplete="off" checked> <span class="glyphicon glyphicon-th-list"></span>
                    </label>
                    <label class="btn btn-default">
                        <input type="radio" name="output_type" id="option2" value="graph" autocomplete="off"> <span class="glyphicon glyphicon-stats"></span>
                    </label>
                </div>
            </div>
        </form>
    </div>

    <div id="report-output" class="panel-body">
        {!! $reportContent !!}
    </div>
</div>