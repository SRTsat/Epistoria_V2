<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $fillable = ['judul', 'penulis', 'penerbit', 'stok', 'foto', 'genre_id'];

    // Relasi ke Peminjaman (Satu buku bisa dipinjam berkali-kali)
    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class);
    }

    public function genre()
    {
        // Pastikan foreign key-nya 'genre_id'
        return $this->belongsTo(Genre::class, 'genre_id');
    }
}