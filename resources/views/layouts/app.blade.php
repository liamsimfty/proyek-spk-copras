<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'SPK COPRAS') - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />


    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- Bootstrap Icons (Opsional, tapi sangat berguna) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7f6; /* Light gray background */
            color: #333;
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }

        .navbar-custom {
            background-color: #ffffff; /* White navbar */
            box-shadow: 0 2px 4px rgba(0,0,0,.08);
            padding: 0.8rem 1rem;
        }
        .navbar-custom .navbar-brand {
            font-weight: 600;
            color: #007bff; /* Primary color */
        }
        .navbar-custom .nav-link {
            color: #555;
            font-weight: 500;
            padding: 0.5rem 1rem;
        }
        .navbar-custom .nav-link:hover,
        .navbar-custom .nav-link.active {
            color: #007bff;
        }

        .main-content {
            flex: 1; /* Ensure content takes remaining space */
            padding-top: 1.5rem; /* Space from navbar */
            padding-bottom: 1.5rem;
        }

        .card-custom {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,.1);
            margin-bottom: 1.5rem;
        }
        .card-custom .card-header {
            background-color: #007bff; /* Primary color */
            color: white;
            font-weight: 500;
            border-bottom: none;
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
            padding: 0.75rem 1.25rem;
        }
         .card-custom .card-header h2,
         .card-custom .card-header h4 {
            margin-bottom: 0;
            font-size: 1.25rem;
        }
        .card-custom .card-body {
            padding: 1.5rem;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .table th {
            font-weight: 600;
            background-color: #e9ecef; /* Lighter header for tables */
        }
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: #343a40;
        }

        .alert {
            border-radius: 0.3rem;
        }

        footer.footer-custom {
            background-color: #343a40; /* Dark footer */
            color: #f8f9fa;
            padding: 1rem 0;
            text-align: center;
            font-size: 0.9em;
            margin-top: auto; /* Push footer to bottom */
        }
        footer.footer-custom a {
            color: #007bff;
            text-decoration: none;
        }
        footer.footer-custom a:hover {
            text-decoration: underline;
        }

        /* Responsive Sidebar (jika diperlukan, bisa dikembangkan lebih lanjut) */
        @media (min-width: 768px) {
            /* Contoh jika ingin layout dengan sidebar
            .sidebar {
                position: fixed;
                top: 0;
                bottom: 0;
                left: 0;
                z-index: 100;
                padding: 48px 0 0;
                box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
                width: 220px;
            }
            .main-content-with-sidebar {
                margin-left: 220px;
            }
            .navbar-custom {
                 padding-left: 235px; // if sidebar is fixed
            }
            */
        }

        /* Active link styling helper */
        .nav-item .nav-link.active {
            background-color: rgba(0, 123, 255, 0.1);
            border-left: 3px solid #007bff;
            color: #007bff !important;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-md navbar-custom">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ route('dashboard') }}">
                    <i class="bi bi-calculator-fill me-2"></i> SPK COPRAS
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0"> <!-- Navigasi utama ke kiri -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <i class="bi bi-grid-1x2-fill me-1"></i> Dashboard
                            </a>
                        </li>
                        {{-- Hanya tampilkan menu SPK jika pengguna sudah login --}}
                        @auth
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('alternatives.*') ? 'active' : '' }}" href="{{ route('alternatives.index') }}">
                                <i class="bi bi-ui-checks-grid me-1"></i> Alternatif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('criteria.*') ? 'active' : '' }}" href="{{ route('criteria.index') }}">
                                <i class="bi bi-card-checklist me-1"></i> Kriteria
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('copras.inputScores') ? 'active' : '' }}" href="{{ route('copras.inputScores') }}">
                                <i class="bi bi-pencil-square me-1"></i> Input Nilai
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('copras.results') ? 'active' : '' }}" href="{{ route('copras.results') }}">
                                <i class="bi bi-bar-chart-line-fill me-1"></i> Hasil COPRAS
                            </a>
                        </li>
                        @endauth
                    </ul>

                    <ul class="navbar-nav ms-auto"> <!-- Navigasi autentikasi ke kanan -->
                        @guest
                            {{-- Tampilkan jika pengguna adalah Guest (belum login) --}}
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">
                                        <i class="bi bi-box-arrow-in-right me-1"></i> Login
                                    </a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}" href="{{ route('register') }}">
                                        <i class="bi bi-person-plus-fill me-1"></i> Register
                                    </a>
                                </li>
                            @endif
                        @else
                            {{-- Tampilkan jika pengguna sudah login --}}
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownUser" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownUser">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                            <i class="bi bi-gear-fill me-1"></i> Profile
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <a class="dropdown-item" href="{{ route('logout') }}"
                                               onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                                <i class="bi bi-box-arrow-left me-1"></i> Logout
                                            </a>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main class="main-content container-fluid">
        <div class="container"> {{-- Wrapper untuk konten agar tidak terlalu lebar di layar besar --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
             @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                     <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Whoops!</strong> Ada beberapa masalah dengan input Anda.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer class="footer-custom mt-auto py-3">
        <div class="container text-center">
            <span class="text-light">© {{ date('Y') }} <a href="#" class="text-light">SPK COPRAS Laravel</a>. All Rights Reserved.</span>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle (Popper.js and Bootstrap JS) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    @stack('scripts') {{-- For page-specific scripts --}}
</body>
</html>