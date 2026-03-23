<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\User;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard() {
        // Ambil statistik buat ditampilin di Card Dashboard
        $total_buku = Buku::count();
        $total_siswa = User::where('role', 'siswa')->count();
        $total_pinjam = Peminjaman::where('status', 'dipinjam')->count();
        
        // Ngitung semua denda yang sudah terkumpul di database
        $total_denda = Peminjaman::sum('denda'); 
        $recent_activities = Peminjaman::with(['user', 'buku'])->latest('updated_at')->take(5)->get();
        $populers = Buku::withCount('peminjamans')
        ->orderBy('peminjamans_count', 'desc')
        ->take(3)
        ->get();

        return view('admin.dashboard', compact(
            'total_buku', 
            'total_siswa', 
            'total_pinjam', 
            'total_denda',
            'recent_activities',
            'populers'
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
        $anggotas = User::where('role', 'siswa')->get();
        return view('admin.anggota.index', compact('anggotas'));
    }

    public function storeAnggota(Request $request) {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required|min:6'
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'role' => 'siswa'
        ]);

        return back()->with('success', 'Anggota berhasil ditambahkan!');
    }

    // Update data siswa
    public function updateAnggota(Request $request, $id) {
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->username = $request->username;
        
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
        
        $user->save();
        return back()->with('success', 'Data anggota berhasil diupdate!');
    }

    // Hapus siswa
    public function destroyAnggota($id) {
        User::destroy($id);
        return back()->with('success', 'Anggota berhasil dihapus!');
    }
}