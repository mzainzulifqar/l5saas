<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="icon" href="{{ asset('icon/spark.png') }}" sizes="40x40">

    <!-- Scripts js -->
    <script src="https://js.stripe.com/v3/"></script>
    
       
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link href="{{ asset('fontawesome/css/font-awesome.min.css') }}" rel="stylesheet" >
    

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    <link rel=stylesheet href="{{ asset('stripe/stripe.css') }}">

     

</head>
<body  style="background-color: #F5F3EF;">
<div id="app">
<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>

            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 43" xmlns:xlink="http://www.w3.org/1999/xlink" style="height: 37px; margin-right: 20px;"><defs><path id="a" d="M22 2.5c4-3.8 6.7-2.4 6 3.2l-1.5 10h4.2c5.5 0 6.7 3.2 2.6 7L14 40.7c-4 3.7-6.7 2.3-6-3.3l1.5-10H5.3c-5.5 0-6.7-3-2.6-7L22 2.4z"></path> <linearGradient id="b" x1="59.1%" x2="88.7%" y1="55.6%" y2="100%"><stop stop-color="#F1C476" offset="0%"></stop> <stop stop-color="#CC973B" offset="100%"></stop></linearGradient> <linearGradient id="d" x1="11.3%" x2="40.9%" y1="0%" y2="44.4%"><stop stop-color="#CC973B" offset="0%"></stop> <stop stop-color="#F1C476" offset="100%"></stop></linearGradient></defs> <g fill="none" fill-rule="evenodd"><path fill="#F1C476" d="M16 8.4c7.3-7 12.3-4.4 10.8 5.7l-.2 2c7.8 0 8 5.7.6 12.7l-7 6.5c-7.5 7-12.4 4.5-11-5.7l.3-1.8c-7.8 0-8-5.7-.7-12.6l7-6.5z"></path> <g transform="translate(.037 .147)"><mask id="c" fill="#fff"><use xlink:href="#a"></use></mask> <use fill="#F1C476" xlink:href="#a"></use> <path fill="url(#b)" d="M3.8-1.5h25.6v17.3H3.8" mask="url(#c)"></path> <path fill="url(#d)" d="M6.6 27.3h25.6v17.3H6.6z" mask="url(#c)"></path></g></g></svg>

          
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
                @noSubscription()
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('plans.index') }}">Plans</a>
                    </li>
                 @endnoSubscription()

                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                    <li class="nav-item dropdown">
                        <div class="dropdown show">
                            <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{Auth::user()->name}}
                            </a>
                            
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <div class="dropdown-header"><i class="icon-cog"></i> Settings</div>
                                <a class="dropdown-item" href="{{ route('account.index') }}">
                                    Account
                                    
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                    <i class="icon-signout"></i> &nbsp;&nbsp;{{ __('Logout') }}
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
<script>
    var stripe_key = '{{config('services.stripe.key')}}';

</script>
@stack('js')

</body>
</html>

