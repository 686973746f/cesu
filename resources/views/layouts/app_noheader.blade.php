<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    @if(!(auth()->check()))
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
    <!-- Fonts -->

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <script>
        $(document).on('select2:open', () => {
            document.querySelector('.select2-search__field').focus();
        });
    </script>
</head>
<body style="font-family: Arial, Helvetica, sans-serif">
    <div id="app">
        <main class="py-4">
            @yield('content')
        </main>
        <!-- Developed by Christian James Historillo -->
    </div>
</body>
</html>
