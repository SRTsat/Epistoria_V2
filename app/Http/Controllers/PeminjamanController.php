<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\TransaksiExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    // 1. List Transaksi Admin (Dashboard & Riwayat)
    public function indexAdmin(Request $request) 
    {
        $tahun = $request->get('tahun', 'semua'); 

        // Bikin base query
        $query = Peminjaman::with(['user', 'buku'])->latest();

        // Kalau milih tahun spesifik (bukan 'semua'), baru kita filter
        if ($tahun !== 'semua') {
            $query->whereYear('created_at', $tahun);
        }

        // Ambil datanya (HANYA SEKALI INI SAJA, jangan ditimpa lagi di bawah!)
        $transaksi = $query->get();
        
        // List tahun buat dropdown (tetap ambil semua tahun yang pernah ada di DB)
        $list_tahun = Peminjaman::selectRaw('YEAR(created_at) as tahun')
                    ->distinct()
                    ->orderBy('tahun', 'desc')
                    ->get();

        // 5. Hitung Denda (Pake variabel $transaksi yang udah ke-filter di atas)
        $sekarang = Carbon::now()->startOfDay();
        $totalDendaKeseluruhan = 0;

        foreach ($transaksi as $t) {
            // Logika status 'dipinjam' atau 'proses_kembali' (Admin perlu liat denda berjalan)
            if (in_array($t->status, ['dipinjam', 'proses_kembali'])) {
                $deadline = Carbon::parse($t->deadline)->startOfDay();
                if ($sekarang->gt($deadline)) {
                    $selisih = $sekarang->diffInDays($deadline, true);
                    $t->denda_saat_ini = $selisih * 1000;
                } else {
                    $t->denda_saat_ini = 0;
                }
            } else {
                $t->denda_saat_ini = max(0, $t->denda);
            }
            $totalDendaKeseluruhan += $t->denda_saat_ini;
        }
        
        return view('admin.transaksi.index', [
            'transaksi' => $transaksi,
            'totalDenda' => $totalDendaKeseluruhan, 
            'tahun' => $tahun,
            'list_tahun' => $list_tahun
        ]);
    }

    // 2. Proses Pinjam Buku (Sisi Siswa/Admin)
    public function store(Request $request) 
    {
        $request->validate(['buku_id' => 'required|exists:bukus,id']);
        $buku = Buku::find($request->buku_id);

        if ($buku->stok <= 0) {
            return back()->with('error', 'Maaf, stok buku sedang kosong!');
        }

        Peminjaman::create([
            'user_id' => Auth::id(),
            'buku_id' => $request->buku_id,
            'tanggal_pinjam' => Carbon::now(),
            'deadline' => Carbon::now()->addDays(7)->startOfDay(), // Deadline bersih jam 00:00
            'status' => 'dipinjam',
            'denda' => 0
        ]);

        $buku->decrement('stok');
        return back()->with('success', 'Buku berhasil dipinjam!');
    }

    // 3. Proses Pengembalian (Update Denda ke DB)
    public function kembalikan($id) 
    {
        $pinjam = Peminjaman::findOrFail($id);
        
        if ($pinjam->status === 'dikembalikan') {
            return back()->with('error', 'Buku ini sudah dikembalikan.');
        }

        $tgl_kembali = Carbon::now()->startOfDay();
        $deadline = Carbon::parse($pinjam->deadline)->startOfDay();
        $denda = 0;

        // Logika hitung denda saat klik kembali
        if ($tgl_kembali->gt($deadline)) {
            $selisih_hari = $tgl_kembali->diffInDays($deadline, true);
            $denda = $selisih_hari * 1000;
        }

        $pinjam->update([
            'tanggal_kembali' => Carbon::now(),
            'status' => 'dikembalikan',
            'denda' => $denda 
        ]);

        if ($pinjam->buku) {
            $pinjam->buku->increment('stok');
        }
    
        if ($denda > 0) {
        return back()->with('success', "Buku kembali. Terlambat, denda: Rp " . number_format($denda, 0, ',', '.'));
        }

        return back()->with('success', 'Buku sudah kembali tepat waktu!');
    }

    // 4. Konfirmasi Pembayaran Denda
    public function bayarDenda($id) 
    {
        $pinjam = Peminjaman::findOrFail($id);
        
        // Proteksi: jangan sampai denda dihapus tapi buku belum balik
        if ($pinjam->status == 'dipinjam') {
            return back()->with('error', 'Buku harus dikembalikan terlebih dahulu!');
        }

        $pinjam->update(['denda' => 0]);
        return back()->with('success', 'Denda berhasil dilunasi!');
    }

    // 5. Export PDF
    public function exportPdf(Request $request) 
    {
        $tahun = $request->get('tahun', date('Y'));

        // Filter query berdasarkan tahun
        $transaksi = Peminjaman::with(['user', 'buku'])
                    ->whereYear('created_at', $tahun)
                    ->latest()
                    ->get();

        // Sum denda juga harus di-filter per tahun biar akurat
        $totalDenda = Peminjaman::whereYear('created_at', $tahun)
                    ->where('denda', '>', 0)
                    ->sum('denda');
        
        $pdf = Pdf::loadView('admin.transaksi.pdf', compact('transaksi', 'totalDenda', 'tahun'))
                    ->setPaper('a4', 'landscape');
                    
        return $pdf->download("Laporan-Perpus-$tahun.pdf");
    }

    public function approvePinjam($id) {
        $pinjam = Peminjaman::findOrFail($id);
        
        if ($pinjam->buku->stok <= 0) return back()->with('error', 'Stok habis!');

        $pinjam->update(['status' => 'dipinjam']);
        $pinjam->buku->decrement('stok'); // Stok berkurang saat buku keluar
        return back()->with('success', 'Buku resmi dipinjamkan!');
    }

    public function konfirmasiTerima($id) 
    {
        $pinjam = Peminjaman::findOrFail($id);

        // Itung Denda (Pindahkan logika lu ke sini)
        $sekarang = Carbon::now()->startOfDay();
        $deadline = Carbon::parse($pinjam->deadline)->startOfDay();
        $denda = 0;

        if ($sekarang->gt($deadline)) {
            $selisih_hari = $sekarang->diffInDays($deadline);
            $denda = $selisih_hari * 1000; 
        }

        $pinjam->update([
            'tanggal_kembali' => Carbon::now(),
            'status' => 'dikembalikan', // Sekarang statusnya resmi balik
            'denda' => $denda 
        ]);

        // Stok baru nambah pas Admin yang klik
        $pinjam->buku->increment('stok');

        return back()->with('success', 'Buku diterima! Status diperbarui.');
    }
    
    // 6. Export Excel
    public function exportExcel(Request $request) 
    {
        // Ambil tahun dari request, default tahun sekarang
        $tahun = $request->get('tahun', date('Y'));
        
        // Kirim variabel tahun ke class Export lu
        return Excel::download(new TransaksiExport($tahun), "Laporan-Perpus-$tahun.xlsx");
    }
}