@forelse($bukus as $b)
<div class="col-md-3 mb-4">
    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden position-relative card-hover">
        
        <span class="position-absolute top-0 end-0 m-2 badge {{ $b->stok > 0 ? 'bg-success' : 'bg-danger' }} rounded-pill shadow-sm">
            {{ $b->stok > 0 ? $b->stok . ' Tersedia' : 'Habis' }}
        </span>

        <div class="p-3 bg-light text-center">
            @if($b->foto)
                <img src="{{ asset('storage/buku/'.$b->foto) }}" 
                     class="rounded-3 shadow-sm" 
                     style="height: 180px; width: 130px; object-fit: cover;">
            @else
                <div class="rounded-3 bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center mx-auto" 
                     style="height: 180px; width: 130px;">
                    <i class="bi bi-book fs-1 text-muted opacity-50"></i>
                </div>
            @endif
        </div>

        <div class="card-body">
            {{-- RELASI GENRE --}}
            <div class="badge bg-primary bg-opacity-10 text-primary mb-2" style="font-size: 10px;">
                {{ $b->genre->nama ?? 'Umum' }}
            </div>

            <h6 class="fw-bold mb-1 text-truncate">{{ $b->judul }}</h6>
            <p class="text-muted mb-3 small">{{ $b->penulis }}</p>
            
            @if($b->stok > 0)
                <button class="btn btn-primary btn-sm w-100 rounded-pill fw-bold"
                        data-bs-toggle="modal"
                        data-bs-target="#modalPinjam{{ $b->id }}">
                    Pinjam
                </button>
            @else
                <button class="btn btn-light btn-sm w-100 rounded-pill disabled text-muted">
                    Stok Kosong
                </button>
            @endif
        </div>
    </div>
</div>

{{-- ✅ MODAL (BALIK LAGI) --}}
@if($b->stok > 0)
<div class="modal fade" id="modalPinjam{{ $b->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold">
                    <i class="bi bi-info-circle me-2 text-primary"></i>Konfirmasi Pinjam
                </h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            
            <form action="{{ route('pinjam.store') }}" method="POST">
                @csrf
                <input type="hidden" name="buku_id" value="{{ $b->id }}">
                
                <div class="modal-body py-4">

                    <div class="d-flex align-items-start mb-4 p-3 bg-light rounded-4">
                        @if($b->foto)
                            <img src="{{ asset('storage/buku/'.$b->foto) }}" 
                                 class="rounded-2 shadow-sm me-3" 
                                 style="width: 50px; height: 70px; object-fit: cover;">
                        @endif
                        <div>
                            <h6 class="fw-bold mb-1 text-dark">{{ $b->judul }}</h6>
                            <p class="text-muted small mb-0">{{ $b->penulis }}</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Kelas Kamu</label>
                        <input type="text" name="kelas" 
                               class="form-control border-0 bg-light rounded-3" 
                               placeholder="Contoh: XII RPL 1" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label small fw-bold text-muted">
                            Mau Pinjam Berapa Hari? (Maks 20)
                        </label>
                        <div class="input-group">
                            <input type="number" name="durasi" 
                                   class="form-control border-0 bg-light rounded-start-3" 
                                   min="1" max="20" value="7" required>
                            <span class="input-group-text border-0 bg-light rounded-end-3 text-muted small">
                                Hari
                            </span>
                        </div>
                    </div>

                    <small class="text-info" style="font-size: 11px;">
                        <i class="bi bi-calendar-event me-1"></i>
                        Deadline akan dihitung otomatis sejak hari ini.
                    </small>
                </div>

                <div class="modal-footer border-0 pt-0 px-4 pb-4">
                    <button type="button" 
                            class="btn btn-light rounded-pill px-4 fw-bold text-muted border-0" 
                            data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit" 
                            class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                        Pinjam
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endif

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