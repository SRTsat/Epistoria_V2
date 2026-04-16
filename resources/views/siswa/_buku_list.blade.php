@forelse($bukus as $b)
<div class="col-md-3 mb-4">
    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden position-relative card-hover">
        <span class="position-absolute top-0 end-0 m-2 badge {{ $b->stok > 0 ? 'bg-success' : 'bg-danger' }} rounded-pill shadow-sm">
            {{ $b->stok > 0 ? $b->stok . ' Tersedia' : 'Habis' }}
        </span>

        <div class="p-3 bg-light text-center">
            @if($b->foto)
                <img src="{{ asset('storage/buku/'.$b->foto) }}" class="rounded-3 shadow-sm" style="height: 180px; width: 130px; object-fit: cover;">
            @else
                <div class="rounded-3 bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center mx-auto" style="height: 180px; width: 130px;">
                    <i class="bi bi-book fs-1 text-muted opacity-50"></i>
                </div>
            @endif
        </div>

        <div class="card-body">
            {{-- RELASI GENRE DISINI --}}
            <div class="badge bg-primary bg-opacity-10 text-primary mb-2" style="font-size: 10px;">
                {{ $b->genre->nama ?? 'Umum' }}
            </div>
            <h6 class="fw-bold mb-1 text-truncate">{{ $b->judul }}</h6>
            <p class="text-muted mb-3 small">{{ $b->penulis }}</p>
            
            @if($b->stok > 0)
                <button class="btn btn-primary btn-sm w-100 rounded-pill fw-bold" data-bs-toggle="modal" data-bs-target="#modalPinjam{{ $b->id }}">Pinjam</button>
            @else
                <button class="btn btn-light btn-sm w-100 rounded-pill disabled text-muted">Stok Kosong</button>
            @endif
        </div>
    </div>
</div>
@empty
<div class="col-12 text-center py-5">
    <p class="text-muted">Buku nggak ketemu, bro.</p>
</div>
@endforelse

@push('styles')
<style>
    .card-hover:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }
    .form-control:focus {
        background-color: #ffffff !important;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1) !important;
    }
</style>
@endpush