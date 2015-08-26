<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="{{ Settings::get('site_name') }}">
        <meta name="description" content="{{ Settings::get('site_description') }}">
        @section('head')
            <title>{{ trans('admin.administration_panel') }} - {{ Settings::get('site_name') }}</title>
        @show

        @section('css')
            <link href="{{ asset('/css/uikit.flat.min.css') }}" rel="stylesheet">
        @show
    </head>

    <body>
        @section('navbar')
            @include('includes.navbar')
        @show

        @section('header')
        @show
        
        @yield ('content')
        
        @section('footer')
            @include('includes.footer')
        @show
        
        <!-- Scripts -->
        @section('js')
            <!-- Necessary Scripts -->
            <script src="{{ asset('/js/jquery.min.js') }}"></script>
            <script src="{{ asset('/js/uikit.min.js') }}"></script>

            <!-- Other Scripts -->
            <link href="{{ asset('/css/components/notify.almost-flat.css') }}" rel="stylesheet">
            <script src="{{ asset('/js/components/notify.min.js') }}"></script>
        @show

        @section('alerts')
            @include('includes.alerts')
        @show
        {!! Analytics::render() !!}
    </body>
</html>