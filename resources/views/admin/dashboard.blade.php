@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Dashboard Admin</h3>
            <p class="text-muted">Selamat datang kembali, <strong>{{ Auth::user()->name }}</strong>. Berikut ringkasan perpustakaan hari ini.</p>
        </div>
        <span class="badge bg-white text-dark shadow-sm p-2 px-3 rounded-pill">
            <i class="bi bi-calendar3 me-2 text-primary"></i> {{ date('d M Y') }}
        </span>
    </div>
    
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm overflow-hidden" style="background: linear-gradient(45deg, #4e73df, #224abe);">
                <div class="card-body p-4 text-white">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-white-50 small fw-bold text-uppercase mb-1">Total Koleksi</div>
                            <h2 class="fw-bold mb-0">{{ $total_buku }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-book fs-3"></i>
                        </div>
                    </div>
                    <div class="mt-3 small text-white-50">
                        <i class="bi bi-arrow-right-short"></i> <a href="{{ route('buku.index') }}" class="text-white-50 text-decoration-none">Lihat Detail</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm overflow-hidden" style="background: linear-gradient(45deg, #1cc88a, #13855c);">
                <div class="card-body p-4 text-white">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-white-50 small fw-bold text-uppercase mb-1">Anggota Aktif</div>
                            <h2 class="fw-bold mb-0">{{ $total_siswa }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-people fs-3"></i>
                        </div>
                    </div>
                    <div class="mt-3 small text-white-50">
                        <i class="bi bi-arrow-right-short"></i> <a href="{{ url('/admin/anggota') }}" class="text-white-50 text-decoration-none">Kelola Anggota</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm overflow-hidden" style="background: linear-gradient(45deg, #f6c23e, #dda20a);">
                <div class="card-body p-4 text-white">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-white-50 small fw-bold text-uppercase mb-1">Sedang Dipinjam</div>
                            <h2 class="fw-bold mb-0">{{ $total_pinjam }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-clock-history fs-3"></i>
                        </div>
                    </div>
                    <div class="mt-3 small text-white-50">
                        <i class="bi bi-arrow-right-short"></i> <a href="{{ url('/admin/transaksi') }}" class="text-white-50 text-decoration-none">Cek Transaksi</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm overflow-hidden" style="background: linear-gradient(45deg, #e74a3b, #be2617);">
                <div class="card-body p-4 text-white">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-white-50 small fw-bold text-uppercase mb-1">Kas Denda</div>
                            <h4 class="fw-bold mb-0">Rp {{ number_format($total_denda, 0, ',', '.') }}</h4>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-cash-coin fs-3"></i>
                        </div>
                    </div>
                    <div class="mt-3 small text-white-50">
                        <i class="bi bi-info-circle me-1"></i> Akumulasi keterlambatan
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
    <div class="col-lg-12">
        <div class="card border-0 shadow-sm p-3">
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-warning bg-opacity-10 p-2 rounded-3 me-2">
                        <i class="bi bi-lightning-charge-fill text-warning"></i>
                    </div>
                    <h5 class="fw-bold mb-0">Akses Cepat</h5>
                </div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <a href="{{ route('buku.index') }}" class="btn btn-outline-primary w-100 py-3 rounded-4 shadow-sm hover-elevate text-decoration-none text-start px-4">
                            <i class="bi bi-book-half fs-3 d-block mb-2"></i> 
                            <span class="small fw-bold">Kelola Buku</span>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ url('/admin/anggota') }}" class="btn btn-outline-success w-100 py-3 rounded-4 shadow-sm hover-elevate text-decoration-none text-start px-4">
                            <i class="bi bi-people-fill fs-3 d-block mb-2"></i> 
                            <span class="small fw-bold">Kelola Siswa</span>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('transaksi.exportPdf') }}" class="btn btn-outline-danger w-100 py-3 rounded-4 shadow-sm hover-elevate text-decoration-none text-start px-4">
                            <i class="bi bi-printer-fill fs-3 d-block mb-2"></i> 
                            <span class="small fw-bold">Cetak Laporan</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h5 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>Aktivitas Terbaru</h5>
                    <a href="{{ url('/admin/transaksi') }}" class="btn btn-sm btn-light rounded-pill px-3">Semua</a>
                </div>

                <div class="timeline-container">
                    @forelse($recent_activities as $act)
                    <div class="d-flex mb-3">
                        <div class="me-3">
                            <span class="badge rounded-pill bg-{{ $act->status == 'dipinjam' ? 'warning' : 'success' }} p-2">
                                <i class="bi bi-{{ $act->status == 'dipinjam' ? 'arrow-up-right' : 'arrow-down-left' }}"></i>
                            </span>
                        </div>
                        <div class="border-bottom pb-2 w-100">
                            <p class="mb-0 small"><strong>{{ $act->user->name }}</strong> {{ $act->status == 'dipinjam' ? 'meminjam' : 'mengembalikan' }} <strong>{{ $act->buku->judul }}</strong></p>
                            <small class="text-muted">{{ $act->updated_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-muted py-4">Belum ada transaksi.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h5 class="fw-bold mb-4"><i class="bi bi-fire me-2 text-danger"></i>Buku Terpopuler</h5>
                
                @forelse($populers as $index => $buku)
                <div class="d-flex align-items-center mb-3 p-2 bg-light rounded-3">
                    <div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 35px; height: 35px; font-size: 14px;">
                        {{ $index + 1 }}
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0 fw-bold small text-truncate" style="max-width: 150px;">{{ $buku->judul }}</h6>
                    </div>
                    <div class="ms-auto">
                        <span class="badge bg-white text-primary border border-primary-subtle">Rank #{{ $index + 1 }}</span>
                    </div>
                </div>
                @empty
                <p class="text-center text-muted py-4">Belum ada data populer.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
</div>

<style>
    /* Efek melayang dan warna hover custom */
    .hover-elevate {
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        border-width: 1px !important; /* Biar border gak ketebelan */
    }

    /* Reset kelakuan default Bootstrap outline btn saat hover */
    .hover-elevate:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.08) !important;
    }

    /* Fix tombol Biru (Kelola Buku) */
    .btn-outline-primary.hover-elevate:hover {
        background-color: rgba(13, 110, 253, 0.05) !important; /* Biru sangat transparan */
        color: #0d6efd !important; /* Teks tetep biru */
        border-color: #0d6efd !important;
    }

    /* Fix tombol Hijau (Kelola Siswa) */
    .btn-outline-success.hover-elevate:hover {
        background-color: rgba(25, 135, 84, 0.05) !important; /* Hijau sangat transparan */
        color: #198754 !important; /* Teks tetep hijau */
        border-color: #198754 !important;
    }

    /* Fix tombol Merah (Cetak Laporan) */
    .btn-outline-danger.hover-elevate:hover {
        background-color: rgba(220, 53, 69, 0.05) !important; /* Merah sangat transparan */
        color: #dc3545 !important; /* Teks tetep merah */
        border-color: #dc3545 !important;
    }

    /* Pastikan icon tidak berubah warna saat hover */
    .hover-elevate:hover i {
        opacity: 1 !important;
    }
</style>
</div>
@endsection