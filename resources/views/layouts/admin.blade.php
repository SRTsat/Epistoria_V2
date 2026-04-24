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
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

        :root {
            --sidebar-bg: #0f172a;
            --primary-blue: #3b82f6;
            --accent-color: #6366f1;
        }

        body { 
            background-color: #f8fafc; 
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #1e293b;
            overflow-x: hidden;
        }

        /* Sidebar Modern */
        .sidebar { 
            height: 100vh; 
            width: 280px; 
            position: fixed; 
            background: var(--sidebar-bg); 
            color: white; 
            padding: 20px 0;
            z-index: 1000;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-right: 1px solid rgba(255,255,255,0.05);
        }

        .brand-section {
            padding: 0 25px 30px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-logo {
            width: 35px;
            height: 35px;
            background: linear-gradient(135deg, var(--primary-blue), var(--accent-color));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }

        /* User Profile Mini */
        .user-profile-mini {
            margin: 0 20px 25px;
            padding: 15px;
            background: rgba(255,255,255,0.03);
            border-radius: 16px;
            border: 1px solid rgba(255,255,255,0.05);
        }

        .main-content { 
            margin-left: 280px; 
            padding: 30px 45px; 
            transition: all 0.3s ease;
            min-height: 100vh;
        }

        /* Nav Link Enhancement */
        .nav-link { 
            color: #94a3b8; 
            padding: 12px 20px;
            border-radius: 12px;
            margin: 4px 18px;
            font-weight: 500;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link i { 
            font-size: 1.2rem; 
            margin-right: 12px;
            transition: all 0.3s ease;
        }

        .nav-link:hover { 
            color: #fff; 
            background: rgba(255, 255, 255, 0.05);
        }

        .nav-link.active {
            background: rgba(59, 130, 246, 0.1);
            color: var(--primary-blue) !important;
        }

        /* Indicator Active */
        .nav-link.active::before {
            content: "";
            position: absolute;
            left: -18px;
            height: 20px;
            width: 4px;
            background: var(--primary-blue);
            border-radius: 0 4px 4px 0;
            box-shadow: 2px 0 10px rgba(59, 130, 246, 0.5);
        }

        .nav-link.active i {
            color: var(--primary-blue);
        }

        .section-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #475569;
            margin: 20px 35px 10px;
            font-weight: 700;
        }

        /* Alert Styling */
        .alert {
            border: none;
            border-radius: 16px;
            padding: 1rem 1.5rem;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="brand-section">
            <div class="brand-logo"><i class="bi bi-book-half"></i></div>
            <span class="fw-bold h5 mb-0 text-white">Epistoria</span>
        </div>

        <div class="user-profile-mini">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white fw-bold" style="width: 40px; height: 40px; font-size: 0.9rem;">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
                <div class="flex-grow-1 ms-3 overflow-hidden">
                    <p class="text-white small fw-bold mb-0 text-truncate">{{ Auth::user()->name }}</p>
                </div>
            </div>
        </div>

        <div class="section-title">Main Menu</div>
        <nav class="nav flex-column">
            <a class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}" href="{{ url('/admin/dashboard') }}">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>
            <a class="nav-link {{ request()->is('admin/buku*') ? 'active' : '' }}" href="{{ route('buku.index') }}">
                <i class="bi bi-journal-text"></i> Kelola Buku
            </a>
            <a class="nav-link {{ request()->is('admin/genre*') ? 'active' : '' }}" href="{{ route('genre.index') }}">
                <i class="bi bi-tags-fill"></i> Genre Buku
            </a>
            <a class="nav-link {{ request()->is('admin/anggota*') ? 'active' : '' }}" href="{{ url('/admin/anggota') }}">
                <i class="bi bi-people-fill"></i> Anggota
            </a>
            
            <div class="section-title">Transaksi</div>
            <a class="nav-link {{ request()->is('admin/transaksi*') ? 'active' : '' }}" href="{{ url('/admin/transaksi') }}">
                <i class="bi bi-arrow-left-right"></i> Log Transaksi
            </a>
            <a class="nav-link {{ request()->routeIs('admin.buku_rusak') ? 'active' : '' }}" href="{{ route('admin.buku_rusak') }}">
                <i class="bi bi-exclamation-octagon-fill"></i> Buku Rusak
                @php
                    $jumlahRusak = \App\Models\Peminjaman::where('status', 'rusak')->count();
                @endphp
                @if($jumlahRusak > 0)
                    <span class="badge bg-danger ms-auto rounded-pill">{{ $jumlahRusak }}</span>
                @endif
            </a>

            <div class="mt-auto pt-4 px-4">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-link text-danger text-decoration-none small d-flex align-items-center p-0">
                        <i class="bi bi-box-arrow-left me-2"></i> Keluar Aplikasi
                    </button>
                </form>
            </div>
        </nav>
    </div>

    <div class="main-content">
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4">
                <div class="d-flex">
                    <i class="bi bi-check-circle-fill me-3 fs-4"></i>
                    <div>
                        <h6 class="alert-heading fw-bold mb-1">Berhasil!</h6>
                        <p class="mb-0 small">{{ session('success') }}</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>