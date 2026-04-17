<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Menampilkan halaman login sesuai Flowmap [cite: 61]
    public function showLogin() {
        return view('auth.login');
    }

    // Proses Validasi Login [cite: 70, 82]
    public function login(Request $request) {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Cek apakah user sudah verifikasi email (opsional untuk pesan khusus)
            if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail()) {
                // User akan otomatis diarahkan ke /email/verify oleh middleware 'verified'
                // Tapi kita pastikan redirect ke dashboard dulu biar middleware-nya bekerja
                return $user->role === 'admin' 
                    ? redirect()->intended('/admin/dashboard') 
                    : redirect()->intended('/siswa/dashboard');
            }
            
            return $user->role === 'admin' 
                ? redirect()->intended('/admin/dashboard') 
                : redirect()->intended('/siswa/dashboard');
        }

        return back()->withErrors(['username' => 'Username atau Password salah!']);
    }

    // Menampilkan halaman daftar anggota [cite: 79]
    public function showRegister() {
        return view('auth.register');
    }

    // Proses Daftar Anggota Siswa 
    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users', // Tambahkan ini
            'username' => 'required|string|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email, // Simpan email
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'siswa',
        ]);

        // TRIGGER EMAIL: Mengirim email verifikasi secara otomatis
        event(new \Illuminate\Auth\Events\Registered($user));

        return redirect('/login')->with('success', 'Pendaftaran berhasil! Silakan cek email kamu untuk aktivasi akun.');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}