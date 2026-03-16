@forelse($bukus as $b)
<tr>
    <td>
        @if($b->foto)
            <img src="{{ asset('storage/buku/'.$b->foto) }}" width="50" class="rounded shadow-sm">
        @else
            <span class="text-muted">No Image</span>
        @endif
    </td>
    <td>{{ $b->judul }}</td>
    <td>{{ $b->penulis }}</td>
    <td><span class="badge bg-info text-dark">{{ $b->genre }}</span></td>
    <td>{{ $b->stok }}</td>
    <td>
        <button class="btn btn-warning btn-sm" 
                data-bs-toggle="modal" data-bs-target="#modalEdit"
                data-id="{{ $b->id }}" data-judul="{{ $b->judul }}"
                data-penulis="{{ $b->penulis }}" data-penerbit="{{ $b->penerbit }}"
                data-genre="{{ $b->genre }}" data-stok="{{ $b->stok }}">
            Edit
        </button>
        <form action="{{ route('buku.destroy', $b->id) }}" method="POST" class="d-inline">
            @csrf @method('DELETE')
            <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus buku ini?')">Hapus</button>
        </form>
    </td>
</tr>
@empty
<tr><td colspan="6" class="text-center">Data tidak ditemukan.</td></tr>
@endforelse