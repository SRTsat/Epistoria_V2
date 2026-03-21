<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon; // WAJIB: Buat itung-itungan tanggal

class PeminjamanController extends Controller
{
    // List transaksi untuk Admin (Lengkap dengan Denda)
    public function indexAdmin() {
        $transaksi = Peminjaman::with(['user', 'buku'])->latest()->get();
        // Itung total denda buat pajangan di dashboard nanti
        $totalDenda = Peminjaman::sum('denda'); 
        
        return view('admin.transaksi.index', compact('transaksi', 'totalDenda'));
    }

    // Proses Peminjaman Buku oleh Siswa (Set Deadline Otomatis)
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
            'deadline' => now()->addDays(7), // SET DEADLINE: 7 hari dari sekarang
            'status' => 'dipinjam'
        ]);

        $buku->decrement('stok');

        return back()->with('success', 'Buku berhasil dipinjam! Kembalikan sebelum 7 hari ya.');
    }

    // Proses Pengembalian Buku oleh Siswa (Hitung Denda)
    public function kembalikan($id) {
        $pinjam = Peminjaman::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        
        if ($pinjam->status === 'dikembalikan') {
            return back()->with('error', 'Buku ini sudah dikembalikan sebelumnya.');
        }

        $tgl_kembali = now();
        $deadline = Carbon::parse($pinjam->deadline);
        $denda = 0;

        // LOGIKA DENDA: Cek telat atau enggak
        if ($tgl_kembali->gt($deadline)) {
            $selisih_hari = $tgl_kembali->diffInDays($deadline);
            $denda = $selisih_hari * 1000; // Contoh: 1000 per hari
        }

        $pinjam->update([
            'tanggal_kembali' => $tgl_kembali,
            'status' => 'dikembalikan',
            'denda' => $denda // Simpan nominal denda ke DB
        ]);

        $pinjam->buku->increment('stok');

        $pesan = "Terima kasih, buku telah dikembalikan!";
        if ($denda > 0) {
            $pesan .= " Anda telat $selisih_hari hari. Denda: Rp " . number_format($denda);
        }

        return back()->with('success', $pesan);
    }

    public function exportPdf() {
        $transaksi = Peminjaman::with(['user', 'buku'])->latest()->get();
        $totalDenda = Peminjaman::sum('denda');
        
        $pdf = Pdf::loadView('admin.transaksi.pdf', compact('transaksi', 'totalDenda'))
                ->setPaper('a4', 'landscape'); // Landscape biar muat banyak kolom
                
        return $pdf->download('Laporan-Transaksi-Perpus-'.now()->format('d-m-Y').'.pdf');
    }
}