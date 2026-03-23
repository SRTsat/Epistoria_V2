<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SiswaController extends Controller
{
    /**
     * HALAMAN DASHBOARD (Ringkasan Akun)
     * Isinya Statistik & 4 Buku Terbaru
     */
    public function index() 
    {
        $userId = Auth::id();

        // 1. Hitung buku yang SEDANG dipinjam (belum dikembalikan)
        $totalDipinjam = Peminjaman::where('user_id', $userId)
                                   ->where('status', 'dipinjam')
                                   ->count();

        // 2. Hitung total semua riwayat (termasuk yang sudah dikembalikan)
        $totalRiwayat = Peminjaman::where('user_id', $userId)->count();

        // 3. Hitung total denda yang pernah/sedang didapat
        $totalDenda = Peminjaman::where('user_id', $userId)->sum('denda');

        // 4. Ambil 4 buku terbaru untuk pajangan di dashboard
        $bukuTerbaru = Buku::latest()->take(4)->get();

        return view('siswa.dashboard', compact('totalDipinjam', 'totalRiwayat', 'totalDenda', 'bukuTerbaru'));
    }

    /**
     * HALAMAN KATALOG (Pindahan dari dashboard lama)
     * Isinya Filter, Search, dan Daftar Semua Buku
     */
    public function katalog(Request $request) 
    {
        $search = $request->search;
        $genres = $request->genres; 

        $query = Buku::query();

        // Filter Multi-Genre
        if ($request->filled('genres')) {
            $query->whereIn('genre', $genres);
        }

        // Live Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('penulis', 'like', "%{$search}%");
            });
        }

        $bukus = $query->get();

        // Jika request via AJAX (Live Search)
        if ($request->ajax()) {
            return view('siswa._buku_list', compact('bukus'))->render();
        }

        return view('siswa.katalog', compact('bukus'));
    }

    // Menampilkan riwayat pinjaman siswa (Halaman Riwayat)
    public function indexPinjam() {
        $pinjaman = Peminjaman::where('user_id', Auth::id())
                    ->with('buku')
                    ->latest()
                    ->get();
        
        return view('siswa.pinjam', compact('pinjaman'));
    }

    // Proses Peminjaman
    public function pinjamBuku(Request $request) {
        $buku = Buku::findOrFail($request->buku_id);

        if ($buku->stok <= 0) {
            return back()->with('error', 'Stok buku habis!');
        }

        Peminjaman::create([
            'user_id' => Auth::id(),
            'buku_id' => $request->buku_id,
            'tanggal_pinjam' => now(),
            'deadline' => now()->addDays(7), 
            'status' => 'dipinjam',
            'denda' => 0
        ]);

        $buku->decrement('stok');

        return redirect()->route('siswa.pinjam')->with('success', 'Buku berhasil dipinjam! Batas waktu 7 hari.');
    }

    // Proses Pengembalian + Hitung Denda
    public function kembaliBuku($id) {
        $pinjam = Peminjaman::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        
        if ($pinjam->status == 'dikembalikan') {
            return back()->with('error', 'Buku ini sudah dikembalikan!');
        }

        $tgl_kembali = now();
        $deadline = Carbon::parse($pinjam->deadline);
        $denda = 0;

        if ($tgl_kembali->gt($deadline)) {
            $selisih_hari = $tgl_kembali->diffInDays($deadline);
            $denda = $selisih_hari * 1000; 
        }

        $pinjam->update([
            'tanggal_kembali' => $tgl_kembali,
            'status' => 'dikembalikan',
            'denda' => $denda 
        ]);

        $pinjam->buku->increment('stok');

        if ($denda > 0) {
            return back()->with('success', "Buku dikembalikan. Anda telat $selisih_hari hari, denda: Rp " . number_format($denda));
        }

        return back()->with('success', 'Buku berhasil dikembalikan tepat waktu!');
    }
}