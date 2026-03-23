@forelse($bukus as $b)
<div class="col-md-3">
    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden position-relative" style="transition: 0.3s;">
        <span class="position-absolute top-0 end-0 m-2 badge {{ $b->stok > 0 ? 'bg-success' : 'bg-danger' }} rounded-pill">
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
            <div class="badge bg-primary bg-opacity-10 text-primary mb-2" style="font-size: 10px;">{{ $b->genre ?? 'Umum' }}</div>
            <h6 class="fw-bold mb-1 text-truncate">{{ $b->judul }}</h6>
            <p class="text-muted mb-3" style="font-size: 12px;">{{ $b->penulis }}</p>
            
            @if($b->stok > 0)
                <form action="{{ route('pinjam.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="buku_id" value="{{ $b->id }}">
                    <button type="submit" class="btn btn-primary btn-sm w-100 rounded-pill">Pinjam Sekarang</button>
                </form>
            @else
                <button class="btn btn-light btn-sm w-100 rounded-pill disabled text-muted">Stok Kosong</button>
            @endif
        </div>
    </div>
</div>
@empty
<div class="col-12 text-center py-5">
    <i class="bi bi-emoji-frown fs-1 text-muted opacity-50"></i>
    <p class="mt-2 text-muted">Buku yang lu cari nggak ketemu, bro.</p>
</div>
@endforelse