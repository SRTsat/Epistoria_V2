<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Tambahin ini di atas!

class Genre extends Model
{
    protected $fillable = ['nama', 'slug'];

    // Kita pake Booted Function biar slug keisi otomatis tiap nambah data
    protected static function booted()
    {
        static::creating(function ($genre) {
            $genre->slug = Str::slug($genre->nama);
        });
    }

    public function bukus()
    {
        return $this->hasMany(Buku::class, 'genre_id');
    }
}