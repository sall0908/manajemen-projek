<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Proyek - ITSME</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .bg-gradient-blue {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        }
        .btn-primary {
            background-color: #1e40af;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #1e3a8a;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .hero-wave {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
        }
        .hero-wave svg {
            position: relative;
            display: block;
            width: calc(100% + 1.3px);
            height: 80px;
        }
        .hero-wave .shape-fill {
            fill: #FFFFFF;
        }
    </style>
</head>
<body class="antialiased bg-white">
    <div class="relative min-h-screen bg-gradient-blue">
        <div class="container mx-auto px-4 py-32 flex flex-col items-center justify-center text-center h-screen">
            <h1 class="text-5xl md:text-6xl font-bold text-white mb-6">Selamat Datang di Web Manajemen Proyek</h1>
            <h2 class="text-3xl md:text-4xl font-semibold text-blue-100 mb-8">ITSME</h2>
            <p class="text-xl text-white mb-12 max-w-2xl">
                Platform manajemen proyek yang membantu Anda mengelola tugas, kolaborasi tim, dan mencapai tujuan proyek dengan lebih efisien.
            </p>
            <a href="{{ route('login') }}" class="btn-primary text-white font-bold py-3 px-8 rounded-full text-xl">
                Mulai
            </a>
        </div>
        
        <div class="hero-wave">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
            </svg>
        </div>
    </div>

    <div class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-blue-50 p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-semibold text-blue-800 mb-3">Manajemen Tugas</h3>
                    <p class="text-gray-700">Kelola tugas proyek dengan mudah dan efisien. Tetapkan tenggat waktu, prioritas, dan pantau kemajuan.</p>
                </div>
                <div class="bg-blue-50 p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-semibold text-blue-800 mb-3">Kolaborasi Tim</h3>
                    <p class="text-gray-700">Bekerja sama dengan tim Anda secara real-time. Bagikan dokumen, diskusikan ide, dan selesaikan proyek bersama.</p>
                </div>
                <div class="bg-blue-50 p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-semibold text-blue-800 mb-3">Analisis Proyek</h3>
                    <p class="text-gray-700">Dapatkan wawasan tentang kinerja proyek Anda dengan analisis dan laporan yang komprehensif.</p>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-blue-900 text-white py-8">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; {{ date('Y') }} Manajemen Proyek ITSME. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>