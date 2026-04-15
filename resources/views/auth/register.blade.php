@extends('layouts.app')

@section('content')

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg bg-white shadow-sm fixed-top py-3">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center fw-bold" href="{{ route('siswa.dashboard') }}">
            <i class="bi bi-book-half me-2 fs-3 text-primary"></i>
            <span>Epis<span class="text-dark">Storia</span></span>
        </a>
    </div>
</nav>

<div class="login-wrapper d-flex align-items-center justify-content-center py-5">
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>
    <div class="shape shape-3"></div>
    
    <div class="container position-relative" style="z-index: 10;">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                
                <div class="card border-0 shadow-xl rounded-5 overflow-hidden auth-card">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-5">
                            <div class="brand-logo mb-3 mx-auto">
                                <i class="bi bi-person-plus-fill"></i>
                            </div>
                            <h3 class="fw-black text-dark mb-1">Daftar Anggota</h3>
                            <p class="text-muted small">Bergabunglah dan mulai jelajahi koleksi kami.</p>
                        </div>

                        <form action="{{ url('/register') }}" method="POST">
                            @csrf
                            
                            <div class="form-floating mb-3">
                                <input type="text" name="name" class="form-control border-0 rounded-4" id="regName" placeholder="Nama Lengkap" required value="{{ old('name') }}">
                                <label for="regName" class="text-muted">
                                    <i class="bi bi-card-heading me-2"></i>Nama Lengkap
                                </label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" name="username" class="form-control border-0 rounded-4 @error('username') is-invalid @enderror" id="regUser" placeholder="Username" required value="{{ old('username') }}">
                                <label for="regUser" class="text-muted">
                                    <i class="bi bi-person me-2"></i>Username
                                </label>
                                @error('username') 
                                    <div class="invalid-feedback ps-2">{{ $message }}</div> 
                                @enderror
                            </div>

                            <div class="row g-2">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="password" name="password" class="form-control border-0 rounded-4" id="regPass" placeholder="Password" required>
                                        <label for="regPass" class="text-muted">
                                            <i class="bi bi-lock me-2"></i>Password
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="password" name="password_confirmation" class="form-control border-0 rounded-4" id="regConfirm" placeholder="Konfirmasi" required>
                                        <label for="regConfirm" class="text-muted">
                                            <i class="bi bi-shield-check me-2"></i>Konfirmasi
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-3 rounded-4 fw-bold shadow-blue mb-4 transition-all">
                                Daftar Sekarang <i class="bi bi-person-check ms-2"></i>
                            </button>

                            <div class="text-center">
                                <p class="text-muted small mb-0">Sudah jadi anggota?</p>
                                <a href="{{ url('/login') }}" class="fw-bold text-primary text-decoration-none">
                                    Login di sini
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-5">
                    <p class="text-muted x-small">Perpustakaan Digital v2.0 &bull; 2026</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    :root {
        --primary: #2563eb;
        --secondary: #38bdf8;
    }

    /* Navbar */
    .navbar {
        background: rgba(255,255,255,0.85) !important;
        backdrop-filter: blur(12px);
    }

    /* Background */
    .login-wrapper {
        min-height: 100vh;
        background: linear-gradient(135deg, #eff6ff, #f8fafc);
        overflow: hidden;
        position: relative;
        padding-top: 90px;
    }

    /* Shapes */
    .shape {
        position: absolute;
        border-radius: 50%;
        filter: blur(100px);
        z-index: 1;
    }

    .shape-1 {
        width: 500px;
        height: 500px;
        background: rgba(37, 99, 235, 0.25);
        top: -150px;
        right: -150px;
    }

    .shape-2 {
        width: 350px;
        height: 350px;
        background: rgba(56, 189, 248, 0.25);
        bottom: -80px;
        left: -80px;
    }

    .shape-3 {
        width: 250px;
        height: 250px;
        background: rgba(147, 197, 253, 0.25);
        top: 50%;
        left: 60%;
        transform: translate(-50%, -50%);
    }

    /* Card */
    .auth-card {
        background: rgba(255, 255, 255, 0.75);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.6) !important;
        box-shadow: 0 20px 60px rgba(0,0,0,0.08);
    }

    /* Logo */
    .brand-logo {
        width: 75px;
        height: 75px;
        border-radius: 25px;
        background: linear-gradient(135deg, #2563eb, #38bdf8);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        box-shadow: 0 10px 25px rgba(37, 99, 235, 0.4);
    }

    .fw-black { 
        font-weight: 900; 
        letter-spacing: -0.5px; 
    }

    /* Input */
    .form-control {
        padding: 1rem 1.2rem;
        transition: 0.3s;
        background-color: #eff6ff !important;
    }

    .form-control:focus {
        background-color: #fff !important;
        box-shadow: 0 10px 25px rgba(37, 99, 235, 0.15) !important;
        transform: translateY(-2px);
    }

    /* Button */
    .btn-primary {
        background: linear-gradient(135deg, #2563eb, #38bdf8);
        border: none;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #1d4ed8, #0ea5e9);
    }

    .shadow-blue {
        box-shadow: 0 10px 25px rgba(37, 99, 235, 0.35);
    }

    .transition-all { 
        transition: all 0.3s ease; 
    }

    .transition-all:hover { 
        transform: translateY(-3px); 
    }

    .x-small { 
        font-size: 11px; 
        letter-spacing: 1px; 
        text-transform: uppercase; 
    }
</style>
@endpush