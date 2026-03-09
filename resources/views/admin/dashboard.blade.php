@extends('layouts.admin')

@section('content')
<h2>Selamat Datang, Admin!</h2>
<p>Hari ini kita mau ngelola apa bro?</p>

<div class="row mt-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white p-3">
            <h5>Total Buku</h5>
            <h3>{{ \App\Models\Buku::count() }}</h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white p-3">
            <h5>Total Anggota</h5>
            <h3>{{ \App\Models\User::where('role', 'siswa')->count() }}</h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-warning text-dark p-3">
            <h5>Buku Dipinjam</h5>
            <h3>{{ \App\Models\Peminjaman::where('status', 'dipinjam')->count() }}</h3>
        </div>
    </div>
</div>
@endsection