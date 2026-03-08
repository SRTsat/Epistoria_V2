<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Form Login
    public function showLogin() {
        return view('auth.login');
    }

    // Proses Login (Validasi Login di Flowmap) [cite: 70, 82]
    public function login(Request $request) {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Redirect sesuai Role [cite: 66, 68]
            if (Auth::user()->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            }
            return redirect()->intended('/siswa/dashboard');
        }

        return back()->withErrors(['username' => 'Login gagal, cek lagi bro!']);
    }

    // Daftar Anggota buat Siswa [cite: 79]
    public function register(Request $request) {
        $data = $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'role' => 'siswa', // Default pendaftar adalah siswa
        ]);

        return redirect('/login')->with('success', 'Berhasil daftar, silakan login!');
    }

    public function logout() {
        Auth::logout();
        return redirect('/login');
    }
}
