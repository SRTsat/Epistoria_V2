<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    // List transaksi untuk Admin (CRUD Transaksi) [cite: 89, 134]
    public function indexAdmin() {
        $transaksi = Peminjaman::with(['user', 'buku'])->latest()->get();
        return view('admin.transaksi.index', compact('transaksi'));
    }

    // Proses Peminjaman Buku oleh Siswa [cite: 106, 133]
    public function store(Request $request) {
        $request->validate(['buku_id' => 'required|exists:bukus,id']);
        $buku = Buku::find($request->buku_id);

        if ($buku->stok <= 0) {
            return back()->with('error', 'Maaf, stok buku sedang kosong!');
        }

        Peminjaman::create([
            'user_id' => Auth::id(),
            'buku_id' => $request->buku_id,
            'tanggal_pinjam' => now(),
            'status' => 'dipinjam'
        ]);

        $buku->decrement('stok'); // Kurangi stok otomatis

        return back()->with('success', 'Buku berhasil dipinjam!');
    }

    // Proses Pengembalian Buku oleh Siswa [cite: 107, 133]
    public function kembalikan($id) {
        $pinjam = Peminjaman::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        
        if ($pinjam->status === 'dikembalikan') {
            return back()->with('error', 'Buku ini sudah dikembalikan sebelumnya.');
        }

        $pinjam->update([
            'tanggal_kembali' => now(),
            'status' => 'dikembalikan'
        ]);

        $pinjam->buku->increment('stok'); // Tambah stok kembali

        return back()->with('success', 'Terima kasih, buku telah dikembalikan!');
    }
}