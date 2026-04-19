@extends('layouts.siswa')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Koleksi Pinjaman</h2>
            <p class="text-muted small mb-0">Kelola dan pantau batas waktu pengembalian buku kamu.</p>
        </div>
        <a href="{{ route('siswa.katalog') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Pinjam Lagi
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-3">
                <div class="d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 text-warning p-3 rounded-4 me-3">
                        <i class="bi bi-book-half fs-3"></i>
                    </div>
                    <div>
                        <small class="text-muted fw-bold d-block">Sedang Kamu Baca</small>
                        <h4 class="fw-bold mb-0 text-dark">
                            {{ $pinjaman->whereIn('status', ['dipinjam', 'proses_kembali'])->count() }} Buku
                        </h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-3 border-start border-danger border-4">
                <div class="d-flex align-items-center">
                    <div class="bg-danger bg-opacity-10 text-danger p-3 rounded-4 me-3">
                        <i class="bi bi-exclamation-triangle fs-3"></i>
                    </div>
                    <div>
                        <small class="text-muted fw-bold d-block">Tunggakan Denda</small>
                        <h4 class="fw-bold mb-0 text-danger">Rp {{ number_format($totalDendaLive, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>Riwayat & Status</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0 ps-4">Info Buku</th>
                        <th class="border-0">Waktu Pinjam</th>
                        <th class="border-0">Batas Kembali</th>
                        <th class="border-0">Status / Denda</th>
                        <th class="border-0 text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pinjaman as $p)
                        @php
                            $isOverdue = \Carbon\Carbon::now()->startOfDay()->gt(\Carbon\Carbon::parse($p->deadline)->startOfDay()) 
                                         && in_array($p->status, ['dipinjam', 'proses_kembali']);
                            
                            // Hitung denda telat live untuk tampilan (asumsi 1000/hari)
                            $denda_telat_live = 0;
                            if($isOverdue) {
                                $hari = \Carbon\Carbon::now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($p->deadline)->startOfDay());
                                $denda_telat_live = $hari * 1000;
                            }
                        @endphp
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        @if($p->buku->foto)
                                            <img src="{{ asset('storage/buku/'.$p->buku->foto) }}" class="rounded-2 shadow-sm" style="width: 45px; height: 65px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded-2 d-flex align-items-center justify-content-center" style="width: 45px; height: 65px;">
                                                <i class="bi bi-book text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $p->buku->judul }}</div>
                                        <small class="text-muted">{{ $p->buku->penulis }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="small fw-bold text-dark">
                                    {{ $p->tanggal_pinjam ? \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M Y') : '-' }}
                                </div>
                            </td>
                            <td>
                                <div class="small fw-bold {{ $isOverdue ? 'text-danger' : 'text-dark' }}">
                                    {{ $p->deadline ? \Carbon\Carbon::parse($p->deadline)->format('d M Y') : '-' }}
                                </div>
                                @if($isOverdue)
                                    <span class="badge bg-danger bg-opacity-10 text-danger mt-1" style="font-size: 10px;">Terlambat!</span>
                                @endif
                            </td>
                            <td>
                                {{-- Status Badges --}}
                                @if($p->status == 'menunggu')
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill">Menunggu ACC</span>
                                @elseif($p->status == 'dipinjam')
                                    <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">Sedang Dipinjam</span>
                                @elseif($p->status == 'proses_kembali')
                                    <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill">Proses Balik</span>
                                @elseif($p->status == 'rusak')
                                    <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">Buku Rusak</span>
                                @elseif($p->status == 'hilang')
                                    <span class="badge bg-dark bg-opacity-10 text-dark px-3 py-2 rounded-pill">Buku Hilang</span>
                                @else
                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Sudah Kembali</span>
                                @endif

                                {{-- INFO DENDA DENGAN PENJELASAN --}}
                                @php
                                    $current_denda = in_array($p->status, ['dipinjam', 'proses_kembali']) ? $denda_telat_live : $p->denda;
                                @endphp

                                @if($current_denda > 0)
                                    <div class="mt-1">
                                        <span class="badge bg-danger rounded-pill px-2 py-1 shadow-sm cursor-pointer" 
                                              data-bs-toggle="popover" 
                                              data-bs-trigger="hover focus"
                                              title="Rincian Denda" 
                                              data-bs-content="Denda ini mencakup keterlambatan dan kondisi fisik buku yang dicek oleh admin.">
                                            Rp {{ number_format($current_denda, 0, ',', '.') }} <i class="bi bi-info-circle ms-1"></i>
                                        </span>
                                    </div>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                @if($p->status == 'dipinjam')
                                    <form action="{{ route('pinjam.kembali', $p->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-success btn-sm px-4 rounded-pill shadow-sm fw-bold border-0" 
                                                onclick="return confirm('Yakin ingin melaporkan pengembalian buku?')">
                                            Kembalikan
                                        </button>
                                    </form>
                                @elseif($p->status == 'proses_kembali')
                                    <div class="d-flex flex-column align-items-center">
                                        <span class="text-info small fw-bold">Serahkan Buku</span>
                                        <small class="text-muted" style="font-size: 10px;">Temui Pustakawan</small>
                                    </div>
                                @else
                                    <i class="bi bi-check2-all text-success fs-5"></i>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-emoji-smile d-block fs-1 opacity-25 mb-2"></i>
                                Belum ada riwayat pinjaman.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Aktifkan Popover Bootstrap biar siswa bisa hover liat info denda
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl)
    })
</script>
@endpush