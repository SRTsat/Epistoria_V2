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
            if ($p->status == 'dipinjam') {
                $deadline = \Carbon\Carbon::parse($p->deadline)->startOfDay();
                if ($sekarang->gt($deadline)) {
                    // Tambahkan TRUE agar tidak 0 atau minus
                    $selisih = $sekarang->diffInDays($deadline, true);
                    $totalDenda += ($selisih * 1000); 
                }
            } else {
                $totalDenda += max(0, $p->denda); 
            }
        }

        $totalDipinjam = $pinjamans->where('status', 'dipinjam')->count();
        $totalRiwayat = $pinjamans->count();
        $bukuTerbaru = Buku::latest()->take(4)->get();

        return view('siswa.dashboard', compact('totalDipinjam', 'totalRiwayat', 'totalDenda', 'bukuTerbaru'));
    }

    // Katalog Buku
    public function katalog(Request $request) 
    {
        $search = $request->search;
        $genres = $request->genres; 
        $query = Buku::query();

        if ($request->filled('genres')) {
            $query->whereIn('genre', $genres);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('penulis', 'like', "%{$search}%");
            });
        }

        $bukus = $query->get();

        if ($request->ajax()) {
            return view('siswa._buku_list', compact('bukus'))->render();
        }

        return view('siswa.katalog', compact('bukus'));
    }

    // Riwayat Pinjaman Siswa
   public function indexPinjam() 
    {
        $userId = Auth::id();
        $pinjaman = Peminjaman::where('user_id', $userId)->with('buku')->latest()->get();
        
        $totalDenda = 0;
        $sekarang = Carbon::now()->startOfDay();

        foreach ($pinjaman as $p) {
            if ($p->status == 'dipinjam') {
                $deadline = Carbon::parse($p->deadline)->startOfDay();
                if ($sekarang->gt($deadline)) {
                    $selisih = $sekarang->diffInDays($deadline, true);
                    $totalDenda += ($selisih * 1000);
                }
            } else {
                $totalDenda += max(0, $p->denda);
            }
        }
        
        return view('siswa.pinjam', compact('pinjaman', 'totalDenda'));
    }

    // Proses Pinjam
    public function pinjamBuku(Request $request) 
    {
        $request->validate([
            'buku_id' => 'required|exists:bukus,id',
            'durasi' => 'required|integer|min:1|max:20', 
            'kelas' => 'required|string'
        ]);

        $buku = Buku::findOrFail($request->buku_id);

        if ($buku->stok <= 0) {
            return back()->with('error', 'Stok buku habis!');
        }

        Peminjaman::create([
            'user_id' => Auth::id(),
            'buku_id' => $request->buku_id,
            'kelas' => $request->kelas,
            'tanggal_pinjam' => Carbon::now(),
            'deadline' => Carbon::now()->addDays((int) $request->durasi),
            'status' => 'dipinjam',
            'denda' => 0
        ]);

        $buku->decrement('stok');
        return redirect()->route('siswa.pinjam')->with('success', 'Buku berhasil dipinjam!');
    }

    // Proses Kembalikan (Sisi Siswa)
    public function kembaliBuku($id) 
    {
        $pinjam = Peminjaman::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
    
        if ($pinjam->status == 'dikembalikan') {
            return back()->with('error', 'Buku ini sudah dikembalikan!');
        }

        $sekarang = Carbon::now()->startOfDay();
        $deadline = Carbon::parse($pinjam->deadline)->startOfDay();
        $denda = 0;
        $selisih_hari = 0;

        if ($sekarang->gt($deadline)) {
            $selisih_hari = $sekarang->diffInDays($deadline);
            $denda = $selisih_hari * 1000; 
        }

        $pinjam->update([
            'tanggal_kembali' => Carbon::now(),
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