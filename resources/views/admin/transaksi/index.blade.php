@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    
    {{-- 1. HEADER & TOMBOL EXPORT --}}
    <div class="card shadow-sm border-0 rounded-4 mb-3">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h3 class="fw-bold mb-1 text-dark">
                        <i class="bi bi-clock-history text-primary me-2"></i>Riwayat Transaksi
                    </h3>
                    <p class="text-muted small mb-0">Data transaksi perpustakaan tahun <strong>{{ $tahun }}</strong>.</p>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('transaksi.exportExcel', ['tahun' => $tahun]) }}" 
                       class="btn btn-outline-success shadow-sm px-4 rounded-pill fw-bold">
                        <i class="bi bi-file-earmark-excel me-1"></i> Excel {{ $tahun }}
                    </a>
                    
                    <a href="{{ route('transaksi.exportPdf', ['tahun' => $tahun]) }}" 
                       class="btn btn-outline-danger shadow-sm px-4 rounded-pill fw-bold">
                        <i class="bi bi-file-earmark-pdf me-1"></i> PDF {{ $tahun }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. FILTER TAHUN --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body py-3">
            <form action="{{ route('admin.transaksi') }}" method="GET" class="row g-3 align-items-center">
                <div class="col-auto">
                    <span class="text-muted small fw-bold"><i class="bi bi-funnel me-1"></i> Filter Tahun:</span>
                </div>
                <div class="col-auto">
                    <select name="tahun" class="form-select form-select-sm rounded-pill px-3 border-primary">
                        <option value="semua" {{ $tahun == 'semua' ? 'selected' : '' }}>-- Semua Tahun --</option>
                        
                        @foreach($list_tahun as $lt)
                            <option value="{{ $lt->tahun }}" {{ $tahun == $lt->tahun ? 'selected' : '' }}>
                                Tahun {{ $lt->tahun }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-primary rounded-pill px-4 shadow-sm">
                        Tampilkan
                    </button>
                    <a href="{{ route('admin.transaksi') }}" class="btn btn-sm btn-light rounded-pill px-3 text-muted">Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- 3. STATISTIK RINGKASAN --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-4 bg-primary text-white position-relative">
                    <i class="bi bi-journal-check position-absolute end-0 bottom-0 mb-3 me-3 opacity-25" style="font-size: 3rem;"></i>
                    <div class="small opacity-75 fw-bold text-uppercase">Total Transaksi ({{ $tahun }})</div>
                    <h2 class="fw-bold mb-0 mt-1">{{ $transaksi->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-4 bg-danger text-white position-relative">
                    <i class="bi bi-cash-stack position-absolute end-0 bottom-0 mb-3 me-3 opacity-25" style="font-size: 3rem;"></i>
                    <div class="small opacity-75 fw-bold text-uppercase">Total Denda Terdeteksi</div>
                    <h2 class="fw-bold mb-0 mt-1">Rp {{ number_format($totalDenda ?? 0, 0, ',', '.') }}</h2>
                </div>
            </div>
        </div>
    </div>

    {{-- 4. TABEL DATA --}}
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="text-muted small">
                            <th class="border-0 px-4 py-3">PEMINJAM</th>
                            <th class="border-0 py-3">BUKU</th>
                            <th class="border-0 py-3 text-center">DEADLINE</th>
                            <th class="border-0 py-3 text-center">TGL KEMBALI</th>
                            <th class="border-0 py-3 text-center">DENDA</th>
                            <th class="border-0 py-3 text-center">STATUS</th>
                            <th class="border-0 py-3 text-center px-4">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksi as $t)
                        <tr>
                            <td class="px-4">
                                <div class="fw-bold text-dark">{{ $t->user->name }}</div>
                                <div class="text-muted small" style="font-size: 11px;">ID: #{{ $t->user->id }}</div>
                            </td>
                            <td>
                                <div class="text-dark fw-medium">{{ $t->buku->judul }}</div>
                                <small class="text-muted">{{ $t->buku->penulis }}</small>
                            </td>
                            <td class="text-center">
                                <div class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2" style="font-size: 11px;">
                                    {{ \Carbon\Carbon::parse($t->deadline)->format('d M Y') }}
                                </div>
                            </td>
                            <td class="text-center">
                                @if($t->tanggal_kembali)
                                    <div class="small fw-bold text-dark">{{ \Carbon\Carbon::parse($t->tanggal_kembali)->format('d M Y') }}</div>
                                @else
                                    <span class="text-muted small fst-italic">Belum Kembali</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @php
                                    $nilaiDenda = in_array($t->status, ['dipinjam', 'proses_kembali']) ? ($t->denda_saat_ini ?? 0) : $t->denda;
                                @endphp

                                @if($nilaiDenda > 0)
                                    <span class="badge bg-danger px-3 py-2 rounded-pill shadow-sm">
                                        Rp {{ number_format($nilaiDenda, 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="text-success small fw-bold">
                                        <i class="bi bi-patch-check-fill me-1"></i> Lunas
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($t->status == 'menunggu')
                                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-3 py-2 rounded-pill">
                                        Menunggu ACC
                                    </span>
                                @elseif($t->status == 'dipinjam')
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-3 py-2 rounded-pill">
                                        Dipinjam
                                    </span>
                                @elseif($t->status == 'proses_kembali')
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2 rounded-pill">
                                        Proses Balik
                                    </span>
                                @else
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill">
                                        Selesai
                                    </span>
                                @endif
                            </td>
                            <td class="text-center px-4">
                                @if($t->status == 'menunggu')
                                    <form action="{{ route('admin.transaksi.approve', $t->id) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-sm btn-warning text-white rounded-pill px-3">Setujui</button>
                                    </form>
                                @elseif($t->status == 'proses_kembali')
                                    <form action="{{ route('admin.transaksi.kembali', $t->id) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-sm btn-primary rounded-pill px-3">Terima Buku</button>
                                    </form>
                                @elseif($t->status == 'dikembalikan' && $t->denda > 0)
                                    <form action="{{ route('admin.transaksi.bayar', $t->id) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-sm btn-success rounded-pill px-3">Bayar Denda</button>
                                    </form>
                                @else
                                    <i class="bi bi-check2-all text-success fs-5"></i>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-folder-x fs-1 opacity-25 d-block mb-3"></i>
                                <span class="text-muted">Tidak ada data transaksi untuk tahun {{ $tahun }}.</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    tbody tr { animation: fadeIn 0.4s ease-in-out; }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .table-hover tbody tr:hover { background-color: #f8fafc; }
    .btn:hover { transform: translateY(-1px); transition: 0.2s; }
</style>
@endpush