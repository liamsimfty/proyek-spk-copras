<?php

use App\Http\Controllers\AlternativeController;
use App\Http\Controllers\CriterionController;
use App\Http\Controllers\CoprasController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('alternatives', AlternativeController::class);

// Rute untuk Kriteria
Route::resource('criteria', CriterionController::class);

// Rute untuk Input Nilai dan Hasil COPRAS
Route::get('copras/input-scores', [CoprasController::class, 'inputScores'])->name('copras.inputScores');
Route::post('copras/save-scores', [CoprasController::class, 'saveScores'])->name('copras.saveScores');
Route::get('copras/results', [CoprasController::class, 'calculateAndShowResults'])->name('copras.results');

// Tambahkan ini jika Anda ingin halaman dashboard/utama SPK
Route::get('/dashboard', function () {
    // Anda bisa mengambil data ringkasan di sini jika perlu
    return view('dashboard');
})->name('dashboard');

// Redirect root ke dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});