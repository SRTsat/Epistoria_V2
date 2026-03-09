<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sidebar { height: 100vh; width: 250px; position: fixed; background: #343a40; color: white; padding-top: 20px; }
        .main-content { margin-left: 260px; padding: 20px; }
        .nav-link { color: #ccc; }
        .nav-link:hover, .nav-link.active { color: white; background: #495057; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4 class="text-center">Admin Perpus</h4>
        <hr>
        <nav class="nav flex-column px-3">
            <a class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}" href="{{ url('/admin/dashboard') }}">Dashboard</a>
            <a class="nav-link {{ request()->is('admin/buku*') ? 'active' : '' }}" href="{{ route('buku.index') }}">Kelola Buku</a>
            <a class="nav-link {{ request()->is('admin/anggota*') ? 'active' : '' }}" href="{{ url('/admin/anggota') }}">Kelola Anggota</a>
            <a class="nav-link {{ request()->is('admin/transaksi*') ? 'active' : '' }}" href="{{ url('/admin/transaksi') }}">Data Transaksi</a>
            <hr>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn btn-danger btn-sm w-100">Logout</button>
            </form>
        </nav>
    </div>

    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @yield('content')
    </div>
</body>
</html>