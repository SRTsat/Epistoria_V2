<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaController extends Controller
{
    public function dashboard() {
        return view('siswa.dashboard'); // Dashboard Siswa [cite: 83]
    }

    // Melakukan Peminjaman [cite: 91, 106]
    public function pinjamBuku(Request $request) {
        Peminjaman::create([
            'user_id' => Auth::id(),
            'buku_id' => $request->buku_id,
            'tanggal_pinjam' => now(),
            'status' => 'dipinjam'
        ]);
        return back()->with('success', 'Buku berhasil dipinjam!');
    }
}
