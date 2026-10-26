<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - SiPinjam Lab (Laravel)</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0f3d3e 0%, #146356 50%, #208e7a 100%);
            padding: 20px;
        }
        .login-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            width: 100%;
            max-width: 440px;
        }
        .login-header {
            background: linear-gradient(to right, #0f3d3e, #146356);
            color: #ffffff;
            padding: 30px 25px;
            text-align: center;
        }
        .login-header i.fa-flask {
            font-size: 42px;
            margin-bottom: 10px;
            color: #5eead4;
        }
        .login-body {
            padding: 30px 25px 25px 25px;
        }
        .demo-box {
            background-color: #f0fdf4;
            border: 1px dashed #86efac;
            border-radius: 8px;
            padding: 12px;
            font-size: 12px;
            color: #166534;
        }
        .demo-btn {
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 4px;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-header">
        <i class="fa fa-flask"></i>
        <h4 class="fw-bold mb-1">SiPinjam Lab</h4>
        <p class="mb-0 small text-light opacity-75">Sistem Peminjaman Alat Laboratorium</p>
    </div>

    <div class="login-body">
        @if (session('success'))
            <div class="alert alert-success py-2 px-3 small mb-3">
                <i class="fa fa-circle-check me-1"></i> {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger py-2 px-3 small mb-3">
                <i class="fa fa-circle-exclamation me-1"></i> {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label small fw-semibold">Username / NIM</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted"><i class="fa fa-user"></i></span>
                    <input type="text" name="username" id="username" class="form-control" 
                           placeholder="admin atau NIM mahasiswa" value="{{ old('username') }}" required autofocus>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-semibold">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted"><i class="fa fa-lock"></i></span>
                    <input type="password" name="password" id="password" class="form-control" 
                           placeholder="Masukkan password" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary-lab text-white w-100 py-2 fw-semibold">
                <i class="fa fa-arrow-right-to-bracket me-1"></i> Masuk ke Sistem
            </button>
        </form>

        <div class="demo-box mt-3">
            <div class="fw-bold mb-1"><i class="fa fa-key me-1"></i> Akun Demo Uji Coba:</div>
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span><strong>Admin:</strong> <code>admin</code> / <code>admin123</code></span>
                <button type="button" class="btn btn-sm btn-outline-success demo-btn" onclick="isiAkun('admin', 'admin123')">Gunakan</button>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <span><strong>Mahasiswa:</strong> <code>24051130084</code> / <code>123456</code></span>
                <button type="button" class="btn btn-sm btn-outline-success demo-btn" onclick="isiAkun('24051130084', '123456')">Gunakan</button>
            </div>
        </div>

        <div class="text-center text-muted mt-3" style="font-size: 12px;">
            &copy; {{ date('Y') }} Kelompok 6 &bull; UTS Praktikum MSI (Laravel MVC)
        </div>
    </div>
</div>

<script>
function isiAkun(u, p) {
    document.getElementById('username').value = u;
    document.getElementById('password').value = p;
}
</script>

</body>
</html>
