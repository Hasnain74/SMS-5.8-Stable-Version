<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{asset('css/style.css')}}" rel="stylesheet" type="text/css"/>

    <link rel="apple-touch-icon" sizes="57x57" href={{asset('favicons/apple-icon-57x57.png')}}>
    <link rel="apple-touch-icon" sizes="60x60" href={{asset('favicons//apple-icon-60x60.png')}}>
    <link rel="apple-touch-icon" sizes="72x72" href={{asset('favicons//apple-icon-72x72.png')}}>
    <link rel="apple-touch-icon" sizes="76x76" href={{asset('favicons//apple-icon-76x76.png')}}>
    <link rel="apple-touch-icon" sizes="114x114" href={{asset('favicons//apple-icon-114x114.png')}}>
    <link rel="apple-touch-icon" sizes="120x120" href={{asset('favicons//apple-icon-120x120.png')}}>
    <link rel="apple-touch-icon" sizes="144x144" href={{asset('favicons//apple-icon-144x144.png')}}>
    <link rel="apple-touch-icon" sizes="152x152" href={{asset('favicons//apple-icon-152x152.png')}}>
    <link rel="apple-touch-icon" sizes="180x180" href={{asset('favicons//apple-icon-180x180.png')}}>
    <link rel="icon" type="image/png" sizes="192x192"  href={{asset('favicons//android-icon-192x192.png')}}>
    <link rel="icon" type="image/png" sizes="32x32" href={{asset('favicons//favicon-32x32.png')}}>
    <link rel="icon" type="image/png" sizes="96x96" href={{asset('favicons//favicon-96x96.png')}}>
    <link rel="icon" type="image/png" sizes="16x16" href={{asset('favicons//favicon-16x16.png')}}>
    <link rel="manifest" href={{asset('favicons//manifest.json')}}>
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <img style="border-radius: 50%;" width="100" height="100" src="{{asset('img/logo.png')}}" alt="Logo" class="mx-2">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            {{--<li class="nav-item">--}}
                                {{--<a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>--}}
                            {{--</li>--}}
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
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
    </div>
</body>
</html>
