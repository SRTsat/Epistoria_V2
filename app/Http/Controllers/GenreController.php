<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    public function index()
    {
        $genres = Genre::withCount('bukus')->get(); // Sekalian hitung ada berapa buku per genre
        return view('admin.genre.index', compact('genres'));
    }

    public function store(Request $request)
    {
        $request->validate(['nama' => 'required|unique:genres,nama']);
        Genre::create($request->all());
        return back()->with('success', 'Genre berhasil ditambah!');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['nama' => 'required|unique:genres,nama,'.$id]);
        $genre = Genre::findOrFail($id);
        
        // Manual update slug pas edit nama
        $genre->update([
            'nama' => $request->nama,
            'slug' => \Illuminate\Support\Str::slug($request->nama)
        ]);
        
        return back()->with('success', 'Genre berhasil diupdate!');
    }
    
    public function destroy($id)
    {
        $genre = Genre::findOrFail($id);
        // Proteksi: Jangan hapus kalau masih ada bukunya
        if ($genre->bukus()->count() > 0) {
            return back()->with('error', 'Gak bisa dihapus! Masih ada buku di genre ini.');
        }
        $genre->delete();
        return back()->with('success', 'Genre berhasil dihapus!');
    }
}