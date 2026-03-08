<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SiswaController;
use Illuminate\Support\Facades\Route;

// 1. Rute Guest (Belum Login)
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::get('/login', [AuthController::class, 'showLogin']);
    Route::post('/login', [AuthController::class, 'login']);
    
    // Fitur Daftar buat Siswa (Sesuai Flowmap) [cite: 79]
    Route::get('/register', function () { return view('auth.register'); });
    Route::post('/register', [AuthController::class, 'register']);
});

// 2. Rute Setelah Login (General)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// 3. Rute Khusus Admin (Pake Middleware/Cek Role) [cite: 133]
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Kelola Buku (CRUD Buku) [cite: 88, 134]
    Route::resource('buku', AdminController::class); 
    
    // Kelola Anggota [cite: 90, 134]
    Route::get('/anggota', [AdminController::class, 'indexAnggota'])->name('admin.anggota');
    
    // Kelola Transaksi [cite: 89]
    Route::get('/transaksi', [AdminController::class, 'indexTransaksi'])->name('admin.transaksi');
});

// 4. Rute Khusus Siswa [cite: 133]
Route::middleware(['auth'])->prefix('siswa')->group(function () {
    Route::get('/dashboard', [SiswaController::class, 'dashboard'])->name('siswa.dashboard');
    
    // Menu Peminjaman & Pengembalian [cite: 91, 92]
    Route::get('/pinjam', [SiswaController::class, 'indexPinjam'])->name('siswa.pinjam');
    Route::post('/pinjam', [SiswaController::class, 'storePinjam']);
    Route::post('/kembali/{id}', [SiswaController::class, 'kembaliBuku']);
});
