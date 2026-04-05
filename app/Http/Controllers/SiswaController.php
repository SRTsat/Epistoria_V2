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
        $pinjamans = Peminjaman::where('user_id', $userId)->get();

        $totalDenda = 0;
        $hari_ini = \Carbon\Carbon::now()->startOfDay();

        foreach ($pinjamans as $p) {
            if ($p->status == 'dipinjam') {
                // Hitung denda berjalan jika sudah lewat deadline
                $deadline = \Carbon\Carbon::parse($p->deadline)->startOfDay();
                if ($hari_ini->gt($deadline)) {
                    $selisih = $hari_ini->diffInDays($deadline);
                    $totalDenda += ($selisih * 1000);
                }
            } else {
                // Tambahkan denda yang sudah fix (sudah dikembalikan)
                $totalDenda += $p->denda;
            }
        }

        $totalDipinjam = $pinjamans->where('status', 'dipinjam')->count();
        $totalRiwayat = $pinjamans->count();
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
        $userId = Auth::id();
        $pinjaman = Peminjaman::where('user_id', $userId)
                    ->with('buku')
                    ->latest()
                    ->get();
        
        // HITUNG TOTAL DENDA (LOGIKA YANG SAMA KAYAK DI DASHBOARD)
        $totalDenda = 0;
        $hari_ini = \Carbon\Carbon::now()->startOfDay();

        foreach ($pinjaman as $p) {
            if ($p->status == 'dipinjam') {
                // Hitung denda berjalan jika sudah lewat deadline
                $deadline = \Carbon\Carbon::parse($p->deadline)->startOfDay();
                if ($hari_ini->gt($deadline)) {
                    $selisih = $hari_ini->diffInDays($deadline);
                    $totalDenda += ($selisih * 1000);
                }
            } else {
                // Tambahkan denda yang sudah fix dari database
                $totalDenda += $p->denda;
            }
        }
        
        // Kirim variabel totalDenda ke view
        return view('siswa.pinjam', compact('pinjaman', 'totalDenda'));
    }
    // Proses Peminjaman
    public function pinjamBuku(Request $request) {
        // 1. Validasi dulu (biar pasti angkanya bener)
        $data = $request->validate([
            'buku_id' => 'required|exists:bukus,id',
            'durasi' => 'required|integer|min:1|max:20', 
            'kelas' => 'required|string'
        ]);

        $buku = Buku::findOrFail($request->buku_id);

        if ($buku->stok <= 0) {
            return back()->with('error', 'Stok buku habis!');
        }

        // 2. Gunakan durasi yang sudah divalidasi
        Peminjaman::create([
            'user_id' => Auth::id(),
            'buku_id' => $request->buku_id,
            'kelas' => $request->kelas,
            'tanggal_pinjam' => now(),
            'deadline' => now()->addDays((int) $request->durasi), // <--- DIPAKSA JADI INT DI SINI
            'status' => 'dipinjam',
            'denda' => 0
        ]);

        $buku->decrement('stok');

        return redirect()->route('siswa.pinjam')->with('success', 'Buku berhasil dipinjam!');
    }

    // Proses Pengembalian + Hitung Denda
    public function kembaliBuku($id) {
        $pinjam = Peminjaman::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        
        if ($pinjam->status == 'dikembalikan') {
            return back()->with('error', 'Buku ini sudah dikembalikan!');
        }

        $tgl_sekarang = \Carbon\Carbon::now()->startOfDay();
        $deadline = \Carbon\Carbon::parse($pinjam->deadline)->startOfDay();
        
        $denda = 0;
        $selisih_hari = 0;

        // Cek jika SEKARANG sudah LEWAT dari DEADLINE
        if ($tgl_sekarang->gt($deadline)) {
            // Tambahkan parameter true agar hasilnya SELALU POSITIF
            $selisih_hari = $tgl_sekarang->diffInDays($deadline, true); 
            $denda = $selisih_hari * 1000; 
        }

        $pinjam->update([
            'tanggal_kembali' => now(),
            'status' => 'dikembalikan',
            'denda' => $denda 
        ]);

        $pinjam->buku->increment('stok');

        if ($denda > 0) {
            return back()->with('success', "Buku dikembalikan. Telat $selisih_hari hari, denda: Rp " . number_format($denda, 0, ',', '.'));
        }

        return back()->with('success', 'Buku berhasil dikembalikan tepat waktu!');
    }
}