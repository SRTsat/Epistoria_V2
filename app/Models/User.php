<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail; // WAJIB: Biar fitur verifikasi aktif
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail // Tambahkan ini
{
    use Notifiable;

    // Tambahkan 'email' dan 'email_verified_at' agar bisa diisi (Mass Assignment)
    protected $fillable = [
        'name', 
        'username', 
        'email', 
        'password', 
        'role', 
        'email_verified_at'
    ];

    // Relasi ke Peminjaman
    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class);
    }

    // Helper role
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}