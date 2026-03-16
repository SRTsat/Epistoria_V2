@forelse($bukus as $b)
<tr>
    <td>
        @if($b->foto)
            <img src="{{ asset('storage/buku/'.$b->foto) }}" width="50" class="img-thumbnail">
        @else
            <span class="text-muted">No Image</span>
        @endif
    </td>
    <td>{{ $b->judul }}</td>
    <td>{{ $b->penulis }}</td>
    <td><span class="badge bg-info text-dark">{{ $b->genre ?? 'Umum' }}</span></td>
    <td>{{ $b->stok }}</td>
    <td>
        <form action="{{ route('buku.destroy', $b->id) }}" method="POST" class="d-inline">
            @csrf @method('DELETE')
            <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus bro?')">Hapus</button>
        </form>
    </td>
</tr>
@empty
<tr>
    <td colspan="6" class="text-center text-muted">Data buku nggak ketemu, bro!</td>
</tr>
@endforelse