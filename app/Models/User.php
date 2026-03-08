<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'username', 'password', 'role'];

    // Relasi ke Peminjaman (Satu user bisa punya banyak transaksi pinjam)
    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class);
    }

    // Helper buat ngecek role di Controller/Middleware nanti
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}