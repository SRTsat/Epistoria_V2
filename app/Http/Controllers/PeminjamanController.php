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
        $query = Peminjaman::with(['user', 'buku'])->latest();

        if ($tahun !== 'semua') {
            $query->whereYear('created_at', $tahun);
        }

        // --- 1. HITUNG TOTAL DENDA DARI SEMUA DATA (SEBELUM PAGINATE) ---
        // Kita ambil semua data yang sesuai filter tahun buat itung denda asli
        $semuaTransaksiUntukDenda = $query->get(); 
        $sekarang = Carbon::now()->startOfDay();
        $totalDendaKeseluruhan = 0;

        foreach ($semuaTransaksiUntukDenda as $t) {
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

        // --- 2. BARU KITA PAGINATE BUAT TAMPILAN TABEL ---
        $transaksi = $query->paginate(10)->appends($request->all());
        
        // Loop sekali lagi buat data yang di-paginate biar label denda di tabel muncul
        foreach ($transaksi as $t) {
            if (in_array($t->status, ['dipinjam', 'proses_kembali'])) {
                $deadline = Carbon::parse($t->deadline)->startOfDay();
                $t->denda_saat_ini = $sekarang->gt($deadline) ? $sekarang->diffInDays($deadline, true) * 1000 : 0;
            } else {
                $t->denda_saat_ini = max(0, $t->denda);
            }
        }

        $list_tahun = Peminjaman::selectRaw('YEAR(created_at) as tahun')
                    ->distinct()
                    ->orderBy('tahun', 'desc')
                    ->get();

        return view('admin.transaksi.index', [
            'transaksi' => $transaksi,
            'totalDenda' => $totalDendaKeseluruhan, // Angka ini sekarang dari SEMUA data
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
            'deadline' => Carbon::now()->addDays(7)->startOfDay(),
            'status' => 'dipinjam',
            'denda' => 0
        ]);

        $buku->decrement('stok');
        return back()->with('success', 'Buku berhasil dipinjam!');
    }

    // 3. Konfirmasi Terima & Filter Stok (Update Sesuai Saran Guru)
    public function konfirmasiTerima(Request $request, $id) 
    {
        $request->validate([
            'kondisi' => 'required|in:normal,rusak,hilang',
            'denda_tambahan' => 'nullable|numeric|min:0'
        ]);

        $pinjam = Peminjaman::findOrFail($id);
        $buku = $pinjam->buku;

        $sekarang = Carbon::now()->startOfDay();
        $deadline = Carbon::parse($pinjam->deadline)->startOfDay();
        $denda_telat = 0;

        if ($sekarang->gt($deadline)) {
            $selisih_hari = $sekarang->diffInDays($deadline, true);
            $denda_telat = $selisih_hari * 1000; 
        }

        $denda_fisik = $request->denda_tambahan ?? 0;
        $total_denda = $denda_telat + $denda_fisik;

        $status_final = ($request->kondisi === 'normal') ? 'dikembalikan' : $request->kondisi;

        $pinjam->update([
            'tanggal_kembali' => Carbon::now(),
            'status' => $status_final,
            'denda' => $total_denda 
        ]);

        // LOGIC CRITICAL: Hanya buku NORMAL yang stoknya nambah otomatis
        if ($request->kondisi === 'normal') {
            $buku->increment('stok');
        }

        return back()->with('success', "Buku diproses! Kondisi: " . ucfirst($request->kondisi));
    }

    // 4. Halaman Khusus Buku Rusak (Daftar Tunggu Perbaikan)
    public function bukuRusak()
    {
        // Ambil data yang statusnya 'rusak' saja
        $rusaks = Peminjaman::with(['user', 'buku'])->where('status', 'rusak')->latest()->get();
        return view('admin.buku_rusak', compact('rusaks'));
    }

    // 5. Tombol Perbaiki (Balikin ke Stok)
    public function perbaikiBuku($id)
    {
        $pinjam = Peminjaman::findOrFail($id);
        
        // Tambah stok karena sudah diperbaiki
        $pinjam->buku->increment('stok');

        // Ubah status jadi dikembalikan agar hilang dari daftar "Buku Rusak"
        $pinjam->update(['status' => 'dikembalikan']);

        return back()->with('success', 'Buku sudah diperbaiki dan masuk ke stok kembali!');
    }

    // 6. Sisanya (PDF, Excel, Bayar Denda, Approve) - Tetap Sama
    public function bayarDenda($id) 
    {
        $pinjam = Peminjaman::findOrFail($id);
        if ($pinjam->status == 'dipinjam') {
            return back()->with('error', 'Buku harus dikembalikan terlebih dahulu!');
        }
        $pinjam->update(['denda' => 0]);
        return back()->with('success', 'Denda berhasil dilunasi!');
    }

    public function exportPdf(Request $request) 
    {
        $tahun = $request->get('tahun', 'semua');
        $query = Peminjaman::with(['user', 'buku'])->latest();
        
        if ($tahun !== 'semua') { 
            $query->whereYear('created_at', $tahun); 
        }
        
        $transaksi = $query->get();
        $sekarang = Carbon::now()->startOfDay();

        // 1. Inisialisasi variabel total
        $totalDenda = 0; 

        foreach ($transaksi as $t) {
            if (in_array($t->status, ['dipinjam', 'proses_kembali'])) {
                $deadline = Carbon::parse($t->deadline)->startOfDay();
                $t->denda_saat_ini = $sekarang->gt($deadline) ? $sekarang->diffInDays($deadline, true) * 1000 : 0;
            } else {
                $t->denda_saat_ini = max(0, $t->denda);
            }
            
            // 2. Tambahkan ke total setiap kali loop jalan
            $totalDenda += $t->denda_saat_ini;
        }
        
        // 3. Masukkan 'totalDenda' ke dalam array data view
        $pdf = Pdf::loadView('admin.transaksi.pdf', [
            'transaksi' => $transaksi, 
            'tahun' => $tahun,
            'totalDenda' => $totalDenda // <-- WAJIB ADA INI
        ])->setPaper('a4', 'landscape');

        return $pdf->download("Laporan-Perpus-$tahun.pdf");
    }

    public function approvePinjam($id) {
        $pinjam = Peminjaman::findOrFail($id);
        if ($pinjam->buku->stok <= 0) return back()->with('error', 'Stok habis!');
        $pinjam->update(['status' => 'dipinjam']);
        $pinjam->buku->decrement('stok');
        return back()->with('success', 'Buku resmi dipinjamkan!');
    }

    public function exportExcel(Request $request) 
    {
        $tahun = $request->get('tahun', date('Y'));
        return Excel::download(new TransaksiExport($tahun), "Laporan-Perpus-$tahun.xlsx");
    }
}