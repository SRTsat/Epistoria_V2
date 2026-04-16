@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Kelola Genre Buku</h5>
                    <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalTambah">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Genre
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">NAMA GENRE</th>
                                    <th>TOTAL BUKU</th>
                                    <th class="text-end pe-4">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($genres as $g)
                                <tr>
                                    <td class="ps-4 fw-medium">{{ $g->nama }}</td>
                                    <td>
                                        <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill">
                                            {{ $g->bukus_count }} Item
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <button class="btn btn-sm btn-light text-warning rounded-circle shadow-sm" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalEdit"
                                                data-id="{{ $g->id }}" 
                                                data-nama="{{ $g->nama }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        
                                        <form action="{{ route('genre.destroy', $g->id) }}" method="POST" class="d-inline ms-1">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light text-danger rounded-circle shadow-sm" 
                                                    onclick="return confirm('Hapus genre ini?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5 text-muted">Belum ada genre yang dibuat.</td>
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

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('genre.store') }}" method="POST" class="modal-content border-0 shadow rounded-4">
            @csrf
            <div class="modal-header border-0">
                <h5 class="fw-bold">Tambah Genre Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Nama Genre</label>
                    <input type="text" name="nama" class="form-control" placeholder="Contoh: Fantasy, Sci-Fi..." required autofocus>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Genre</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT --}}
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form id="formEdit" method="POST" class="modal-content border-0 shadow rounded-4">
            @csrf @method('PUT')
            <div class="modal-header border-0">
                <h5 class="fw-bold">Edit Genre</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Nama Genre</label>
                    <input type="text" name="nama" id="edit-nama" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-warning text-white rounded-pill px-4">Update Genre</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Logic buat nembak data ke Modal Edit
    const modalEdit = document.getElementById('modalEdit');
    if (modalEdit) {
        modalEdit.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const nama = button.getAttribute('data-nama');
            
            const form = document.getElementById('formEdit');
            form.action = `/admin/genre/${id}`; // Sesuaikan prefix jika pake route group
            document.getElementById('edit-nama').value = nama;
        });
    }
</script>
@endpush