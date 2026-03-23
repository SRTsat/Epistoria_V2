@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-people-fill text-success me-2"></i>Data Anggota
                    </h3>
                    <p class="text-muted small mb-0">Manajemen data siswa dan akun akses perpustakaan.</p>
                </div>
                <button class="btn btn-success shadow-sm px-4 rounded-pill" data-bs-toggle="modal" data-bs-target="#modalTambah">
                    <i class="bi bi-person-plus-fill me-1"></i> Tambah Anggota
                </button>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="text-muted small">
                            <th width="50" class="border-0 px-4 py-3">NO</th>
                            <th class="border-0 py-3">NAMA LENGKAP</th>
                            <th class="border-0 py-3">USERNAME</th>
                            <th class="border-0 py-3">TANGGAL BERGABUNG</th>
                            <th class="border-0 py-3">STATUS</th>
                            <th width="150" class="text-center border-0 py-3 px-4">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($anggotas as $index => $a)
                        <tr>
                            <td class="px-4 fw-bold text-muted">{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                    <span class="fw-bold text-dark">{{ $a->name }}</span>
                                </div>
                            </td>
                            <td><code class="text-primary fw-bold">{{ $a->username }}</code></td>
                            <td class="text-muted small">{{ $a->created_at->format('d M Y') }}</td>
                            <td><span class="badge bg-success bg-opacity-10 text-success px-3">Siswa Aktif</span></td>
                            <td class="px-4">
                                <div class="d-flex justify-content-center gap-1">
                                    <button class="btn btn-light btn-sm text-warning shadow-sm border" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $a->id }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <form action="{{ route('admin.anggota.destroy', $a->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-light btn-sm text-danger shadow-sm border" onclick="return confirm('Hapus anggota ini?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="modalEdit{{ $a->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <form action="{{ route('admin.anggota.update', $a->id) }}" method="POST" class="modal-content border-0 shadow-lg rounded-4">
                                    @csrf @method('PUT')
                                    <div class="modal-header border-0 pb-0 px-4 pt-4">
                                        <h5 class="fw-bold"><i class="bi bi-pencil-square text-warning me-2"></i>Edit Data Anggota</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold text-muted">Nama Lengkap</label>
                                            <input type="text" name="name" value="{{ $a->name }}" class="form-control rounded-3 bg-light border-0 px-3 py-2" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold text-muted">Username</label>
                                            <input type="text" name="username" value="{{ $a->username }}" class="form-control rounded-3 bg-light border-0 px-3 py-2" required>
                                        </div>
                                        <div class="mb-0">
                                            <label class="form-label small fw-bold text-muted">Password Baru <span class="text-danger" style="font-size: 10px;">(Kosongkan jika tidak ganti)</span></label>
                                            <input type="password" name="password" class="form-control rounded-3 bg-light border-0 px-3 py-2" placeholder="********">
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 p-4 pt-0">
                                        <button type="button" class="btn btn-light px-4 rounded-pill" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-warning px-4 rounded-pill shadow-sm">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.anggota.store') }}" method="POST" class="modal-content border-0 shadow-lg rounded-4">
            @csrf
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <h5 class="fw-bold"><i class="bi bi-person-plus-fill text-success me-2"></i>Tambah Anggota Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Nama Lengkap Siswa</label>
                    <input type="text" name="name" class="form-control rounded-3 bg-light border-0 px-3 py-2" placeholder="Masukkan nama lengkap..." required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Username</label>
                    <input type="text" name="username" class="form-control rounded-3 bg-light border-0 px-3 py-2" placeholder="Contoh: siswa01" required>
                </div>
                <div class="mb-0">
                    <label class="form-label small fw-bold text-muted">Password Default</label>
                    <input type="password" name="password" class="form-control rounded-3 bg-light border-0 px-3 py-2" placeholder="********" required>
                </div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="button" class="btn btn-light px-4 rounded-pill" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success px-4 rounded-pill shadow-sm">Simpan Anggota</button>
            </div>
        </form>
    </div>
</div>

<style>
    /* Styling Tabel & Hover */
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
        transition: 0.2s;
    }
    .badge {
        font-weight: 600;
        letter-spacing: 0.3px;
    }
    /* Button Hover Effect */
    .btn-light.border:hover {
        background-color: white !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.05) !important;
    }
</style>
@endsection