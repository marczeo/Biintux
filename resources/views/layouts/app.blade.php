<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" type="image/png" href="/images/favicon.png" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
        <!--User style-->
        <link href="/css/app.css" rel="stylesheet">
        <!-- bootstrap table -->
        <link href="{{ asset('css/bootstrap-table/bootstrap-table.min.css') }}" rel="stylesheet">
        <!--<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.0/bootstrap-table.min.css">-->

    
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
@yield('scriptsTop')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-inverse navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <!-- Authentication Links -->
                    @if (Auth::check())
                    <ul class="nav navbar-nav">
                        @if(Auth::user()->isAdmin())
                        <li>
                            <a href="/ciclovia">
                                <img src="/images/cycling.svg" onerror="this.src='/images/cycling.png'" width="25" height="25">
                                {{trans('navbar.bikeway')}}
                            </a>
                        </li>

                        <li>
                            <a href="/route">
                                <img src="/images/route.svg" onerror="this.src='/images/route.png'" width="25" height="25">
                                {{trans('navbar.route')}}
                            </a>
                        </li>

                        <li>
                            <a href="/mibici">
                                <img src="/images/mibici.svg" onerror="this.src='/images/mibici.png'" width="25" height="25">
                                {{trans('navbar.mibici')}}
                            </a>
                        </li>
                        <li>
                            <a href="/user">
                                <img src="/images/user.svg" onerror="this.src='/images/user.png'" width="25" height="25">
                                {{trans('navbar.user')}}
                            </a>
                        </li>
                        <li>
                            <a href="/bus">
                                <img src="/images/bus.svg" onerror="this.src='/images/bus.png'" width="25" height="25">
                                {{trans('navbar.bus')}}
                            </a>
                        </li>
                        @elseif(Auth::user()->isConcessionaire())
                        <li>
                            <a href="/user">
                                <img src="/images/user.svg" onerror="this.src='/images/user.png'" width="25" height="25">
                                {{trans('navbar.driver')}}
                            </a>
                        </li>
                        <li>
                            <a href="/bus">
                                <img src="/images/bus.svg" onerror="this.src='/images/bus.png'" width="25" height="25">
                                {{trans('navbar.bus')}}
                            </a>
                        </li>
                        @endif
                    </ul>
                    @endif

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!--Dropdown to change languaje-->
                        <li class="dropdown">
                            <a href="languaje" title = "{{ trans('navbar.language') }}" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            @if(App::getLocale()=='es')
                            <img src="/images/lang/es.svg" onerror="this.src='/images/lang/es.png'" width="25" height="25">
                            @elseif(App::getLocale()=='en')
                            <img src="/images/lang/en.svg" onerror="this.src='/images/lang/en.png'" width="25" height="25">
                            @endif 
                                {{ trans('navbar.language') }} <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="/spanish">{{ trans('navbar.spanish') }}</a>
                                </li>
                                <li>
                                    <a href="/english">{{ trans('navbar.english') }}</a>
                                </li>
                            </ul>
                        </li><!--End language-->
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ url('/login') }}">
                            <img src="/images/login.svg" onerror="this.src='/images/login.png'" width="25" height="25">{{trans('navbar.login')}}</a></li>
                            <li><a href="{{ url('/register') }}"><img src="/images/register.svg" onerror="this.src='/images/register.png'" width="25" height="25">{{trans('navbar.register')}}</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="/perfil">{{trans('navbar.profile')}}</a></li>
                                    <li>
                                        <a href="{{ url('/logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')
        <footer>Biintux &#174; 2017</footer>
    </div>

    <!-- Scripts -->
        <!-- jQuery -->
        <script src="{{asset('js/jquery/jquery.min.js')}}"></script>
        <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js" type="text/javascript"></script>-->

        <!-- bootstrap-table -->
        <script src="{{asset('js/bootstrap-table/bootstrap-table.min.js')}}"></script>
        <!--<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.0/bootstrap-table.min.js"></script>-->

        <!-- bootstrap-table Locales -->
        <script src="{{asset('js/bootstrap-table/bootstrap-table-locale-all.min.js')}}"></script>
        <!--<script src="{{asset('js/bootstrap-table/bootstrap-table-es-MX.min.js')}}"></script>
        <script src="{{asset('js/bootstrap-table/bootstrap-table-en-US.min.js')}}"></script>-->
        <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.0/locale/bootstrap-table-es-MX.js"></script>-->
        
        <!-- User script -->
        <script src="/js/app.js"></script>
        <script src="/js/main.js"></script>
    @yield('scriptsBottom')
</body>
</html>
