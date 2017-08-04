<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <title>Budget Calculator - {{ $pageTitle }}</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}" />
    <script type="application/javascript">
        var csrfToken = "{{ csrf_token() }}";
        var baseUrl   = "{{ url('/') }}";
    </script>
</head>
<body>

<nav class="side-navigation">
    <div class="side-navigation__inner">
        <div class="side-navigation__item">
            <a href="#" class="{{ (request()->is('accounts/*') || request()->is('accounts')) ? 'selected' : '' }}">Accounts</a>
        </div>
        <div class="side-navigation__item">
            <a href="#" class="{{ (request()->is('categories/*') || request()->is('categories')) ? 'selected' : '' }}">Categories</a>
        </div>
        <div class="side-navigation__item">
            <a href="#" class="{{ (request()->is('reports/*') || request()->is('reports')) ? 'selected' : '' }}">Reports</a>
        </div>
        <div class="side-navigation__item">
            <a href="#" class="{{ (request()->is('budget/*') || request()->is('budget')) ? 'selected' : '' }}">Budget</a>
        </div>
    </div>
</nav>

<div class="wrapper">
    <section class="section__page-title clearfix">
        <div class="pull-left">
            <h1>{!! $pageTitle !!}</h1>
            <!--<ol class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li class="active">Data</li>
            </ol>-->
        </div>
        @if(isset($actionButtons))
            <div class="pull-right" style="padding-top: 13px;">
                @foreach($actionButtons as $actionButton)
                    <a class="btn {{ isset($actionButton['class']) ? $actionButton['class'] : 'btn-default' }}" href="{{ $actionButton['href'] }}" role="button">{{ $actionButton['text'] }}</a>
                @endforeach
            </div>
        @endif
    </section>

    <section class="section__page-body clearfix">
        @if((isset($success) && ! empty($success)) || $success = session('success'))
            <div class="alert alert-success" role="alert">{{ $success }}</div>
        @endif

        @if((isset($error) && ! empty($error)) || $error = session('error'))
            <div class="alert alert-danger" role="alert">{{ $error }}</div>
        @endif

        @yield('content')
    </section>
</div>

<script src="{{ mix('js/app.js') }}" type="application/javascript"></script>
</body>
</html>