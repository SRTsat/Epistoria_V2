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
            
            // Redirect berdasarkan Role [cite: 133, 134]
            return Auth::user()->role === 'admin' 
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
            'username' => 'required|string|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'siswa', // Default pendaftar adalah siswa
        ]);

        return redirect('/login')->with('success', 'Pendaftaran berhasil, silakan login!');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}