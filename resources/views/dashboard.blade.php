@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h2>Selamat Datang di SPK COPRAS</h2>
    <p>Gunakan menu navigasi di atas untuk mengelola data dan melihat hasil perhitungan.</p>
    
    <h4>Ringkasan Data:</h4>
    <ul>
        <li>Jumlah Alternatif: {{ \App\Models\Alternative::count() }}</li>
        <li>Jumlah Kriteria: {{ \App\Models\Criterion::count() }}</li>
    </ul>
    <p>Pastikan Anda telah menginput semua alternatif dan kriteria sebelum melakukan input nilai dan melihat hasil.</p>
@endsection