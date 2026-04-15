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
    Route::patch('/transaksi/{id}/kembalikan', [PeminjamanController::class, 'kembalikan'])->name('admin.transaksi.kembali');
    Route::patch('/transaksi/{id}/approve', [PeminjamanController::class, 'approvePinjam'])->name('admin.transaksi.approve');
    Route::patch('/transaksi/{id}/konfirmasi', [PeminjamanController::class, 'konfirmasiTerima'])->name('admin.transaksi.kembali');
    // Anggota
    Route::get('/anggota', [AdminController::class, 'indexAnggota'])->name('admin.anggota');
    Route::post('/anggota', [AdminController::class, 'storeAnggota'])->name('admin.anggota.store');
    Route::put('/anggota/{id}', [AdminController::class, 'updateAnggota'])->name('admin.anggota.update');
    Route::delete('/anggota/{id}', [AdminController::class, 'destroyAnggota'])->name('admin.anggota.destroy');

    // Transaksi
    Route::get('/transaksi', [PeminjamanController::class, 'indexAdmin'])->name('admin.transaksi');

    // Export PDF Buku
    Route::get('/admin/buku/export-pdf', [BukuController::class, 'exportPdf'])->name('buku.exportPdf');
    Route::get('/admin/transaksi/export-excel', [App\Http\Controllers\PeminjamanController::class, 'exportExcel'])->name('transaksi.exportExcel');

    Route::get('/admin/transaksi/export-pdf', [PeminjamanController::class, 'exportPdf'])->name('transaksi.exportPdf');
    Route::patch('/admin/transaksi/{id}/bayar', [App\Http\Controllers\PeminjamanController::class, 'bayarDenda'])->name('admin.transaksi.bayar');
});

Route::middleware(['auth'])->prefix('siswa')->group(function () {
    // Dashboard (Isinya Statistik/Ringkasan - Pake function index)
    Route::get('/dashboard', [SiswaController::class, 'index'])->name('siswa.dashboard');
    
    // Katalog (Isinya Daftar Buku Lengkap - Pake function katalog)
    Route::get('/katalog', [SiswaController::class, 'katalog'])->name('siswa.katalog');

    // Riwayat Pinjaman
    Route::get('/pinjam', [SiswaController::class, 'indexPinjam'])->name('siswa.pinjam');

    // Proses Pinjam & Kembali
    Route::post('/pinjam', [SiswaController::class, 'pinjamBuku'])->name('pinjam.store');
    Route::post('/kembali/{id}', [SiswaController::class, 'kembaliBuku'])->name('pinjam.kembali');
});