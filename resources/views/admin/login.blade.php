<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Dapoer Budess</title>
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --accent-orange: #f97316;
        }
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background: #f8fafc;
        }
        .main-container {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }
        /* Kiri: Background Image & Text */
        .left-side {
            flex: 1;
            background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url("{{ asset('images/rooti.jpg') }}");
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 80px;
            color: white;
            position: relative;
        }
        .logo-container {
            margin-bottom: 2rem;
        }
        .logo-badge {
            display: inline-block;
        }
        .logo-img {
            height: 80px;
            border-radius: 12px;
            filter: drop-shadow(0 10px 15px rgba(0,0,0,0.3));
        }
        .welcome-text {
            font-size: 3.5rem;
            font-weight: 700;
            line-height: 1.1;
            margin-bottom: 2rem;
        }
        .description-text {
            font-size: 1.125rem;
            line-height: 1.8;
            max-width: 600px;
            color: #f1f5f9;
            opacity: 0.95;
            text-align: center;
        }

        /* Kanan: Login Form */
        .right-side {
            width: 45%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: #fdfdfd;
        }
        .login-card {
            background: white;
            width: 100%;
            max-width: 480px;
            padding: 50px;
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            position: relative;
            overflow: hidden;
            border-top: 4px solid transparent;
            border-image: linear-gradient(to right, #2563eb, #f97316);
            border-image-slice: 1;
        }

        .card-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }
        .card-header h2 {
            font-size: 1.875rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }
        .card-header p {
            color: #64748b;
            font-size: 1rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.5rem;
        }
        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.2s;
            background: #fff;
        }
        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        .remember-me {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.875rem;
            color: #64748b;
            cursor: pointer;
        }
        .forgot-link {
            font-size: 0.875rem;
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }

        .btn-submit {
            width: 100%;
            background: var(--primary);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9375rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: background 0.2s;
        }
        .btn-submit:hover {
            background: #1d4ed8;
        }

        .help-text {
            text-align: center;
            margin-top: 2rem;
            font-size: 0.875rem;
            color: #64748b;
        }
        .copyright-text {
            text-align: center;
            margin-top: 1rem;
            font-size: 0.75rem;
            color: #94a3b8;
        }

        .security-note {
            margin-top: 3rem;
            background: #eff6ff;
            color: #1e40af;
            padding: 12px;
            border-radius: 8px;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        @media (max-width: 1024px) {
            .left-side { padding: 40px; }
            .welcome-text { font-size: 2.5rem; }
            .right-side { width: 50%; }
        }
        @media (max-width: 768px) {
            .main-container { flex-direction: column; }
            .left-side { display: none; }
            .right-side { width: 100%; min-height: 100vh; }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- Panel Kiri -->
        <div class="left-side">
            <div class="logo-container">
                <div class="logo-badge">
                    <img src="{{ asset('images/budess.jpg') }}" alt="Logo" class="logo-img">
                </div>
            </div>
            <h1 class="welcome-text">Selamat Datang Kembali</h1>
            <p class="description-text">
              Nikmati kelezatan roti yang dipanggang setiap hari dengan penuh cinta dan bahan pilihan terbaik. Dari roti klasik hingga favorit keluarga, semua kami sajikan hangat, lembut, dan siap menemani momen spesial Anda.
        </div>

        <!-- Panel Kanan -->
        <div class="right-side">
            <div class="login-card">
                <div class="card-header">
                    <h2>Selamat Datang Admin</h2>
                    <p>Masuk ke panel admin Anda</p>
                </div>

                <!-- Session Status & Errors -->
                @if (session('status'))
                    <div style="background: #f0fdf4; color: #16a34a; padding: 12px; border-radius: 8px; font-size: 0.875rem; margin-bottom: 1.5rem; border: 1px solid #bbf7d0;">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div style="background: #fef2f2; color: #dc2626; padding: 12px; border-radius: 8px; font-size: 0.875rem; margin-bottom: 1.5rem; border: 1px solid #fecaca;">
                        <ul style="list-style: none; margin: 0; padding: 0;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus class="form-input" placeholder="admin@budess.com">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" required class="form-input" placeholder="password">
                    </div>

                    <div class="form-options">
                        <label class="remember-me">
                            <input type="checkbox" name="remember" style="width: 16px; height: 16px;">
                            Ingat saya
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-link">Lupa password?</a>
                        @endif
                    </div>

                    <button type="submit" class="btn-submit">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        MASUK KE ADMIN PANEL
                    </button>

                    <p class="help-text">
                        Butuh bantuan? Hubungi administrator.
                    </p>
                    <p class="copyright-text">
                        © {{ date('Y') }} Admin Panel Roti. Semua hak dilindungi.
                    </p>

                    <div class="security-note">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        Halaman ini aman dan terenkripsi. Jangan bagikan kredensial Anda.
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
