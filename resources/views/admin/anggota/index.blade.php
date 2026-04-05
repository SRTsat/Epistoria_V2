@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-people-fill text-success me-2"></i>Data Pengguna
                    </h3>
                    <p class="text-muted small mb-0">Manajemen data akun admin dan siswa perpustakaan.</p>
                </div>
                <button class="btn btn-success shadow-sm px-4 rounded-pill" data-bs-toggle="modal" data-bs-target="#modalTambah">
                    <i class="bi bi-person-plus-fill me-1"></i> Tambah Akun
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
                            <th class="border-0 py-3">ROLE</th>
                            <th class="border-0 py-3">BERGABUNG</th>
                            <th width="150" class="text-center border-0 py-3 px-4">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($anggotas as $index => $a)
                        <tr>
                            <td class="px-4 fw-bold text-muted">{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="{{ $a->role == 'admin' ? 'bg-primary' : 'bg-success' }} bg-opacity-10 {{ $a->role == 'admin' ? 'text-primary' : 'text-success' }} rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        <i class="bi {{ $a->role == 'admin' ? 'bi-shield-lock-fill' : 'bi-person-fill' }}"></i>
                                    </div>
                                    <span class="fw-bold text-dark">{{ $a->name }}</span>
                                </div>
                            </td>
                            <td><code class="text-primary fw-bold">{{ $a->username }}</code></td>
                            <td>
                                @if($a->role == 'admin')
                                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 rounded-pill">Administrator</span>
                                @else
                                    <span class="badge bg-success bg-opacity-10 text-success px-3 rounded-pill">Siswa</span>
                                @endif
                            </td>
                            <td class="text-muted small">{{ $a->created_at->format('d M Y') }}</td>
                            <td class="px-4">
                                <div class="d-flex justify-content-center gap-1">
                                    <button class="btn btn-light btn-sm text-warning shadow-sm border" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $a->id }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    @if(Auth::id() !== $a->id)
                                    <form action="{{ route('admin.anggota.destroy', $a->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-light btn-sm text-danger shadow-sm border" onclick="return confirm('Hapus akun ini?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@foreach($anggotas as $a)
    <div class="modal fade" id="modalEdit{{ $a->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('admin.anggota.update', $a->id) }}" method="POST" class="modal-content border-0 shadow-lg rounded-4">
                @csrf @method('PUT')
                <div class="modal-header border-0 pb-0 px-4 pt-4">
                    <h5 class="fw-bold"><i class="bi bi-pencil-square text-warning me-2"></i>Edit Akun</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 text-start">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ $a->name }}" class="form-control bg-light border-0 py-2 px-3 rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Username</label>
                        <input type="text" name="username" value="{{ $a->username }}" class="form-control bg-light border-0 py-2 px-3 rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Role</label>
                        <select name="role" class="form-select bg-light border-0 py-2 px-3 rounded-3" required>
                            <option value="siswa" {{ $a->role == 'siswa' ? 'selected' : '' }}>Siswa</option>
                            <option value="admin" {{ $a->role == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-bold text-muted text-uppercase">Password <span class="text-danger" style="font-size: 10px;">(Kosongkan jika tidak ganti)</span></label>
                        <input type="password" name="password" class="form-control bg-light border-0 py-2 px-3 rounded-3" placeholder="********">
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning rounded-pill px-4 shadow-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endforeach

<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.anggota.store') }}" method="POST" class="modal-content border-0 shadow-lg rounded-4">
            @csrf
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <h5 class="fw-bold"><i class="bi bi-person-plus-fill text-success me-2"></i>Tambah Akun</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-start">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted text-uppercase">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control bg-light border-0 py-2 px-3 rounded-3" placeholder="..." required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted text-uppercase">Username</label>
                    <input type="text" name="username" class="form-control bg-light border-0 py-2 px-3 rounded-3" placeholder="..." required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted text-uppercase">Role</label>
                    <select name="role" class="form-select bg-light border-0 py-2 px-3 rounded-3" required>
                        <option value="siswa" selected>Siswa</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="mb-0">
                    <label class="form-label small fw-bold text-muted text-uppercase">Password</label>
                    <input type="password" name="password" class="form-control bg-light border-0 py-2 px-3 rounded-3" placeholder="********" required>
                </div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>


<style>
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
        transition: 0.2s;
    }
    .badge {
        font-weight: 600;
        letter-spacing: 0.3px;
    }
    .btn-light.border:hover {
        background-color: white !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.05) !important;
    }
    /* Pastikan input focus nggak berantakan */
    .form-control:focus, .form-select:focus {
        background-color: #fff !important;
        box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.1);
        border: 1px solid #198754 !important;
    }
</style>
@endsection