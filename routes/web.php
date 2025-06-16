<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AlternativeController;
use App\Http\Controllers\CriterionController;
use App\Http\Controllers\CoprasController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes
Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
});

// --- Rute SPK COPRAS ---
// Semua rute ini sekarang memerlukan autentikasi
Route::middleware(['auth'])->group(function () {
    // Rute untuk Alternatif
    Route::resource('alternatives', AlternativeController::class);

    // Rute untuk Kriteria
    Route::resource('criteria', CriterionController::class);

    // Rute untuk Input Nilai dan Hasil COPRAS
    Route::get('copras/input-scores', [CoprasController::class, 'inputScores'])->name('copras.inputScores');
    Route::post('copras/save-scores', [CoprasController::class, 'saveScores'])->name('copras.saveScores');
    Route::get('copras/results', [CoprasController::class, 'calculateAndShowResults'])->name('copras.results');
});
// --- Akhir Rute SPK COPRAS ---

require __DIR__.'/auth.php';

// Mengarahkan root (/) ke halaman login jika belum login, atau ke dashboard jika sudah login
Route::get('/', function () {
    if (Auth::check()) {
        // Check if user is admin
        if (Auth::user()->name === 'Admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});
