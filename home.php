<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>GardaBook - Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(to right, #f8fafc, #e2e8f0);
            min-height: 100vh;
        }
        .navbar-custom {
            border-radius: 16px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 15px 30px;
            margin-top: 20px;
        }
        .navbar-brand {
            font-weight: 600;
            color: #1e293b !important;
            font-size: 1.25rem;
        }
        .nav-link {
            font-weight: 500;
            color: #64748b !important;
            transition: color 0.2s ease;
            border-radius: 8px;
            padding: 8px 16px !important;
            text-decoration: none !important;
        }
        .nav-link:hover {
            color: #1e293b !important;
            background-color: rgba(30, 41, 59, 0.05);
        }
        .hero-section {
            padding: 100px 20px;
            text-align: center;
        }
        .hero-title {
            font-size: 2.8rem;
            font-weight: 600;
            color: #1e293b;
        }
        .hero-subtitle {
            font-size: 1.2rem;
            color: #475569;
            margin-top: 10px;
            margin-bottom: 30px;
        }
        .btn-primary-modern {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            padding: 12px 24px;
            font-size: 1rem;
            border: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            text-decoration: none !important;
            display: inline-block;
        }
        .btn-primary-modern:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }
        .features {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
            margin-top: 50px;
        }
        .feature-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            padding: 30px;
            width: 300px;
            text-align: center;
        }
        .feature-icon {
            font-size: 2rem;
            color: #3b82f6;
            margin-bottom: 10px;
        }
        .feature-title {
            font-weight: 600;
            font-size: 1.2rem;
            margin-bottom: 8px;
        }
        .feature-desc {
            font-size: 0.9rem;
            color: #64748b;
        }
    </style>
</head>
<body>
<div class="container">
    <nav class="navbar navbar-expand-lg navbar-custom mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="home.php"><i class="bi bi-book-half"></i> GardaBook</a>
            <div class="collapse navbar-collapse justify-content-center">
                <ul class="navbar-nav mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="perpustakaan.php">Perpustakaan</a></li>
                    <li class="nav-item"><a class="nav-link" href="layanan.php">Layanan</a></li>
                    <li class="nav-item"><a class="nav-link" href="kontak.php">Kontak</a></li>
                </ul>
            </div>
            <div class="d-flex align-items-center gap-2">
                <?php if (isset($_SESSION['admin'])): ?>
                    <a href="logout.php" class="btn btn-danger btn-sm"><i class="bi bi-box-arrow-right"></i> Logout</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-primary btn-sm"><i class="bi bi-box-arrow-in-right"></i> Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="hero-section">
        <h1 class="hero-title">Selamat Datang di GardaBook</h1>
        <p class="hero-subtitle">Kelola koleksi buku digital Anda dengan mudah dan aman</p>
        <a href="perpustakaan.php" class="btn-primary-modern">Buka Perpustakaan</a>

        <div class="features mt-5">
            <div class="feature-card">
                <div class="feature-icon"><i class="bi bi-book"></i></div>
                <div class="feature-title">Manajemen Buku</div>
                <div class="feature-desc">Tambah, edit, dan hapus buku dengan mudah melalui panel admin.</div>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="bi bi-shield-lock"></i></div>
                <div class="feature-title">Keamanan Data</div>
                <div class="feature-desc">Dilengkapi autentikasi & enkripsi untuk keamanan informasi.</div>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="bi bi-cloud-arrow-up"></i></div>
                <div class="feature-title">Akses Mudah</div>
                <div class="feature-desc">Dapat diakses kapan saja dari perangkat apapun dengan internet.</div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
