<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard() {
        return view('admin.dashboard'); // Dashboard Admin [cite: 73]
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
}