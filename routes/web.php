<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\AbsensiController;
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
        
        // Absensi Routes
        Route::get('/dosen/kelas/{kelas}/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
        Route::get('/dosen/kelas/{kelas}/absensi/create', [AbsensiController::class, 'create'])->name('absensi.create');
        Route::post('/dosen/kelas/{kelas}/absensi', [AbsensiController::class, 'store'])->name('absensi.store');
        Route::get('/dosen/kelas/{kelas}/absensi/{absensi}', [AbsensiController::class, 'show'])->name('absensi.show');
        Route::get('/dosen/kelas/{kelas}/absensi/{absensi}/edit', [AbsensiController::class, 'edit'])->name('absensi.edit');
        Route::put('/dosen/kelas/{kelas}/absensi/{absensi}', [AbsensiController::class, 'update'])->name('absensi.update');
        Route::delete('/dosen/kelas/{kelas}/absensi/{absensi}', [AbsensiController::class, 'destroy'])->name('absensi.destroy');
        Route::get('/dosen/kelas/{kelas}/absensi-rekap', [AbsensiController::class, 'rekap'])->name('absensi.rekap');
    });
    
    // Mahasiswa Routes
    Route::middleware('role:mahasiswa')->group(function () {
        Route::get('/mahasiswa/dashboard', [DashboardController::class, 'index'])->name('mahasiswa.dashboard');
        Route::get('/mahasiswa/kelas', [KelasController::class, 'kelasMahasiswa'])->name('kelas.mahasiswa');
        Route::get('/mahasiswa/nilai', [KelasController::class, 'nilaiSaya'])->name('kelas.nilai.saya');
        
        // Enrollment Routes
        Route::get('/mahasiswa/enrollment', [EnrollmentController::class, 'index'])->name('enrollment.index');
        Route::post('/mahasiswa/enrollment/{kelas}', [EnrollmentController::class, 'enroll'])->name('enrollment.enroll');
        Route::delete('/mahasiswa/enrollment/{kelas}', [EnrollmentController::class, 'drop'])->name('enrollment.drop');
    });
});
