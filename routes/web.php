<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\GenreController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Guest Routes (Belum Login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::get('/login', [AuthController::class, 'showLogin']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| Email Verification Routes (Wajib Login tapi Belum Verifikasi)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // 1. Halaman pemberitahuan verifikasi email
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    // 2. Proses verifikasi saat user klik link di email
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();

        // JANGAN KE /home. Arahin ke login aja biar user login ulang dengan status verified.
        return redirect('/login')->with('success', 'Email berhasil diverifikasi! Silakan login kembali.');
    })->middleware(['signed'])->name('verification.verify');

    // 3. Kirim ulang email verifikasi
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Link verifikasi baru udah dikirim!');
    })->middleware(['throttle:6,1'])->name('verification.send');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Wajib Login & Wajib Verifikasi Email)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Kelola Genre & Buku
    Route::resource('genre', GenreController::class);
    Route::resource('buku', BukuController::class);
    
    // Kelola Anggota
    Route::get('/anggota', [AdminController::class, 'indexAnggota'])->name('admin.anggota');
    Route::post('/anggota', [AdminController::class, 'storeAnggota'])->name('admin.anggota.store');
    Route::put('/anggota/{id}', [AdminController::class, 'updateAnggota'])->name('admin.anggota.update');
    Route::delete('/anggota/{id}', [AdminController::class, 'destroyAnggota'])->name('admin.anggota.destroy');

    // Transaksi & Approval
    Route::get('/transaksi', [PeminjamanController::class, 'indexAdmin'])->name('admin.transaksi');
    Route::patch('/transaksi/{id}/kembalikan', [PeminjamanController::class, 'kembalikan'])->name('admin.transaksi.kembali');
    Route::patch('/transaksi/{id}/approve', [PeminjamanController::class, 'approvePinjam'])->name('admin.transaksi.approve');
    Route::patch('/transaksi/{id}/konfirmasi', [PeminjamanController::class, 'konfirmasiTerima'])->name('admin.transaksi.konfirmasi');
    Route::patch('/transaksi/{id}/bayar', [PeminjamanController::class, 'bayarDenda'])->name('admin.transaksi.bayar');

    // Export Data
    Route::get('/buku/export-pdf', [BukuController::class, 'exportPdf'])->name('buku.exportPdf');
    Route::get('/transaksi/export-excel', [PeminjamanController::class, 'exportExcel'])->name('transaksi.exportExcel');
    Route::get('/transaksi/export-pdf', [PeminjamanController::class, 'exportPdf'])->name('transaksi.exportPdf');
});

/*
|--------------------------------------------------------------------------
| Siswa Routes (Wajib Login & Wajib Verifikasi Email)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->prefix('siswa')->group(function () {
    // Dashboard & Katalog
    Route::get('/dashboard', [SiswaController::class, 'index'])->name('siswa.dashboard');
    Route::get('/katalog', [SiswaController::class, 'katalog'])->name('siswa.katalog');
    Route::get('/pinjam', [SiswaController::class, 'indexPinjam'])->name('siswa.pinjam');

    // Proses Pinjam & Kembali
    Route::post('/pinjam', [SiswaController::class, 'pinjamBuku'])->name('pinjam.store');
    Route::post('/kembali/{id}', [SiswaController::class, 'kembaliBuku'])->name('pinjam.kembali');
});