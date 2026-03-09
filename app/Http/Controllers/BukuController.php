<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    // Menampilkan list buku & Fitur Pencarian 
    public function index(Request $request) {
        $query = Buku::query();
        if ($request->has('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('penulis', 'like', '%' . $request->search . '%');
        }
        $bukus = $query->get();
        return view('admin.buku.index', compact('bukus'));
    }

    // Simpan buku baru (Create) [cite: 88]
    public function store(Request $request) {
        $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'penerbit' => 'required',
            'stok' => 'required|integer|min:0',
        ]);

        Buku::create($request->all());
        return back()->with('success', 'Buku berhasil ditambahkan!');
    }

    // Update buku [cite: 88]
    public function update(Request $request, $id) {
        $buku = Buku::findOrFail($id);
        $buku->update($request->all());
        return back()->with('success', 'Data buku diperbarui!');
    }

    // Hapus buku (Delete) [cite: 88]
    public function destroy($id) {
        Buku::destroy($id);
        return back()->with('success', 'Buku berhasil dihapus!');
    }
}