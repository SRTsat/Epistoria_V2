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
        $total_pinjam = Peminjaman::whereIn('status', ['dipinjam', 'proses_kembali'])->count();
        
        // Ambil SEMUA untuk hitung kas denda
        $semua_transaksi = Peminjaman::all();
        $hari_ini = now()->startOfDay();
        $total_denda = 0;

        foreach ($semua_transaksi as $t) {
            if (in_array($t->status, ['dipinjam', 'proses_kembali'])) {
                $deadline = \Carbon\Carbon::parse($t->deadline)->startOfDay();
                if ($hari_ini->gt($deadline)) {
                    $selisih = $hari_ini->diffInDays($deadline, true);
                    $total_denda += ($selisih * 1000);
                }
            } else {
                $total_denda += max(0, $t->denda);
            }
        }

        $recent_activities = Peminjaman::with(['user', 'buku'])->latest('updated_at')->take(5)->get();
        
        // Ambil buku populer (Gue ambil 5 biar chart-nya kelihatan bagus, kalau 3 kedikitan)
        $populers = Buku::withCount('peminjamans')->orderBy('peminjamans_count', 'desc')->take(5)->get();

        // --- TAMBAHAN UNTUK CHART ---
        $chartLabels = $populers->pluck('judul'); // Ambil semua judul buku
        $chartData = $populers->pluck('peminjamans_count'); // Ambil jumlah berapa kali dipinjam
        // ----------------------------

        return view('admin.dashboard', compact(
            'total_buku', 'total_siswa', 'total_pinjam', 'total_denda', 'recent_activities', 
            'populers', 'chartLabels', 'chartData'
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
            'email' => 'required|email|unique:users', // Tambahkan ini
            'username' => 'required|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,siswa'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            // LANGSUNG AKTIF: Karena admin yang buat, kita anggap email sudah valid
            'email_verified_at' => now(), 
        ]);

        return back()->with('success', 'Akun berhasil ditambahkan dan sudah aktif!');
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