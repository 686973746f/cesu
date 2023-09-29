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

    <!-- Fonts -->

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <script>
        $(document).on('select2:open', () => {
            document.querySelector('.select2-search__field').focus();
        });
    </script>
</head>
@php
//HOME LINKS

if(Str::contains(request()->url(), 'pharmacy')) {
    $homelink = route('pharmacy_home');
}
else if(Str::contains(request()->url(), 'syndromic')) {
    $homelink = route('syndromic_home');
}
else {
    $homelink = route('home');
}
@endphp
<body style="font-family: Arial, Helvetica, sans-serif">
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-success shadow-sm">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{$homelink}}">
                    
                    @if(Str::contains(request()->url(), 'pharmacy'))
                    <img src="{{asset('assets/images/cho_icon_large.png')}}" style="width: 3rem;">
                    Pharmacy
                    @elseif(Str::contains(request()->url(), 'syndromic'))
                    <img src="{{asset('assets/images/cho_icon_large.png')}}" style="width: 3rem;">
                    <img src="{{asset('assets/images/cesu_icon.png')}}" style="width: 3rem;">
                    CBDSS
                    @else
                    <img src="{{asset('assets/images/cesu_icon.png')}}" style="width: 3rem;">
                    {{ config('app.name', 'Laravel') }}
                    @endif
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link {{(Str::contains(request()->url(), 'main_menu')) ? 'active text-warning' : ''}}" href="{{route('home')}}"><b>MAIN MENU</b></a>
                        </li>

                        @if

                        @if(auth()->check())
                        <li class="nav-item">
                            <span class="text-white nav-link"><b>MW: {{date('W')}} | YEAR: {{date('Y')}}</b></span>
                        </li>
                        @endif
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                        
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                   <i class="fa fa-user mr-2" aria-hidden="true"></i> {{ Auth::user()->name }}
                                </a>
                                
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{route('changepw.index')}}"><i class="fa fa-key mr-2" aria-hidden="true"></i>Change Password</a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt mr-2" aria-hidden="true"></i>{{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                            
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>

        <!-- Developed by Christian James Historillo -->
        
    </div>
</body>
</html>
