@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between mb-3 align-items-center">
    <h3>Data Koleksi Buku</h3>
    <div class="d-flex">
        <div class="input-group me-2">
            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
            <input type="text" id="admin-search" class="form-control" placeholder="Cari judul atau genre...">
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah Buku Baru</button>
    </div>
</div>

<table class="table table-striped table-bordered align-middle shadow-sm">
    <thead class="table-dark">
        <tr>
            <th>Gambar</th>
            <th>Judul</th>
            <th>Penulis</th>
            <th>Genre</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody id="tabel-buku">
        @include('admin.buku._table_buku')
    </tbody>
</table>

@push('scripts')
<script>
    document.getElementById('admin-search').addEventListener('input', function() {
        let keyword = this.value;

        fetch("{{ route('buku.index') }}?search=" + keyword, {
            headers: { "X-Requested-With": "XMLHttpRequest" }
        })
        .then(response => response.text())
        .then(html => {
            document.getElementById('tabel-buku').innerHTML = html;
        })
        .catch(err => console.error('Error:', err));
    });
</script>
@endpush

@include('admin.buku._modal_tambah') 
@endsection