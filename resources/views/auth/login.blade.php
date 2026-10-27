<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Masuk ke Sistem — SiPinjam Lab</title>

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
</head>
<body class="bg-white">

    <div class="login-split-wrapper">
        <!-- Panel Kiri: Identitas Visual & Branding -->
        <div class="login-hero-side">
            <div class="login-hero-icon-box">
                <svg width="46" height="46" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10 2v7.31L4.1 19.34A2 2 0 0 0 5.8 22h12.4a2 2 0 0 0 1.7-2.66L14 9.31V2"/>
                    <path d="M8.5 2h7"/>
                    <path d="M7 16h10"/>
                </svg>
            </div>
            <h1 class="login-hero-title">SiPinjam Lab</h1>
            <p class="login-hero-desc">
                Solusi manajemen peminjaman alat laboratorium yang cerdas dan terintegrasi.
            </p>
        </div>

        <!-- Panel Kanan: Form Otentikasi & Akun Demo -->
        <div class="login-form-side">
            <div class="login-form-container">
                <div class="login-header">
                    <h2 class="login-header-title">Selamat Datang</h2>
                    <p class="login-header-subtitle">Silakan masuk ke akun Anda</p>
                </div>

                <!-- Alert Feedback -->
                @if (session('success'))
                    <div class="sipinjam-alert sipinjam-alert-success mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill fs-5"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button type="button" class="btn-close" aria-label="Close" onclick="this.closest('.sipinjam-alert').remove()"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="sipinjam-alert sipinjam-alert-danger mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                        <button type="button" class="btn-close" aria-label="Close" onclick="this.closest('.sipinjam-alert').remove()"></button>
                    </div>
                @endif

                <!-- Form Login -->
                <form action="{{ route('login') }}" method="POST" id="loginForm">
                    @csrf

                    <!-- Input Username / NIM -->
                    <div class="mb-3">
                        <label for="username" class="form-label-custom">Username / NIM</label>
                        <div class="input-icon-wrapper">
                            <i class="bi bi-person"></i>
                            <input type="text" 
                                   name="username" 
                                   id="username" 
                                   class="form-control-custom @error('username') is-invalid @enderror" 
                                   placeholder="Admin atau NIM mahasiswa" 
                                   value="{{ old('username') }}" 
                                   required 
                                   autofocus 
                                   autocomplete="username">
                        </div>
                        @error('username')
                            <div class="text-danger small mt-1 d-flex align-items-center gap-1">
                                <i class="bi bi-exclamation-circle"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Input Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label-custom">Password</label>
                        <div class="input-icon-wrapper">
                            <i class="bi bi-lock"></i>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   class="form-control-custom @error('password') is-invalid @enderror" 
                                   placeholder="Masukkan password" 
                                   required 
                                   autocomplete="current-password">
                        </div>
                        @error('password')
                            <div class="text-danger small mt-1 d-flex align-items-center gap-1">
                                <i class="bi bi-exclamation-circle"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Tombol Masuk -->
                    <button type="submit" class="btn-sipinjam-primary w-100 mt-2" style="height: 48px; font-size: 15px;">
                        <span>Masuk ke Sistem</span>
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </form>

                <!-- Kotak Bantuan Akun Demo Uji Coba -->
                <div class="login-demo-box">
                    <div class="login-demo-header">
                        <i class="bi bi-info-circle-fill text-muted"></i>
                        <span>AKUN DEMO UJI COBA</span>
                    </div>

                    <!-- Row Akun Admin -->
                    <div class="login-demo-item">
                        <div class="login-demo-info">
                            <span class="login-demo-role">Admin</span>
                            <span class="login-demo-creds">admin / admin123</span>
                        </div>
                        <button type="button" class="btn-demo-fill" onclick="fillLogin('admin', 'admin123')">
                            GUNAKAN
                        </button>
                    </div>

                    <!-- Row Akun Mahasiswa -->
                    <div class="login-demo-item">
                        <div class="login-demo-info">
                            <span class="login-demo-role">Mahasiswa</span>
                            <span class="login-demo-creds">24051130084 / 123456</span>
                        </div>
                        <button type="button" class="btn-demo-fill" onclick="fillLogin('24051130084', '123456')">
                            GUNAKAN
                        </button>
                    </div>
                </div>

                <!-- Copyright Footer -->
                <div class="login-footer-copy">
                    &copy; 2026 Kelompok 6 &bull; UTS Praktikum MSI (Laravel MVC)
                </div>
            </div>
        </div>
    </div>

    <!-- Script Quick Auto-Fill Akun Demo -->
    <script>
        function fillLogin(username, password) {
            const userInput = document.getElementById('username');
            const passInput = document.getElementById('password');
            if (userInput && passInput) {
                userInput.value = username;
                passInput.value = password;
                userInput.focus();
            }
        }
    </script>

</body>
</html>
