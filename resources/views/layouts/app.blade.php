<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-success shadow-sm">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        @if(auth()->check())
                        <li class="nav-item">
                            <a class="nav-link {{Request::is('records*') ? 'active' : ''}}" href="{{route('records.index')}}">Patients</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{Request::is('forms*') ? 'active' : ''}}" href="{{route('forms.index')}}">CIFs</a>
                        </li>
                        @if(auth()->user()->isCesuAccount())
                        <li class="nav-item">
                            <a class="nav-link {{Request::is('linelist*') ? 'active' : ''}}" href="{{route('linelist.index')}}">Line Lists</a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link {{Request::is('report*') ? 'active' : ''}}" href="{{route('report.index')}}">Reports</a>
                        </li>
                        @if(auth()->user()->isAdmin == 1)
                        <li class="nav-item">
                            <a class="nav-link {{Request::is('admin*') ? 'active' : ''}}" href="{{route('adminpanel.index')}}">Admin Panel</a>
                        </li>
                        @endif
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
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
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
