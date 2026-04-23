@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Dashboard</h3>
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
                                @php
                                    $color = 'secondary';
                                    $icon = 'clock-history';
                                    $text_action = 'melakukan transaksi';

                                    if($act->status == 'menunggu') {
                                        $color = 'info'; $icon = 'person-plus'; $text_action = 'mengajukan pinjaman';
                                    } elseif($act->status == 'dipinjam') {
                                        $color = 'warning'; $icon = 'box-arrow-up'; $text_action = 'meminjam';
                                    } elseif($act->status == 'proses_kembali') {
                                        $color = 'primary'; $icon = 'arrow-repeat'; $text_action = 'ingin mengembalikan';
                                    } elseif($act->status == 'dikembalikan') {
                                        $color = 'success'; $icon = 'check2-circle'; $text_action = 'telah mengembalikan';
                                    }
                                @endphp
                                <span class="badge rounded-pill bg-{{ $color }} p-2">
                                    <i class="bi bi-{{ $icon }}"></i>
                                </span>
                            </div>
                            <div class="border-bottom pb-2 w-100">
                                <p class="mb-0 small">
                                    <strong>{{ $act->user->name }}</strong> 
                                    {{ $text_action }} 
                                    <strong>{{ $act->buku->judul }}</strong>
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">{{ $act->updated_at->diffForHumans() }}</small>
                                    <span class="badge bg-light text-{{ $color }} border border-{{ $color }} rounded-pill p-1 px-2" style="font-size: 9px;">
                                        {{ strtoupper($act->status) }}
                                    </span>
                                </div>
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
                    <h5 class="fw-bold mb-4"><i class="bi bi-bar-chart-line-fill me-2 text-danger"></i>Buku Terpopuler</h5>
                    
                    @if(count($populers) > 0)
                    <div style="position: relative; height:320px; width:100%">
                        <canvas id="chartPopuler"></canvas>
                    </div>
                    @else
                    <p class="text-center text-muted py-4">Belum ada data populer.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-elevate {
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        border-width: 1px !important;
    }
    .hover-elevate:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.08) !important;
    }
    .btn-outline-primary.hover-elevate:hover {
        background-color: rgba(13, 110, 253, 0.05) !important;
        color: #0d6efd !important;
        border-color: #0d6efd !important;
    }
    .btn-outline-success.hover-elevate:hover {
        background-color: rgba(25, 135, 84, 0.05) !important;
        color: #198754 !important;
        border-color: #198754 !important;
    }
    .btn-outline-danger.hover-elevate:hover {
        background-color: rgba(220, 53, 69, 0.05) !important;
        color: #dc3545 !important;
        border-color: #dc3545 !important;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('chartPopuler');
        if (ctx) {
            const labels = JSON.parse('@json($chartLabels)');
            const dataCounts = JSON.parse('@json($chartData)');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Kali Dipinjam',
                        data: dataCounts,
                        backgroundColor: 'rgba(220, 53, 69, 0.7)',
                        borderColor: 'rgba(220, 53, 69, 1)',
                        borderWidth: 1,
                        borderRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y', // Horizontal
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: { beginAtZero: true, ticks: { stepSize: 1 } },
                        y: { 
                            ticks: {
                                callback: function(value, index) {
                                    const label = this.getLabelForValue(index);
                                    return label.length > 15 ? label.substr(0, 15) + '...' : label;
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection