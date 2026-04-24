<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EpisStoria - Perpustakaan Digital</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --primary: #4361ee;
            --primary-soft: rgba(67, 97, 238, 0.1);
            --bg-body: #fdfdfe;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            color: #1f2937;
        }

        /* Navbar Transparan / Glassmorphism */
        .navbar {
            background: rgba(255, 255, 255, 0.85) !important;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 12px 0;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.4rem;
            color: var(--primary) !important;
            letter-spacing: -1px;
        }

        .nav-link {
            color: #4b5563 !important;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 10px 20px !important;
            border-radius: 12px;
            transition: all 0.25s;
        }

        .nav-link:hover {
            color: var(--primary) !important;
            background: var(--primary-soft);
        }

        .nav-link.active {
            color: var(--primary) !important;
            background: var(--primary-soft);
        }

        /* Profile & Logout */
        .user-avatar {
            width: 38px;
            height: 38px;
            background: var(--primary);
            color: white;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            box-shadow: 0 4px 10px rgba(67, 97, 238, 0.3);
        }

        .logout-pill {
            background: #fff1f2;
            color: #e11d48;
            border: 1px solid #ffe4e6;
            padding: 8px 12px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.2s;
        }

        .logout-pill:hover {
            background: #e11d48;
            color: white;
        }

        /* Container Main */
        .main-wrapper {
            padding-top: 20px;
            padding-bottom: 50px;
        }

        /* Custom Alert */
        .alert {
            border: none;
            border-radius: 16px;
            padding: 16px 20px;
        }
        
        .alert-success { background-color: #ecfdf5; color: #065f46; }
        .alert-danger { background-color: #fef2f2; color: #991b1b; }

    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('siswa.dashboard') }}">
                <div class="me-2 d-flex align-items-center justify-content-center bg-primary text-white rounded-3" style="width: 32px; height: 32px;">
                    <i class="bi bi-bookmarks-fill fs-6"></i>
                </div>
                <span>Epis<span class="text-dark">Storia</span></span>
            </a>

            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="bi bi-list fs-2"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}" href="{{ route('siswa.dashboard') }}">
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('siswa.katalog') ? 'active' : '' }}" href="{{ route('siswa.katalog') }}">
                            Katalog Buku
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('siswa.pinjam') ? 'active' : '' }}" href="{{ route('siswa.pinjam') }}">
                            Peminjaman
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-3 border-start ps-lg-4">
                    <div class="d-flex align-items-center gap-2">
                        <div class="user-avatar">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="d-none d-xl-block">
                            <p class="mb-0 small fw-bold">{{ Auth::user()->name }}</p>
                            <p class="mb-0 text-muted" style="font-size: 10px;">Siswa Perpustakaan</p>
                        </div>
                    </div>
                    
                    <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Yakin mau keluar, bro?')">
                        @csrf
                        <button class="logout-pill btn-sm d-flex align-items-center gap-1">
                            <i class="bi bi-power"></i> <span class="d-none d-sm-inline">Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="container main-wrapper">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
                <div class="d-flex">
                    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                    <div>{{ session('success') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
                <div class="d-flex">
                    <i class="bi bi-exclamation-octagon-fill me-2 fs-5"></i>
                    <div>{{ session('error') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>