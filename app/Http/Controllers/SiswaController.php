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
            if (in_array($p->status, ['dipinjam', 'proses_kembali'])) {
                $deadline = \Carbon\Carbon::parse($p->deadline)->startOfDay();
                if ($sekarang->gt($deadline)) {
                    $selisih = $sekarang->diffInDays($deadline, true);
                    $totalDenda += ($selisih * 1000); 
                }
            } else {
                $totalDenda += max(0, $p->denda); 
            }
        }

        // Hitung buku terbaru + stok virtual
        $bukuTerbaru = Buku::with('genre')->withCount(['peminjamans' => function($q) {
            $q->whereIn('status', ['menunggu', 'dipinjam', 'proses_kembali']);
        }])->latest()->take(4)->get();

        foreach ($bukuTerbaru as $b) {
            $b->stok_tersedia = max(0, $b->stok - $b->peminjamans_count);
        }

        $totalDipinjam = $pinjamans->whereIn('status', ['dipinjam', 'proses_kembali'])->count();
        $totalRiwayat = $pinjamans->count();

        // JANGAN timpa $bukuTerbaru lagi di sini!

        return view('siswa.dashboard', compact('totalDipinjam', 'totalRiwayat', 'totalDenda', 'bukuTerbaru'));
    }

    // Katalog Buku
    public function katalog(Request $request) 
    {
        $search = $request->search;
        $selectedGenres = $request->genres; 
        
        // Gunakan withCount untuk menghitung transaksi aktif
        $query = Buku::query()->with('genre')->withCount(['peminjamans' => function($q) {
            $q->whereIn('status', ['menunggu', 'dipinjam', 'proses_kembali']);
        }]);

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

        // Tambahkan logic "stok_tersedia" secara dinamis
        foreach ($bukus as $b) {
            // Stok Tersedia = Stok di DB - Jumlah Antrean/Pinjam
            $b->stok_tersedia = max(0, $b->stok - $b->peminjamans_count);
        }

        $genres = \App\Models\Genre::all();

        if ($request->ajax()) {
            return view('siswa._buku_list', compact('bukus'))->render();
        }

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
        $buku = Buku::withCount(['peminjamans' => function($q) {
            $q->whereIn('status', ['menunggu', 'dipinjam', 'proses_kembali']);
        }])->findOrFail($request->buku_id);

        // --- VALIDASI STOK VIRTUAL ---
        $stokTersedia = $buku->stok - $buku->peminjamans_count;
        if ($stokTersedia <= 0) {
            return redirect()->back()->with('error', 'Waduh, buku ini baru saja dipesan orang lain. Stok habis!');
        }

        // --- VALIDASI 1: CEK APAKAH LAGI PINJAM BUKU ---
        $sedangPinjam = Peminjaman::where('user_id', $userId)
            ->whereIn('status', ['menunggu', 'dipinjam', 'proses_kembali'])
            ->exists();

        if ($sedangPinjam) {
            return redirect()->back()->with('error', 'Selesaikan peminjaman yang ada dahulu.');
        }

        // --- VALIDASI 2: CEK APAKAH ADA DENDA ---
        $adaDenda = Peminjaman::where('user_id', $userId)
            ->where('denda', '>', 0)
            ->exists();

        if ($adaDenda) {
            return redirect()->back()->with('error', 'Lunasi denda terlebih dahulu!');
        }

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