<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPK COPRAS - @yield('title', 'Sistem Pendukung Keputusan')</title>
    <!-- Tambahkan CSS framework Anda di sini, contoh Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding-top: 20px; }
        .container { max-width: 960px; }
        nav ul { list-style-type: none; padding: 0; }
        nav ul li { display: inline; margin-right: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <header class="mb-4">
            <h1>Sistem Pendukung Keputusan - Metode COPRAS</h1>
            <nav>
                <ul>
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('alternatives.index') }}">Alternatif</a></li>
                    <li><a href="{{ route('criteria.index') }}">Kriteria</a></li>
                    <li><a href="{{ route('copras.inputScores') }}">Input Nilai</a></li>
                    <li><a href="{{ route('copras.results') }}">Hasil COPRAS</a></li>
                </ul>
            </nav>
        </header>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <main>
            @yield('content')
        </main>

        <footer class="mt-5 text-center text-muted">
            <p>© {{ date('Y') }} Proyek SPK COPRAS</p>
        </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>