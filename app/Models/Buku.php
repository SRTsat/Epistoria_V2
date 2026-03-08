<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $fillable = ['judul', 'penulis', 'penerbit', 'stok'];

    // Relasi ke Peminjaman (Satu buku bisa dipinjam berkali-kali)
    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class);
    }
}