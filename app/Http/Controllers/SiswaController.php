<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SiswaController extends Controller
{
    // Dashboard Siswa
     public function index() 
    {
        $userId = Auth::id();
        $pinjamans = Peminjaman::where('user_id', $userId)->get();

        $totalDenda = 0;
        $sekarang = now()->startOfDay();

        foreach ($pinjamans as $p) {
            // Tambahin 'proses_kembali' di sini bro
            if (in_array($p->status, ['dipinjam', 'proses_kembali'])) {
                $deadline = \Carbon\Carbon::parse($p->deadline)->startOfDay();
                if ($sekarang->gt($deadline)) {
                    $selisih = $sekarang->diffInDays($deadline, true);
                    $totalDenda += ($selisih * 1000); 
                }
            } else {
                // Ini untuk status 'dikembalikan', 'rusak', atau 'hilang' yang dendanya sudah fix di DB
                $totalDenda += max(0, $p->denda); 
            }
        }

        $totalDipinjam = $pinjamans->whereIn('status', ['dipinjam', 'proses_kembali'])->count();
        $totalRiwayat = $pinjamans->count();
        $bukuTerbaru = Buku::latest()->take(4)->get();

        return view('siswa.dashboard', compact('totalDipinjam', 'totalRiwayat', 'totalDenda', 'bukuTerbaru'));
    }

    // Katalog Buku
    public function katalog(Request $request) 
    {
        $search = $request->search;
        $selectedGenres = $request->genres; 
        $query = Buku::query()->with('genre');

        // Multi-filter Genre - Ganti ke 'genre_id'
        if ($request->filled('genres')) {
            $query->whereIn('genre_id', $selectedGenres);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                ->orWhere('penulis', 'like', "%{$search}%");
            });
        }

        $bukus = $query->get();
        $genres = \App\Models\Genre::all(); // Tambahkan ini buat ditampilin di checkbox filter

        if ($request->ajax()) {
            return view('siswa._buku_list', compact('bukus'))->render();
        }

        // Kirim $genres ke view
        return view('siswa.katalog', compact('bukus', 'genres'));
    }

    // Riwayat Pinjaman Siswa
  public function indexPinjam() 
    {
        $userId = Auth::id();
        $pinjaman = Peminjaman::where('user_id', $userId)->with('buku')->latest()->get();
        
        $totalDendaLive = 0;
        $sekarang = \Carbon\Carbon::now()->startOfDay();

        foreach ($pinjaman as $p) {
            // Hitung denda tiap baris dan tempelkan ke objek $p
            if (in_array($p->status, ['dipinjam', 'proses_kembali'])) {
                $deadline = \Carbon\Carbon::parse($p->deadline)->startOfDay();
                if ($sekarang->gt($deadline)) {
                    $selisih = $sekarang->diffInDays($deadline, true);
                    $p->denda_final = $selisih * 1000; 
                } else {
                    $p->denda_final = 0;
                }
            } else {
                $p->denda_final = max(0, $p->denda);
            }
            
            // Tambahkan ke total keseluruhan
            $totalDendaLive += $p->denda_final;
        }
        
        return view('siswa.pinjam', compact('pinjaman', 'totalDendaLive'));
    }
    // Proses Pinjam
    public function pinjamBuku(Request $request) 
    {
        $request->validate([
            'buku_id' => 'required|exists:bukus,id',
            'durasi' => 'required|integer|min:1|max:20', 
            'kelas' => 'required|string'
        ]);

        $userId = Auth::id();

        // --- VALIDASI 1: CEK APAKAH LAGI PINJAM BUKU ---
        // Status 'menunggu', 'dipinjam', atau 'proses_kembali' dianggap masih megang/pesen buku
        $sedangPinjam = Peminjaman::where('user_id', $userId)
            ->whereIn('status', ['menunggu', 'dipinjam', 'proses_kembali'])
            ->exists();

        if ($sedangPinjam) {
            return redirect()->back()->with('error', 'balikin buku dahulu.');
        }

        // --- VALIDASI 2: CEK APAKAH ADA DENDA YANG BELUM LUNAS ---
        // Cek di riwayat apakah ada kolom 'denda' yang angkanya di atas 0
        $adaDenda = Peminjaman::where('user_id', $userId)
            ->where('denda', '>', 0)
            ->exists();

        if ($adaDenda) {
            return redirect()->back()->with('error', 'lunasi denda terlebih dahulu!');
        }

        // Kalau lolos dua validasi di atas, baru deh buat datanya
        Peminjaman::create([
            'user_id' => $userId,
            'buku_id' => $request->buku_id,
            'kelas' => $request->kelas,
            'tanggal_pinjam' => now(), 
            'deadline' => now()->addDays((int) $request->durasi),
            'status' => 'menunggu',
            'denda' => 0
        ]);

        return redirect()->route('siswa.pinjam')->with('success', 'Permintaan terkirim, tunggu di-accept admin ya!');
    }

    // Proses Kembalikan (Sisi Siswa)
   public function kembaliBuku($id) 
    {
        $pinjam = Peminjaman::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // Pastikan dia cuma bisa klik kalau statusnya 'dipinjam'
        if ($pinjam->status != 'dipinjam') {
            return back()->with('error', 'Status buku tidak valid untuk dikembalikan.');
        }

        // Siswa cuma ganti status ke 'proses_kembali'
        $pinjam->update([
            'status' => 'proses_kembali'
        ]);

        return back()->with('success', 'Laporan pengembalian dikirim. Segera bawa buku ke perpus!');
    }
}