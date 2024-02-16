<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - {{ env('APP_NAME') }}</title>

    <link rel="stylesheet" href="{{ asset('assets/extensions/@fortawesome/fontawesome-free/css/all.min.css') }}">

    @stack('plugin-styles')

    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/iconly.css') }}">

    @stack('style')

    <link rel="stylesheet" href="{{ asset('assets/compiled/css/custom.min.css') }}">
</head>

<body>
    <div id="app">
        <div id="sidebar">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header position-relative">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="logo">
                            <a href="{{ url('/') }}">Real Count</a>
                        </div>

                        <div class="sidebar-toggler x">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                        </div>
                    </div>
                </div>
                <div class="sidebar-menu">
                    <ul class="menu">
                        <li class="sidebar-title">Menu</li>

                        <li class="sidebar-item active">
                            <a href="{{ url('ppwp/hitung_suara') }}" class='sidebar-link'>
                                <i class="fas fa-vote-yea"></i>
                                <span>Hitung Suara</span>
                            </a>
                        </li>

                        {{-- <li class="sidebar-title">Credits</li>

                        <li class="sidebar-item">
                            <a href="{{ url('credits') }}" class='sidebar-link'>
                                <i class="bi bi-puzzle"></i>
                                <span>Credits</span>
                            </a>
                        </li> --}}

                    </ul>
                </div>
            </div>
        </div>
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            @yield('content')

            <footer>
                <div class="footer clearfix mb-0 text-muted">
                    <div class="float-start">
                        <p>Copyright &copy; <a href="{{ url('/') }}">Real Count</a> 2024</p>
                    </div>
                    <div class="float-end">
                        <p>Develop By <a href="https://khidir.id">Khidir Zahid</a></p>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="{{ asset('assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>

    @stack('plugin-scripts')

    <script src="{{ asset('assets/compiled/js/app.js') }}"></script>

    @stack('script')
</body>

</html>
