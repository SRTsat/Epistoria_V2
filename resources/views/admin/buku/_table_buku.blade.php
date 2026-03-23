@forelse($bukus as $b)
<tr class="hover-light">
    <td>
        @if($b->foto)
            <img src="{{ asset('storage/buku/'.$b->foto) }}" width="60" class="rounded-3 shadow-sm border">
        @else
            <div class="bg-light rounded-3 d-flex align-items-center justify-content-center border shadow-sm" style="width: 60px; height: 80px;">
                <i class="bi bi-image text-muted"></i>
            </div>
        @endif
    </td>
    <td>
        <div class="fw-bold text-dark">{{ $b->judul }}</div>
        <div class="text-muted small"><i class="bi bi-person me-1"></i>{{ $b->penulis }}</div>
        <div class="text-muted small" style="font-size: 10px; opacity: 0.7;">Penerbit: {{ $b->penerbit }}</div>
    </td>
    <td>
        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">{{ $b->genre }}</span>
    </td>
    <td>
        @if($b->stok <= 5)
            <span class="text-danger fw-bold"><i class="bi bi-exclamation-triangle me-1"></i>{{ $b->stok }}</span>
        @else
            <span class="fw-bold">{{ $b->stok }}</span>
        @endif
    </td>
    <td>
        <div class="d-flex justify-content-center gap-1">
            <button class="btn btn-light btn-sm text-warning shadow-sm border" 
                data-bs-toggle="modal" data-bs-target="#modalEdit"
                data-id="{{ $b->id }}" data-judul="{{ $b->judul }}"
                data-penulis="{{ $b->penulis }}" data-penerbit="{{ $b->penerbit }}"
                data-genre="{{ $b->genre }}" data-stok="{{ $b->stok }}">
                <i class="bi bi-pencil-square"></i>
            </button>
            <form action="{{ route('buku.destroy', $b->id) }}" method="POST" class="d-inline">
                @csrf @method('DELETE')
                <button class="btn btn-light btn-sm text-danger shadow-sm border" onclick="return confirm('Hapus buku ini?')">
                    <i class="bi bi-trash"></i>
                </button>
            </form>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="5" class="text-center py-5">
        <img src="https://illustrations.popsy.co/gray/search.svg" width="150" class="mb-3">
        <p class="text-muted">Oops! Buku tidak ditemukan atau data masih kosong.</p>
    </td>
</tr>
@endforelse