@extends('layouts.admin')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold">Riwayat Transaksi Peminjaman</h3>
    <p class="text-muted small">Pantau semua aktivitas peminjaman, deadline, dan denda siswa.</p>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-primary text-white">
            <div class="card-body">
                <div class="small opacity-75">Total Transaksi</div>
                <h3 class="fw-bold mb-0">{{ $transaksi->count() }}</h3>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-danger text-white">
            <div class="card-body">
                <div class="small opacity-75">Total Pendapatan Denda</div>
                <h3 class="fw-bold mb-0">Rp {{ number_format($totalDenda ?? 0, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="mb-4 d-flex justify-content-between align-items-center">
    <h3 class="fw-bold">Riwayat Transaksi Peminjaman</h3>
    <a href="{{ route('transaksi.exportPdf') }}" class="btn btn-danger shadow-sm">
        <i class="bi bi-file-earmark-pdf"></i> Export Laporan PDF
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Peminjam</th>
                        <th>Judul Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Batas (Deadline)</th>
                        <th>Tgl Kembali</th>
                        <th>Denda</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksi as $t)
                    <tr>
                        <td>
                            <div class="fw-bold">{{ $t->user->name }}</div>
                            <div class="text-muted small">ID: #{{ $t->user->id }}</div>
                        </td>
                        <td>{{ $t->buku->judul }}</td>
                        <td>{{ \Carbon\Carbon::parse($t->tanggal_pinjam)->format('d/m/Y') }}</td>
                        <td>
                            <span class="text-danger fw-bold">
                                {{ \Carbon\Carbon::parse($t->deadline)->format('d/m/Y') }}
                            </span>
                        </td>
                        <td>
                            {{ $t->tanggal_kembali ? \Carbon\Carbon::parse($t->tanggal_kembali)->format('d/m/Y') : '-' }}
                        </td>
                        <td>
                            @if($t->denda > 0)
                                <span class="badge bg-danger">Rp {{ number_format($t->denda, 0, ',', '.') }}</span>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td>
                            @if($t->status == 'dipinjam')
                                <span class="badge bg-warning text-dark px-3 rounded-pill">Dipinjam</span>
                            @else
                                <span class="badge bg-success px-3 rounded-pill">Selesai</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection