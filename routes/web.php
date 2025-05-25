<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Redirect root ke login jika belum login
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->isAdmin()) {
            return redirect()->route('dashboard');
        } elseif ($user->isDosen()) {
            return redirect()->route('dosen.dashboard');
        } else {
            return redirect()->route('mahasiswa.dashboard');
        }
    }
    return redirect()->route('login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    
    // Admin Routes
    Route::middleware('role:admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // User Management
        Route::resource('users', UserController::class);
        
        // Tahun Ajaran Management
        Route::resource('tahun-ajaran', TahunAjaranController::class);
        
        // Mata Kuliah Management
        Route::resource('mata-kuliah', MataKuliahController::class);
        
        // Kelas Management (Admin bisa akses semua)
        Route::resource('kelas', KelasController::class);
    });
    
    // Dosen Routes
    Route::middleware('role:dosen')->group(function () {
        Route::get('/dosen/dashboard', [DashboardController::class, 'index'])->name('dosen.dashboard');
        Route::get('/dosen/kelas', [KelasController::class, 'kelasDosen'])->name('kelas.dosen');
        Route::get('/dosen/kelas/{kelas}/nilai', [KelasController::class, 'nilaiMahasiswa'])->name('kelas.nilai');
        Route::post('/dosen/kelas/{kelas}/nilai/{mahasiswa}', [KelasController::class, 'updateNilai'])->name('kelas.nilai.update');
        Route::get('/dosen/kelas/{kelas}/edit', [KelasController::class, 'edit'])->name('kelas.edit');
        Route::put('/dosen/kelas/{kelas}', [KelasController::class, 'update'])->name('kelas.update');
    });
    
    // Mahasiswa Routes
    Route::middleware('role:mahasiswa')->group(function () {
        Route::get('/mahasiswa/dashboard', [DashboardController::class, 'index'])->name('mahasiswa.dashboard');
        Route::get('/mahasiswa/kelas', [KelasController::class, 'kelasMahasiswa'])->name('kelas.mahasiswa');
        Route::get('/mahasiswa/nilai', [KelasController::class, 'nilaiSaya'])->name('kelas.nilai.saya');
    });
});
