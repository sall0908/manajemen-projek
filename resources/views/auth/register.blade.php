<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Manajemen Proyek</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .background-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 6s ease-in-out infinite;
        }

        .shape:nth-child(1) {
            width: 100px;
            height: 100px;
            top: 10%;
            right: 15%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            width: 70px;
            height: 70px;
            top: 70%;
            left: 15%;
            animation-delay: 2s;
        }

        .shape:nth-child(3) {
            width: 50px;
            height: 50px;
            bottom: 10%;
            right: 20%;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .glass-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 40px 30px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .glass-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        }

        .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo h1 {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, #fff 0%, #e2e8f0 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 8px;
        }

        .logo p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
        }

        .form-group {
            margin-bottom: 18px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            font-size: 0.9rem;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            color: white;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .form-input:focus {
            outline: none;
            border-color: rgba(255, 255, 255, 0.4);
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.1);
        }

        .btn-primary {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .link-text {
            text-align: center;
            margin-top: 25px;
        }

        .link-text a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .link-text a:hover {
            color: white;
            text-decoration: underline;
        }

        .error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fecaca;
            padding: 12px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }

        .error ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .error li {
            margin-bottom: 5px;
            padding-left: 15px;
            position: relative;
        }

        .error li:before {
            content: '•';
            position: absolute;
            left: 0;
            color: #fecaca;
        }

        .error li:last-child {
            margin-bottom: 0;
        }

        /* Responsive Design */
        @media (max-width: 480px) {
            .glass-container {
                padding: 30px 20px;
                margin: 10px;
            }

            .logo h1 {
                font-size: 1.75rem;
            }

            .form-input {
                padding: 10px 14px;
            }

            .btn-primary {
                padding: 12px;
            }

            .form-group {
                margin-bottom: 15px;
            }
        }

        @media (max-width: 360px) {
            .glass-container {
                padding: 25px 15px;
            }

            .logo h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="background-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="glass-container">
        <div class="logo">
            <h1>Buat Akun Baru</h1>
            <p>Daftar untuk mulai mengelola proyek</p>
        </div>

        @if ($errors->any())
            <div class="error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ url('/register') }}">
            @csrf
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" class="form-input" value="{{ old('full_name') }}" placeholder="Masukkan nama lengkap" required>
            </div>

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-input" value="{{ old('username') }}" placeholder="Pilih username" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-input" value="{{ old('email') }}" placeholder="Masukkan email" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-input" placeholder="Buat password" required>
            </div>

            <div class="form-group">
                <label>Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-input" placeholder="Ulangi password" required>
            </div>

            <button type="submit" class="btn-primary">Daftar Sekarang</button>
        </form>

        <div class="link-text">
            <a href="{{ route('login') }}">Sudah punya akun? Masuk di sini</a>
        </div>
    </div>
</body>
</html>
