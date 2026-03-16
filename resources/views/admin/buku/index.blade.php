@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between mb-3 align-items-center">
    <h3>Data Koleksi Buku</h3>
    <div class="d-flex gap-2">
        <select id="admin-filter-genre" class="form-select" style="width: 180px;">
            <option value="">Semua Genre</option>
            <option value="Fiksi">Fiksi</option>
            <option value="Non-Fiksi">Non-Fiksi</option>
            <option value="Novel">Novel</option>
            <option value="Edukasi">Edukasi</option>
            <option value="Teknologi">Teknologi</option>
        </select>
        <input type="text" id="admin-live-search" class="form-control" placeholder="Cari judul/penulis..." style="width: 250px;">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah Buku</button>
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

@include('admin.buku._modal_tambah')
@include('admin.buku._modal_edit')

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('admin-live-search');
        const genreSelect = document.getElementById('admin-filter-genre');
        const tableBody = document.getElementById('tabel-buku');

        // 1. Fungsi Live Search & Filter
        function fetchData() {
            let kw = searchInput.value;
            let gn = genreSelect.value;
            fetch(`{{ route('buku.index') }}?search=${encodeURIComponent(kw)}&genre=${encodeURIComponent(gn)}`, {
                headers: { "X-Requested-With": "XMLHttpRequest" }
            })
            .then(res => res.text())
            .then(html => tableBody.innerHTML = html);
        }
        searchInput.addEventListener('input', fetchData);
        genreSelect.addEventListener('change', fetchData);

        // 2. Logika Modal Edit (Passing data ke form)
        const modalEdit = document.getElementById('modalEdit');
        modalEdit.addEventListener('show.bs.modal', function (event) {
            const btn = event.relatedTarget;
            const form = document.getElementById('formEdit');
            
            form.action = `/admin/buku/${btn.getAttribute('data-id')}`;
            document.getElementById('edit-judul').value = btn.getAttribute('data-judul');
            document.getElementById('edit-penulis').value = btn.getAttribute('data-penulis');
            document.getElementById('edit-penerbit').value = btn.getAttribute('data-penerbit');
            document.getElementById('edit-genre').value = btn.getAttribute('data-genre');
            document.getElementById('edit-stok').value = btn.getAttribute('data-stok');
        });
    });
</script>
@endpush
@endsection