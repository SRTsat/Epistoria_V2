<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\PeminjamanController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::get('/login', [AuthController::class, 'showLogin']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', function () { 
        return view('auth.register'); 
    });

    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth'])->prefix('admin')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // CRUD Buku
    Route::resource('buku', BukuController::class);

    // Anggota
    Route::get('/anggota', [AdminController::class, 'indexAnggota'])->name('admin.anggota');

    // Transaksi
    Route::get('/transaksi', [PeminjamanController::class, 'indexAdmin'])->name('admin.transaksi');

    // Export PDF Buku
    Route::get('/admin/buku/export-pdf', [BukuController::class, 'exportPdf'])->name('buku.exportPdf');

    Route::get('/admin/transaksi/export-pdf', [PeminjamanController::class, 'exportPdf'])->name('transaksi.exportPdf');
});

Route::middleware(['auth'])->prefix('siswa')->group(function () {

    Route::get('/dashboard', [SiswaController::class, 'dashboard'])->name('siswa.dashboard');

    Route::get('/pinjam', [SiswaController::class, 'indexPinjam'])->name('siswa.pinjam');

    // Pastiin controller dan fungsinya sinkron
    Route::post('/pinjam', [SiswaController::class, 'pinjamBuku'])->name('pinjam.store');

    // INI YANG TADI ERROR: Lu harus kasih ->name('pinjam.kembali')
    // Dan pastiin fungsinya adalah 'kembaliBuku' sesuai yang ada di SiswaController lu
    Route::post('/kembali/{id}', [SiswaController::class, 'kembaliBuku'])->name('pinjam.kembali');
});