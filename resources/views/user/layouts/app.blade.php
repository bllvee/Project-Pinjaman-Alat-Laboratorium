<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Portal Mahasiswa') - SiPinjam Lab</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('user.dashboard') }}">
            <i class="fa fa-flask fs-4 text-warning"></i>
            <span>SiPinjam Lab</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navUser">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navUser">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}" href="{{ route('user.dashboard') }}">
                        <i class="fa fa-house me-1"></i> Beranda Saya
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('user.barang.*') ? 'active' : '' }}" href="{{ route('user.barang.index') }}">
                        <i class="fa fa-boxes-stacked me-1"></i> Katalog Barang
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('user.peminjaman.create') ? 'active' : '' }}" href="{{ route('user.peminjaman.create') }}">
                        <i class="fa fa-hand-holding-hand me-1"></i> Ajukan Pinjam
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('user.peminjaman.index') ? 'active' : '' }}" href="{{ route('user.peminjaman.index') }}">
                        <i class="fa fa-clock-rotate-left me-1"></i> Riwayat Pinjam
                    </a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-3 text-white">
                <div class="text-end d-none d-sm-block lh-sm">
                    <div class="fw-semibold small">{{ auth()->user()->name }}</div>
                    <span class="badge bg-info text-dark" style="font-size: 10px;">
                        <i class="fa fa-graduation-cap me-1"></i> MAHASISWA
                    </span>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-light" title="Keluar">
                        <i class="fa fa-arrow-right-from-bracket"></i> <span class="d-none d-md-inline ms-1">Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<div class="container my-4">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fa fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fa fa-circle-exclamation me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @yield('content')
</div>

<footer class="text-center text-muted py-4 small">
    &copy; {{ date('Y') }} Sistem Peminjaman Alat Laboratorium &mdash; UTS Praktikum MSI Kelompok 6 (Laravel MVC)
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
