<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminP2mp\DashboardController as AdminP2mpDashboardController;
use App\Http\Controllers\AdminP2mp\UserController as AdminP2mpUserController;
use App\Http\Controllers\AdminP2mp\ProdiController as AdminP2mpProdiController;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Http;

Route::get('/test-gemini', function () {

    $response = Http::post(
        'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . env('GEMINI_API_KEY'),
        [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => 'Halo Gemini, siapa kamu?'
                        ]
                    ]
                ]
            ]
        ]
    );

    return $response->json();
});

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Rute Guest (Pengunjung)
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Rute dengan Autentikasi
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->role === 'admin_p2mp') {
            return redirect()->route('adminp2mp.dashboard');
        }
        if ($user->role === 'admin_prodi' || $user->role === 'kaprodi') {
            return redirect()->route('adminprodi.dashboard');
        }
        if ($user->role === 'dosen') {
            return redirect()->route('dosen.dashboard');
        }
        return view('dashboard');
    })->name('dashboard');

    // Rute Admin P2MP
    Route::middleware('role:admin_p2mp')->prefix('adminp2mp')->name('adminp2mp.')->group(function () {
        Route::get('/dashboard', [AdminP2mpDashboardController::class, 'index'])->name('dashboard');
        Route::resource('users', AdminP2mpUserController::class);
        Route::resource('prodi', AdminP2mpProdiController::class);
        Route::get('/validasi', [AdminP2mpDashboardController::class, 'validasi'])->name('validasi');
        Route::post('/validasi/{id}', [AdminP2mpDashboardController::class, 'updateValidasi'])->name('validasi.update');
        Route::get('/monitoring', [AdminP2mpDashboardController::class, 'monitoring'])->name('monitoring');
    });

    // Rute yang dapat diakses oleh Admin Prodi, Kaprodi & Admin P2MP
    Route::middleware('role:admin_prodi,kaprodi,admin_p2mp')->prefix('adminprodi')->name('adminprodi.')->group(function () {
        Route::get('/laporan', [\App\Http\Controllers\AdminProdi\LaporanController::class, 'index'])->name('laporan.index');

        // Read-only access to Master Data
        Route::resource('kategori', \App\Http\Controllers\AdminProdi\KategoriController::class)->only(['index', 'show']);
        Route::resource('iku', \App\Http\Controllers\AdminProdi\IkuController::class)->only(['index', 'show']);
        Route::resource('bukti', \App\Http\Controllers\AdminProdi\BuktiIkuController::class)->only(['index', 'show']);
    });

    // Rute yang HANYA dapat diakses oleh Admin P2MP (Konfigurasi Utama & Akses Tulis)
    Route::middleware('role:admin_p2mp')->prefix('adminprodi')->name('adminprodi.')->group(function () {
        Route::get('/pengaturan', [\App\Http\Controllers\AdminProdi\PengaturanController::class, 'index'])->name('pengaturan.index');
        Route::post('/pengaturan', [\App\Http\Controllers\AdminProdi\PengaturanController::class, 'store'])->name('pengaturan.store');

        Route::resource('kategori', \App\Http\Controllers\AdminProdi\KategoriController::class)->except(['index', 'show']);
        Route::resource('iku', \App\Http\Controllers\AdminProdi\IkuController::class)->except(['index', 'show']);
        Route::resource('bukti', \App\Http\Controllers\AdminProdi\BuktiIkuController::class)->except(['index', 'show']);
    });

    // Rute yang HANYA dapat diakses oleh Admin Prodi & Kaprodi
    Route::middleware('role:admin_prodi,kaprodi')->prefix('adminprodi')->name('adminprodi.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\AdminProdi\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('pencapaian', \App\Http\Controllers\AdminProdi\IkuPencapaianController::class);
        Route::resource('penugasan', \App\Http\Controllers\AdminProdi\PenugasanController::class);
        Route::get('/bukti-dosen', [\App\Http\Controllers\AdminProdi\DashboardController::class, 'buktiDosen'])->name('bukti-dosen');
        Route::get('/dosen', [\App\Http\Controllers\AdminProdi\DashboardController::class, 'dosen'])->name('dosen');
    });

    // Rute Dosen
    Route::middleware('role:dosen')->prefix('dosen')->name('dosen.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Dosen\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/pengisian', [\App\Http\Controllers\Dosen\PengisianController::class, 'index'])->name('pengisian.index');
        Route::get('/pengisian/create', [\App\Http\Controllers\Dosen\PengisianController::class, 'create'])->name('pengisian.create');
        Route::post('/pengisian', [\App\Http\Controllers\Dosen\PengisianController::class, 'store'])->name('pengisian.store');
        Route::get('/pengisian/{id}/edit', [\App\Http\Controllers\Dosen\PengisianController::class, 'edit'])->name('pengisian.edit');
        Route::put('/pengisian/{id}', [\App\Http\Controllers\Dosen\PengisianController::class, 'update'])->name('pengisian.update');
        Route::get('/pencapaian', [\App\Http\Controllers\Dosen\DashboardController::class, 'pencapaian'])->name('pencapaian.index');
    });
});
