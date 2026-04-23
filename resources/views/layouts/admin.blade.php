<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Perpustakaan</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    @stack('styles')

    <style>
        /* Google Fonts buat kesan modern */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body { 
            background-color: #f0f2f5; 
            font-family: 'Inter', sans-serif;
            color: #334155;
        }

        /* Sidebar dengan Gradasi & Blur */
        .sidebar { 
            height: 100vh; 
            width: 260px; 
            position: fixed; 
            background: #1e293b; 
            color: white; 
            padding-top: 25px; 
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .main-content { 
            margin-left: 260px; 
            padding: 40px; 
            transition: all 0.3s ease;
        }

        /* Nav Link yang Lebih Interaktif */
        .nav-link { 
            color: #94a3b8; 
            padding: 12px 18px;
            border-radius: 8px;
            margin: 4px 15px;
            font-weight: 500;
            display: flex;
            align-items: center;
            transition: all 0.2s;
        }

        .nav-link i { font-size: 1.1rem; }

        .nav-link:hover { 
            color: #f8fafc; 
            background: rgba(255, 255, 255, 0.05);
            transform: translateX(5px);
        }

        .nav-link.active {
            background: linear-gradient(45deg, #2563eb, #3b82f6);
            color: white !important;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        /* Card Styling untuk konten */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .alert {
            border-radius: 12px;
        }

        /* Custom Scrollbar biar estetik */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4 class="text-center fw-bold">Admin Perpus</h4>
        <p class="text-center text-muted small">Panel Kendali</p>
        <hr>
        <nav class="nav flex-column px-3">
            <a class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}" href="{{ url('/admin/dashboard') }}">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
            <a class="nav-link {{ request()->is('admin/buku*') ? 'active' : '' }}" href="{{ route('buku.index') }}">
                <i class="bi bi-book me-2"></i> Kelola Buku
            </a>
            <a class="nav-link {{ request()->is('admin/anggota*') ? 'active' : '' }}" href="{{ url('/admin/anggota') }}">
                <i class="bi bi-people me-2"></i> Kelola Anggota
            </a>
            <a class="nav-link {{ request()->is('admin/transaksi*') ? 'active' : '' }}" href="{{ url('/admin/transaksi') }}">
                <i class="bi bi-cart-check me-2"></i> Data Transaksi
            </a>
            <a href="{{ route('genre.index') }}" class="nav-link">
                <i class="bi bi-tags"></i> Kelola Genre
            </a>
            <a class="nav-link {{ request()->routeIs('admin.buku_rusak') ? 'active' : '' }}" href="{{ route('admin.buku_rusak') }}">
                <i class="bi bi-tools me-2"></i> Buku Rusak
                @php
                    // Ambil jumlah buku rusak buat notifikasi di sidebar
                    $jumlahRusak = \App\Models\Peminjaman::where('status', 'rusak')->count();
                @endphp
                @if($jumlahRusak > 0)
                    <span class="badge bg-danger ms-auto rounded-pill" style="font-size: 0.7rem;">{{ $jumlahRusak }}</span>
                @endif
            </a>
            <hr>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn btn-outline-danger btn-sm w-100 mt-2">
                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                </button>
            </form>
        </nav>
    </div>

    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
                <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>