<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SiPinjam Lab') — Sistem Peminjaman Alat Laboratorium</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- SiPinjam Lab Single Source of Truth Design System -->
    <link rel="stylesheet" href="{{ asset('css/sipinjam.css') }}">

    @stack('styles')
</head>
<body>

    <!-- Main Header / Navbar (Tinggi 80px) -->
    <header class="sipinjam-navbar">
        <div class="container-nav">
            <!-- Left Group: Logo & Navigasi -->
            <div class="nav-left-group">
                <!-- Brand Logo & Nama -->
                <a href="{{ auth()->check() ? (auth()->user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard')) : route('login') }}" class="brand-wrapper" data-purpose="site-logo">
                    <div class="brand-logo-box">
                        <!-- Beaker / Flask Icon -->
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10 2v7.31L4.69 18.06A2 2 0 0 0 6.4 21h11.2a2 2 0 0 0 1.71-2.94L14 9.31V2"></path>
                            <path d="M8.5 2h7"></path>
                            <path d="M7 16h10"></path>
                        </svg>
                    </div>
                    <span class="brand-name">SiPinjam Lab</span>
                </a>

                <!-- Desktop Navigation Menu -->
                <nav class="d-none d-lg-flex h-100">
                    <ul class="nav-menu-links">
                        @if (auth()->check())
                            @if (auth()->user()->role === 'admin')
                                <li class="nav-menu-item">
                                    <a href="{{ route('admin.dashboard') }}" class="nav-link-custom {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
                                        Dashboard
                                    </a>
                                </li>
                                <li class="nav-menu-item">
                                    <a href="{{ route('admin.barang.index') }}" class="nav-link-custom {{ request()->routeIs('admin.barang*') ? 'active' : '' }}">
                                        Data Barang
                                    </a>
                                </li>
                                <li class="nav-menu-item">
                                    <a href="{{ route('admin.kategori.index') }}" class="nav-link-custom {{ request()->routeIs('admin.kategori*') ? 'active' : '' }}">
                                        Kategori
                                    </a>
                                </li>
                                <li class="nav-menu-item">
                                    <a href="{{ route('admin.peminjaman.index') }}" class="nav-link-custom {{ request()->routeIs('admin.peminjaman*') ? 'active' : '' }}">
                                        Transaksi
                                    </a>
                                </li>
                            @else
                                <li class="nav-menu-item">
                                    <a href="{{ route('user.dashboard') }}" class="nav-link-custom {{ request()->routeIs('user.dashboard*') ? 'active' : '' }}">
                                        Dashboard
                                    </a>
                                </li>
                                <li class="nav-menu-item">
                                    <a href="{{ route('user.barang.index') }}" class="nav-link-custom {{ request()->routeIs('user.barang*') ? 'active' : '' }}">
                                        Data Alat
                                    </a>
                                </li>
                                <li class="nav-menu-item">
                                    <a href="{{ route('user.peminjaman.index') }}" class="nav-link-custom {{ request()->routeIs('user.peminjaman*') ? 'active' : '' }}">
                                        Peminjaman Saya
                                    </a>
                                </li>
                            @endif
                        @endif
                    </ul>
                </nav>
            </div>

            <!-- Right Group: User Profile, Logout & Mobile Toggler -->
            <div class="nav-user-section" data-purpose="user-profile-menu">
                @if (auth()->check())
                    <!-- Profile Text -->
                    <div class="user-profile-info d-none d-sm-flex">
                        <div class="user-profile-name">{{ auth()->user()->name }}</div>
                        <span class="user-role-badge">
                            {{ auth()->user()->role === 'admin' ? 'Admin Lab' : 'Mahasiswa' }}
                        </span>
                    </div>

                    <!-- Inisial Avatar (Bukan Foto Eksternal) -->
                    <div class="user-avatar-circle" title="{{ auth()->user()->name }}">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>

                    <!-- Logout Button -->
                    <form action="{{ route('logout') }}" method="POST" class="d-inline m-0">
                        @csrf
                        <button type="submit" class="btn-logout-icon" title="Keluar / Logout" onclick="return confirm('Apakah Anda yakin ingin keluar?')">
                            <i class="bi bi-box-arrow-right"></i>
                        </button>
                    </form>

                    <!-- Mobile Menu Toggler Button -->
                    <button class="navbar-mobile-toggler d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileNavMenu" aria-controls="mobileNavMenu" aria-label="Toggle navigation">
                        <i class="bi bi-list"></i>
                    </button>
                @else
                    <a href="{{ route('login') }}" class="btn-sipinjam-primary btn-sm">
                        <i class="bi bi-box-arrow-in-right"></i>
                        <span>Masuk</span>
                    </a>
                @endif
            </div>
        </div>
    </header>

    <!-- Mobile Offcanvas Menu -->
    @if (auth()->check())
        <div class="offcanvas offcanvas-end" tabindex="-1" id="mobileNavMenu" aria-labelledby="mobileNavMenuLabel">
            <div class="offcanvas-header border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <div class="brand-logo-box" style="width: 36px; height: 36px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10 2v7.31L4.69 18.06A2 2 0 0 0 6.4 21h11.2a2 2 0 0 0 1.71-2.94L14 9.31V2"></path>
                            <path d="M8.5 2h7"></path>
                            <path d="M7 16h10"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="fw-bold text-dark fs-6">SiPinjam Lab</div>
                        <span class="text-secondary small">{{ auth()->user()->name }} ({{ auth()->user()->role }})</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body p-0">
                <ul class="list-group list-group-flush">
                    @if (auth()->user()->role === 'admin')
                        <li class="list-group-item">
                            <a href="{{ route('admin.dashboard') }}" class="d-block py-2 text-decoration-none {{ request()->routeIs('admin.dashboard*') ? 'text-teal fw-bold' : 'text-dark' }}">
                                <i class="bi bi-grid me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="list-group-item">
                            <a href="{{ route('admin.barang.index') }}" class="d-block py-2 text-decoration-none {{ request()->routeIs('admin.barang*') ? 'text-teal fw-bold' : 'text-dark' }}">
                                <i class="bi bi-box-seam me-2"></i> Data Barang
                            </a>
                        </li>
                        <li class="list-group-item">
                            <a href="{{ route('admin.kategori.index') }}" class="d-block py-2 text-decoration-none {{ request()->routeIs('admin.kategori*') ? 'text-teal fw-bold' : 'text-dark' }}">
                                <i class="bi bi-tags me-2"></i> Kategori
                            </a>
                        </li>
                        <li class="list-group-item">
                            <a href="{{ route('admin.peminjaman.index') }}" class="d-block py-2 text-decoration-none {{ request()->routeIs('admin.peminjaman*') ? 'text-teal fw-bold' : 'text-dark' }}">
                                <i class="bi bi-arrow-left-right me-2"></i> Transaksi
                            </a>
                        </li>
                    @else
                        <li class="list-group-item">
                            <a href="{{ route('user.dashboard') }}" class="d-block py-2 text-decoration-none {{ request()->routeIs('user.dashboard*') ? 'text-teal fw-bold' : 'text-dark' }}">
                                <i class="bi bi-grid me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="list-group-item">
                            <a href="{{ route('user.barang.index') }}" class="d-block py-2 text-decoration-none {{ request()->routeIs('user.barang*') ? 'text-teal fw-bold' : 'text-dark' }}">
                                <i class="bi bi-box-seam me-2"></i> Data Alat
                            </a>
                        </li>
                        <li class="list-group-item">
                            <a href="{{ route('user.peminjaman.index') }}" class="d-block py-2 text-decoration-none {{ request()->routeIs('user.peminjaman*') ? 'text-teal fw-bold' : 'text-dark' }}">
                                <i class="bi bi-journal-text me-2"></i> Peminjaman Saya
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    @endif

    <!-- Main Content Container -->
    <main class="flex-grow-1 py-4">
        <div class="sipinjam-container">
            <!-- Flash Notification Alerts -->
            @if (session('success'))
                <div class="sipinjam-alert sipinjam-alert-success" role="alert">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill fs-5"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" class="btn-close" aria-label="Close" onclick="this.closest('.sipinjam-alert').remove()"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="sipinjam-alert sipinjam-alert-danger" role="alert">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button type="button" class="btn-close" aria-label="Close" onclick="this.closest('.sipinjam-alert').remove()"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="sipinjam-alert sipinjam-alert-danger flex-column align-items-start" role="alert">
                    <div class="d-flex align-items-center justify-content-between w-100 mb-1">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-exclamation-circle-fill fs-5"></i>
                            <strong>Validasi Formulir Gagal:</strong>
                        </div>
                        <button type="button" class="btn-close" aria-label="Close" onclick="this.closest('.sipinjam-alert').remove()"></button>
                    </div>
                    <ul class="mb-0 ps-4 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Content Slot -->
            @yield('content')
        </div>
    </main>

    <!-- Global Footer -->
    <footer class="sipinjam-footer">
        <div class="footer-container">
            <div>
                &copy; {{ date('Y') }} Sistem Peminjaman Alat Laboratorium &mdash; UTS Praktikum MSI Kelompok 6 (Laravel MVC)
            </div>
            <div class="footer-links">
                <a href="#">Bantuan</a>
                <a href="#">Kebijakan Privasi</a>
                <a href="#">Syarat &amp; Ketentuan</a>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    @stack('scripts')
</body>
</html>
