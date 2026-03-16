<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    // Menampilkan list buku & Fitur Pencarian 
    public function index(Request $request) {
        $search = $request->search;
        
        $bukus = \App\Models\Buku::when($search, function ($query) use ($search) {
            $query->where('judul', 'like', "%{$search}%")
                ->orWhere('penulis', 'like', "%{$search}%")
                ->orWhere('genre', 'like', "%{$search}%");
        })->get();

        // Cek jika request datang dari AJAX
        if ($request->ajax()) {
            return view('admin.buku._table_buku', compact('bukus'))->render();
        }

        return view('admin.buku.index', compact('bukus'));
    }

    // Simpan buku baru (Create) [cite: 88]
    public function store(Request $request) {
        // 1. Gabung semua validasi jadi satu biar efisien
        $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'penerbit' => 'required',
            'genre' => 'required',
            'stok' => 'required|integer|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048' 
        ]);

        // 2. Ambil semua data input
        $data = $request->all();

        // 3. Proses foto kalo ada
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $nama_file = time() . "_" . $file->getClientOriginalName();
            
            // Pindah ke folder public
            $file->move(public_path('storage/buku'), $nama_file);
            
            // Timpa isi $data['foto'] dengan nama file yang baru
            $data['foto'] = $nama_file;
        }

        // 4. PENTING: Pake variabel $data, JANGAN $request->all()
        Buku::create($data); 

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