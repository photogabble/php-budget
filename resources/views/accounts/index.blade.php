@extends('_layouts.default', ['pageTitle' => 'Accounts', 'actionButtons' => [['class' => 'btn-primary', 'href' => route('accounts.create'), 'text' => 'Create']]])

@section('content')
    <div class="row">

        @if($records->count() < 1)
            <p>No accounts could be found, why not <a href="{{ route('accounts.create') }}">create a new one</a>.</p>
        @endif

        <?php /** @var \App\Account $record */ ?>
        @foreach($records as $record)
        <div class="col-md-6" style="margin-bottom: 20px">
            <div class="profile-container">
                <div class="profile-header row">
                    <div class="col-md-3 col-sm-12 text-center" style="padding-top:1em">
                        <?php $chart = new App\Chart($record->getAccountStatistics()->top('category.usage', 'c'), 'name', 'c'); ?>
                        <canvas width="100" height="100" id="{{ camel_case($record->name) . '__chart' }}" class="chartjs__donut-chart" data-labels="{{ $chart->getLabels() }}" data-dataset="{{ $chart->getDataSets() }}"></canvas>
                    </div>
                    <div class="col-md-9 col-sm-12 profile-info">
                        <div class="header-fullname">
                            <a href="{{ route('accounts.transactions', $record->id) }}" style="display: block">
                                {{ $record->name }}
                            </a>
                        </div>
                        <a href="{{ route('accounts.edit', $record->id) }}" class="btn btn-palegreen btn-sm  btn-follow">
                            <i class="fa fa-check"></i>
                            Edit
                        </a>
                        <div class="header-information">
                            {{ $record->description }}
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12 profile-stats">
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12 stats-col">
                                <div class="stats-value pink">{{ $record->currency.number_format($record->starting_balance, 2) }}</div>
                                <div class="stats-title">Starting Balance</div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12 stats-col">
                                <div class="stats-value pink">{{ $record->currency.number_format($record->current_balance, 2) }}</div>
                                <div class="stats-title">Current Balance</div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12 stats-col">
                                <div class="stats-value pink">{{ $record->transactions->count() }}</div>
                                <div class="stats-title">Total Transactions</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

@stop