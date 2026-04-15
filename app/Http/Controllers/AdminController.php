<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\User;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class AdminController extends Controller
{
   public function dashboard() {
        $total_buku = Buku::count();
        $total_siswa = User::where('role', 'siswa')->count();
        $total_pinjam = Peminjaman::where('status', 'dipinjam')->count();
        
        // 1. Denda dari buku yang sudah dikembalikan (Permanen di DB)
        $denda_permanen = Peminjaman::where('status', 'dikembalikan')->sum('denda'); 

        // 2. Hitung denda Berjalan (Belum balik tapi sudah telat)
        $hari_ini = now()->startOfDay();
        $pinjaman_aktif = Peminjaman::where('status', 'dipinjam')->get();

        $denda_berjalan = 0;
        foreach ($pinjaman_aktif as $p) {
            $deadline = \Carbon\Carbon::parse($p->deadline)->startOfDay();
            // Cek jika hari ini sudah melewati deadline
            if ($hari_ini->gt($deadline)) {
                // PARAMETER TRUE: Supaya hasil selisih selalu POSITIF
                $selisih = $hari_ini->diffInDays($deadline, true);
                $denda_berjalan += ($selisih * 1000);
            }
        }

        // Total gabungan (Pastikan max 0 supaya tidak ada keajaiban angka minus)
        $total_denda = max(0, $denda_permanen + $denda_berjalan);

        $recent_activities = Peminjaman::with(['user', 'buku'])->latest('updated_at')->take(5)->get();
        $populers = Buku::withCount('peminjamans')->orderBy('peminjamans_count', 'desc')->take(3)->get();

        return view('admin.dashboard', compact(
            'total_buku', 'total_siswa', 'total_pinjam', 'total_denda', 'recent_activities', 'populers'
        ));
    }

    // Contoh CRUD Buku [cite: 88, 93]
    public function indexBuku() {
        $bukus = Buku::all();
        return view('admin.buku.index', compact('bukus'));
    }

    public function storeBuku(Request $request) {
        $request->validate(['judul' => 'required', 'penulis' => 'required', 'stok' => 'integer']);
        Buku::create($request->all());
        return back()->with('success', 'Buku berhasil ditambah!');
    }
    
    // Kelola Anggota [cite: 90, 103]
    public function indexAnggota() {
        // Ambil semua user supaya admin bisa liat daftar admin lain juga
        $anggotas = User::latest()->get(); 
        return view('admin.anggota.index', compact('anggotas'));
    }

    public function storeAnggota(Request $request) {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,siswa' // Validasi role
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'role' => $request->role // Simpan sesuai input
        ]);

        return back()->with('success', 'Akun berhasil ditambahkan!');
    }

    // Update data siswa
    public function updateAnggota(Request $request, $id) {
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->username = $request->username;
        $user->role = $request->role; // Admin juga bisa ubah role user lain
        
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
        
        $user->save();
        return back()->with('success', 'Data berhasil diupdate!');
    }

    // Hapus siswa
    public function destroyAnggota($id) {
        User::destroy($id);
        return back()->with('success', 'Anggota berhasil dihapus!');
    }
}