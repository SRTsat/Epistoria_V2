@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-1 text-dark">
                        <i class="bi bi-clock-history text-primary me-2"></i>Riwayat Transaksi
                    </h3>
                    <p class="text-muted small mb-0">Pantau semua aktivitas peminjaman, deadline, dan denda siswa secara real-time.</p>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('transaksi.exportExcel') }}" class="btn btn-outline-success shadow-sm px-4 rounded-pill">
                        <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
                    </a>
                    <a href="{{ route('transaksi.exportPdf') }}" class="btn btn-outline-danger shadow-sm px-4 rounded-pill">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Cetak Laporan PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-4 bg-primary text-white position-relative">
                    <i class="bi bi-journal-check position-absolute end-0 bottom-0 mb-3 me-3 opacity-25" style="font-size: 3rem;"></i>
                    <div class="small opacity-75 fw-bold text-uppercase">Total Transaksi</div>
                    <h2 class="fw-bold mb-0 mt-1">{{ $transaksi->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-4 bg-danger text-white position-relative">
                    <i class="bi bi-cash-stack position-absolute end-0 bottom-0 mb-3 me-3 opacity-25" style="font-size: 3rem;"></i>
                    <div class="small opacity-75 fw-bold text-uppercase">Total Denda Belum Terbayar</div>
                    <h2 class="fw-bold mb-0 mt-1">Rp {{ number_format($totalDenda ?? 0, 0, ',', '.') }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="text-muted small">
                            <th class="border-0 px-4 py-3">PEMINJAM</th>
                            <th class="border-0 py-3">BUKU</th>
                            <th class="border-0 py-3 text-center">DEADLINE</th>
                            <th class="border-0 py-3">TANGGAL KEMBALI</th>
                            <th class="border-0 py-3">DENDA</th>
                            <th class="border-0 py-3 text-center">STATUS</th>
                            <th class="border-0 py-3 text-center px-4">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaksi as $t)
                        <tr>
                            <td class="px-4">
                                <div class="fw-bold text-dark">{{ $t->user->name }}</div>
                                <div class="text-muted small" style="font-size: 11px;">#ID: {{ $t->user->id }}</div>
                            </td>
                            <td>
                                <div class="text-dark fw-medium">{{ $t->buku->judul }}</div>
                            </td>
                            <td class="text-center">
                                <div class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2" style="font-size: 11px;">
                                    {{ \Carbon\Carbon::parse($t->deadline)->format('d M Y') }}
                                </div>
                            </td>
                            <td>
                                @if($t->tanggal_kembali)
                                    <div class="small fw-bold text-dark">{{ \Carbon\Carbon::parse($t->tanggal_kembali)->format('d M Y') }}</div>
                                @else
                                    <span class="text-muted small fst-italic">Masih Dipinjam</span>
                                @endif
                            </td>
                            <td>
                                @if($t->denda > 0)
                                    <span class="badge bg-danger px-3 py-2 rounded-pill shadow-sm">
                                        Rp {{ number_format($t->denda, 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="text-success small fw-bold">
                                        <i class="bi bi-patch-check-fill me-1"></i> Lunas
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($t->status == 'dipinjam')
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-3 py-2 rounded-pill">
                                        <i class="bi bi-clock me-1"></i> Dipinjam
                                    </span>
                                @else
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill">
                                        <i class="bi bi-check-circle me-1"></i> Kembali
                                    </span>
                                @endif
                            </td>
                            <td class="text-center px-4">
                                @if($t->denda > 0)
                                    <form action="{{ route('admin.transaksi.bayar', $t->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success rounded-pill px-3 shadow-sm" 
                                                onclick="return confirm('Siswa ini sudah bayar denda?')">
                                            <i class="bi bi-cash-coin me-1"></i> Confirm Bayar
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-sm btn-light rounded-pill px-3 disabled border-0 text-muted">
                                        <i class="bi bi-check-all me-1"></i> Beres
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Animasi baris tabel */
    tbody tr { animation: fadeIn 0.4s ease-in-out; }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .table-hover tbody tr:hover { background-color: #f8fafc; }
    .btn-success:hover { transform: scale(1.05); }
</style>
@endpush