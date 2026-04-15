@extends('layouts.siswa')

@section('content')
<div class="container py-4">
    <div class="card border-0 rounded-4 mb-4 overflow-hidden shadow-sm" 
         style="background: linear-gradient(135deg, #0d6efd 0%, #00d2ff 100%); position: relative;">
         
        <div class="card-body p-4 p-md-5 text-white position-relative" style="z-index: 2;">
            <h2 class="fw-bold">Selamat Datang, {{ Auth::user()->name }}!</h2>
            <p class="opacity-75">"Buku adalah jendela dunia." Temukan petualangan baru di setiap halaman.</p>
            <a href="{{ route('siswa.katalog') }}" class="btn btn-light rounded-pill px-4 fw-bold text-primary shadow-sm">
                <i class="bi bi-search me-1"></i> Mulai Cari Buku
            </a>
        </div>

        <i class="bi bi-book position-absolute end-0 bottom-0 opacity-25 mb-n4 me-n2 text-white" 
        style="font-size: 12rem; line-height: 1; pointer-events: none;"></i>
    </div>

    <div class="row g-3 mb-5">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-4 me-3">
                        <i class="bi bi-journal-check fs-3"></i>
                    </div>
                    <div>
                        <div class="small text-muted fw-bold">Sedang Dipinjam</div>
                        <h4 class="fw-bold mb-0 text-dark">{{ $totalDipinjam }} <small class="fs-6 fw-normal">Buku</small></h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="bg-success bg-opacity-10 text-success p-3 rounded-4 me-3">
                        <i class="bi bi-clock-history fs-3"></i>
                    </div>
                    <div>
                        <div class="small text-muted fw-bold">Total Pinjaman</div>
                        <h4 class="fw-bold mb-0 text-dark">{{ $totalRiwayat }} <small class="fs-6 fw-normal">Kali</small></h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-danger border-4">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="bg-danger bg-opacity-10 text-danger p-3 rounded-4 me-3">
                        <i class="bi bi-wallet2 fs-3"></i>
                    </div>
                    <div>
                        <div class="small text-muted fw-bold">Tunggakan Denda</div>
                        {{-- Proteksi denda agar tidak minus --}}
                        <h4 class="fw-bold mb-0 text-danger">Rp {{ number_format(max(0, $totalDenda), 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Koleksi Terbaru</h4>
        </div>
        <a href="{{ route('siswa.katalog') }}" class="btn btn-outline-primary rounded-pill btn-sm px-3">
            Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>

    <div class="row g-4">
        @foreach($bukuTerbaru as $b)
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 hover-elevate">
                <div class="p-2">
                    @if($b->foto)
                        <img src="{{ asset('storage/buku/'.$b->foto) }}" class="card-img-top rounded-4 shadow-sm" style="height: 220px; object-fit: cover;">
                    @else
                        <div class="bg-light rounded-4 d-flex align-items-center justify-content-center" style="height: 220px;">
                            <i class="bi bi-image text-muted fs-1"></i>
                        </div>
                    @endif
                </div>
                <div class="card-body pt-2">
                    <span class="badge bg-primary bg-opacity-10 text-primary mb-2" style="font-size: 10px;">{{ $b->genre }}</span>
                    <h6 class="fw-bold mb-1 text-truncate text-dark">{{ $b->judul }}</h6>
                    <p class="text-muted mb-3" style="font-size: 12px;">{{ $b->penulis }}</p>
                    <a href="{{ route('siswa.katalog') }}" class="btn btn-primary w-100 rounded-pill btn-sm shadow-sm">Pinjam</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection