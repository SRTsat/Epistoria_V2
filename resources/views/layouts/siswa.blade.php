<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siswa - Perpustakaan Digital</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-color: #4361ee;
            --bg-light: #f8f9fa;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-light);
            color: #2b2d42;
        }

        /* Navbar Styling */
        .navbar {
            background-color: #ffffff !important;
            border-bottom: 1px solid #e9ecef;
            padding: 15px 0;
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
            letter-spacing: -0.5px;
        }

        .nav-link {
            color: #6c757d !important;
            font-weight: 500;
            padding: 8px 16px !important;
            border-radius: 8px;
            transition: all 0.3s;
            margin: 0 4px;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
            background-color: rgba(67, 97, 238, 0.05);
        }

        .nav-link.active {
            color: var(--primary-color) !important;
            background-color: rgba(67, 97, 238, 0.1);
        }

        /* Alert Styling */
        .alert {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        /* Profile & Logout Section */
        .user-section {
            display: flex;
            align-items: center;
            gap: 15px;
            border-left: 1px solid #e9ecef;
            padding-left: 20px;
            margin-left: 10px;
        }

        .btn-logout {
            background-color: #fee2e2;
            color: #ef4444;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            transition: 0.3s;
        }

        .btn-logout:hover {
            background-color: #ef4444;
            color: white;
        }
    </style>

    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('siswa.dashboard') }}">
                <i class="bi bi-book-half me-2 fs-3"></i>
                <span>Epis<span class="text-dark">Storia</span></span>
            </a>

            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}" href="{{ route('siswa.dashboard') }}">
                            <i class="bi bi-grid-1x2 me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('siswa.katalog') ? 'active' : '' }}" href="{{ route('siswa.katalog') }}">
                            <i class="bi bi-search me-1"></i> Cari Buku
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('siswa.pinjam') ? 'active' : '' }}" href="{{ route('siswa.pinjam') }}">
                            <i class="bi bi-collection me-1"></i> Buku Saya
                        </a>
                    </li>
                </ul>

                <div class="user-section">
                    <span class="small fw-semibold text-muted d-none d-lg-block">
                        Hi, {{ Auth::user()->name }}
                    </span>
                    <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Yakin mau logout?')">
                        @csrf
                        <button class="btn-logout">
                            <i class="bi bi-box-arrow-right"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="container py-2">
        @if(session('success'))
            <div class="alert alert-success d-flex align-items-center shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger d-flex align-items-center shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>