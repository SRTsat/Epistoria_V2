@extends('layouts.admin') {{-- Sesuaikan dengan nama layout admin lu --}}

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-danger mb-0">
                        <i class="bi bi-tools me-2"></i>Daftar Buku Perlu Perbaikan
                    </h5>
                    <span class="badge bg-danger rounded-pill">{{ $rusaks->count() }} Buku</span>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Foto</th>
                                    <th>Info Buku</th>
                                    <th>Peminjam Terakhir</th>
                                    <th>Tanggal Rusak</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rusaks as $index => $r)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if($r->buku->foto)
                                            <img src="{{ asset('storage/buku/' . $r->buku->foto) }}" alt="cover" class="rounded" style="width: 50px; height: 70px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 70px;">
                                                <i class="bi bi-book text-secondary"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $r->buku->judul }}</div>
                                        <small class="text-muted">{{ $r->buku->penulis }}</small>
                                    </td>
                                    <td>{{ $r->user->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($r->tanggal_kembali)->format('d M Y') }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('admin.perbaiki', $r->id) }}" method="POST" onsubmit="return confirm('Yakin buku ini sudah selesai diperbaiki dan siap masuk stok?')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success rounded-pill px-3">
                                                <i class="bi bi-check-circle me-1"></i> Selesai Diperbaiki
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="bi bi-emoji-smile fs-1 d-block mb-2"></i>
                                            Semua buku dalam kondisi prima, bro!
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection