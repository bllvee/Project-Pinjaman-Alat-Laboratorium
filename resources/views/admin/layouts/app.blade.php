<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') — SiPinjam Lab</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- SiPinjam Lab Design System CSS -->
    <link rel="stylesheet" href="{{ asset('css/sipinjam.css') }}">

    @stack('styles')
</head>
<body>

    <!-- Header / Navbar Standardized (Tinggi 80px) -->
    <header class="sipinjam-navbar">
        <div class="container-nav">
            <!-- Brand Logo & Title -->
            <a href="{{ route('admin.dashboard') }}" class="brand-wrapper">
                <div class="brand-logo-box">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10 2v7.31L4.1 19.34A2 2 0 0 0 5.8 22h12.4a2 2 0 0 0 1.7-2.66L14 9.31V2"/>
                        <path d="M8.5 2h7"/>
                        <path d="M7 16h10"/>
                    </svg>
                </div>
                <div>
                    <span class="brand-name">SiPinjam Lab</span>
                </div>
            </a>

            <!-- Menu Navigasi Horizontal Admin -->
            <ul class="nav-menu-links">
                <li class="nav-menu-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link-custom {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
                        <i class="bi bi-grid me-1"></i> Dashboard
                    </a>
                </li>
                <li class="nav-menu-item">
                    <a href="{{ route('admin.barang.index') }}" class="nav-link-custom {{ request()->routeIs('admin.barang*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam me-1"></i> Data Barang
                    </a>
                </li>
                <li class="nav-menu-item">
                    <a href="{{ route('admin.kategori.index') }}" class="nav-link-custom {{ request()->routeIs('admin.kategori*') ? 'active' : '' }}">
                        <i class="bi bi-tags me-1"></i> Kategori
                    </a>
                </li>
                <li class="nav-menu-item">
                    <a href="{{ route('admin.peminjaman.index') }}" class="nav-link-custom {{ request()->routeIs('admin.peminjaman*') ? 'active' : '' }}">
                        <i class="bi bi-arrow-left-right me-1"></i> Transaksi
                    </a>
                </li>
            </ul>

            <!-- Profil User & Logout -->
            <div class="nav-user-section">
                @if (auth()->check())
                    <div class="user-avatar-circle" title="{{ auth()->user()->name }}">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div class="user-profile-info">
                        <div class="user-profile-name">Halo, {{ auth()->user()->name }}</div>
                        <span class="user-role-badge">Admin</span>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline m-0">
                        @csrf
                        <button type="submit" class="btn-logout-icon" title="Keluar / Logout" onclick="return confirm('Apakah Anda yakin ingin keluar?')">
                            <i class="bi bi-box-arrow-right"></i>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="py-4">
        <div class="sipinjam-container">
            <!-- Flash Notification Alerts -->
            @if (session('success'))
                <div class="sipinjam-alert sipinjam-alert-success">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill fs-5"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" class="btn-close" aria-label="Close" onclick="this.closest('.sipinjam-alert').remove()"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="sipinjam-alert sipinjam-alert-danger">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button type="button" class="btn-close" aria-label="Close" onclick="this.closest('.sipinjam-alert').remove()"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="sipinjam-alert sipinjam-alert-danger flex-column align-items-start">
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

            <!-- Page Content Slot -->
            @yield('content')
        </div>
    </main>

    <!-- Global Footer -->
    <footer class="sipinjam-footer">
        <div class="footer-container">
            <div>
                &copy; {{ date('Y') }} <strong>SiPinjam Lab</strong> &mdash; Sistem Peminjaman Alat Laboratorium. All rights reserved.
            </div>
            <div class="d-none d-md-flex align-items-center gap-3">
                <span>Praktikum Manajemen Sistem Informasi</span>
                <span class="text-muted">&bull;</span>
                <span>UTS Laravel MVC</span>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    @stack('scripts')
</body>
</html>
