<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            <span class="navbar-brand">
                <a class="navbar-brand" href="@guest home @else{{ route('dashboard') }}@endguest">
                    <img src="{{ asset('img/logo_jefweb.png') }}" class="img-fluid " alt="logo" width="36px" />
                    <span style="padding-left: 5px">
                        {{ config('app.name', 'Laravel') }}
                    </span>
                </a>
                <span style="font-size:0.75em; margin-left: -15px">
                @if (isSet($here))
                    -> <a href="{{ route($routes['index']) }}">{{$headTitle}}</a>
                @endif
                @if (isSet($title))
                    -> {{$title}}
                @endif
                </span>
            </span>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown,#navbarSupportedContent" aria-controls="navbarNavDropdown,navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
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
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Login</a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">Registre-se</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
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
</header>
