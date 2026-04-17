@extends('layouts.app') {{-- sesuaikan layout lu --}}

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <i class="bi bi-envelope-check text-primary" style="font-size: 4rem;"></i>
                <h3 class="fw-bold mt-3">Verifikasi Email Dulu.</h3>
                <p class="text-muted">
                    Link aktivasinya udah dikirim ke email. 
                    Cek folder Inbox atau Spam ya. Kalau belum masuk juga, coba tunggu bentar.
                </p>
                <hr>
                <a href="/login" class="btn btn-primary rounded-pill px-4">Kembali ke Login</a>
            </div>
        </div>
    </div>
</div>
@endsection