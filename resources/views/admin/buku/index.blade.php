@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h3>Data Koleksi Buku</h3>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah Buku Baru</button>
</div>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Judul</th>
            <th>Penulis</th>
            <th>Penerbit</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($bukus as $b)
        <tr>
            <td>{{ $b->judul }}</td>
            <td>{{ $b->penulis }}</td>
            <td>{{ $b->penerbit }}</td>
            <td>{{ $b->stok }}</td>
            <td>
                <form action="{{ route('buku.destroy', $b->id) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus bro?')">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('buku.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header"><h5>Tambah Buku</h5></div>
            <div class="modal-body">
                <input type="text" name="judul" class="form-control mb-2" placeholder="Judul Buku" required>
                <input type="text" name="penulis" class="form-control mb-2" placeholder="Penulis" required>
                <input type="text" name="penerbit" class="form-control mb-2" placeholder="Penerbit" required>
                <input type="number" name="stok" class="form-control mb-2" placeholder="Jumlah Stok" required>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection