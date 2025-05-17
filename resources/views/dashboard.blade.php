@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h2 class="page-title"><i class="bi bi-grid-1x2-fill me-2"></i>Dashboard</h2>

    <div class="row">
        <div class="col-md-6">
            <div class="card card-custom">
                <div class="card-header">
                    <h4>Selamat Datang!</h4>
                </div>
                <div class="card-body">
                    <p class="lead">Selamat Datang di Sistem Pendukung Keputusan (SPK) menggunakan Metode COPRAS.</p>
                    <p>Gunakan menu navigasi di atas untuk mengelola data alternatif, kriteria, memasukkan nilai, dan melihat hasil perhitungan peringkat.</p>
                    <hr>
                    <p>Pastikan semua data awal (Alternatif dan Kriteria beserta bobotnya) telah terisi dengan benar sebelum melanjutkan ke tahap input nilai.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-custom">
                <div class="card-header">
                    <h4>Ringkasan Data</h4>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Jumlah Alternatif
                            <span class="badge bg-primary rounded-pill">{{ \App\Models\Alternative::count() }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Jumlah Kriteria
                            <span class="badge bg-primary rounded-pill">{{ \App\Models\Criterion::count() }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Total Data Skor Dimasukkan
                            <span class="badge bg-info rounded-pill">{{ \App\Models\Score::count() }}</span>
                        </li>
                    </ul>
                    <div class="mt-3 text-center">
                        <a href="{{ route('alternatives.index') }}" class="btn btn-outline-primary btn-sm me-2">Kelola Alternatif</a>
                        <a href="{{ route('criteria.index') }}" class="btn btn-outline-primary btn-sm">Kelola Kriteria</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom mt-4">
        <div class="card-header">
            <h4>Langkah Selanjutnya</h4>
        </div>
        <div class="card-body">
            <ol>
                <li>Periksa atau tambahkan <a href="{{ route('alternatives.index') }}">data alternatif</a>.</li>
                <li>Periksa atau tambahkan <a href="{{ route('criteria.index') }}">data kriteria</a> beserta bobot dan tipenya (Benefit/Cost).</li>
                <li>Lakukan <a href="{{ route('copras.inputScores') }}">input nilai</a> untuk setiap alternatif berdasarkan kriteria yang ada.</li>
                <li>Lihat <a href="{{ route('copras.results') }}">hasil perhitungan COPRAS</a> untuk mendapatkan peringkat.</li>
            </ol>
        </div>
    </div>

@endsection