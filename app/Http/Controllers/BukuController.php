<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class BukuController extends Controller
{
    // Menampilkan list buku & Fitur Pencarian 
   public function index(Request $request) {
        $search = $request->search;
        $selectedGenres = $request->genres; // Pakai nama variabel beda biar gak bentrok

        $query = \App\Models\Buku::query()->with('genre');

        // Multi-filter Genre - Ganti ke 'genre_id'
        if ($request->filled('genres')) {
            $query->whereIn('genre_id', $selectedGenres);
        }

        // Live Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                ->orWhere('penulis', 'like', "%{$search}%");
            });
        }

        $bukus = $query->get();
        $genres = \App\Models\Genre::all(); // Ambil semua data genre untuk pilihan filter di view

        if ($request->ajax()) {
            return view('admin.buku._table_buku', compact('bukus'))->render();
        }

        // Pastikan $genres dikirim ke view index
        return view('admin.buku.index', compact('bukus', 'genres'));
    }

    // Simpan buku baru (Create) [cite: 88]
    public function store(Request $request) {
        // 1. Gabung semua validasi jadi satu biar efisien
        $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'penerbit' => 'required',
            'genre_id' => 'required|exists:genres,id',
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
    public function update(Request $request, $id)
        {
            $buku = Buku::findOrFail($id);

            $request->validate([
                'judul' => 'required',
                'penulis' => 'required',
                'penerbit' => 'required',
                'genre_id' => 'required|exists:genres,id',
                'stok' => 'required|integer',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            $data = $request->all();

            if ($request->hasFile('foto')) {
                if ($buku->foto) {
                    Storage::delete('public/buku/' . $buku->foto);
                }
                $file = $request->file('foto');
                $nama_file = time() . "_" . $file->getClientOriginalName();
                $file->storeAs('public/buku', $nama_file);
                $data['foto'] = $nama_file;
            }

            $buku->update($data);
            return redirect()->route('buku.index')->with('success', 'Buku berhasil diupdate!');
    }

    // Hapus buku (Delete) [cite: 88]
    public function destroy($id) {
        Buku::destroy($id);
        return back()->with('success', 'Buku berhasil dihapus!');
    }

    public function exportPdf()
    {
        $bukus = Buku::all();
        
        // Kita panggil view khusus buat format PDF
        $pdf = Pdf::loadView('admin.buku.pdf', compact('bukus'));
        
        // Download file-nya
        return $pdf->download('laporan-buku-'.date('Y-m-d').'.pdf');
    }
}