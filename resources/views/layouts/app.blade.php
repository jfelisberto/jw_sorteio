<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ config('app.name', 'Laravel') }}@hasSection('headTitle') | @yield('headTitle')@endif</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('img/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com" />
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" />

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/fontawesome.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2-bootstrap.css') }}" />
    <link href="{{ asset('plugins/sweetalert/css/sweetalert2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('plugins/datatables_bootstrap/datatables.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/main.min.css') }}" rel="stylesheet" />
    {{-- Para carregar CSS personalizado de cada pagina --}}
    @hasSection('stylesheet')@yield('stylesheet')@endif
</head>
<body>
    <div id="app">
        @include('components.navbar')

        <div class="container-fluid py-4">
            @guest
            <div class="row justify-content-center">
                <div class="col-sm-8 col-md-8 col-lg-8 col-xl-8">
            @else
            <div class="row">
                @include('components.menu', ['current'=>$current])
                <div class="col-sm-10 col-md-10 col-lg-10 col-xl-10">
            @endguest
                    @include ('components.messages')
                    <main>
                        @yield('content')
                    </main>
                </div>
            </div>
        </div>


    </div>

    @include('components.form_ajax')

    <footer class="footer fixed-bottom border-top ms-sm-auto bg-light">
        <div class="container-fluid">
            <nav class="text-center">
                &copy; 2021, by JEF Web
            </nav>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/fontawesome.min.js') }}"></script>

    <script src="{{ asset('plugins/datatables_bootstrap/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/select2/js/select2.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/bootstrap_notify/bootstrap-notify.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/sweetalert/js/sweetalert2.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/plugins.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        }
    });
    var basePath = '{{ asset('') }}';
    {{-- basePath = basePath.replace('/public', ''); --}}
    </script>
    <script src="{{ asset('js/main.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/datatables.min.js') }}" type="text/javascript"></script>
    {{-- Para carregar JS personalizado de cada pagina --}}
    @hasSection('javascript')@yield('javascript')@endif
    @hasSection('javascriptForm')@yield('javascriptForm')@endif
    @hasSection('javascriptMessage')@yield('javascriptMessage')@endif
</body>
</html>
