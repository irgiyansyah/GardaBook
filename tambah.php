<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $tahun = $_POST['tahun'];

    $cover = $_FILES['cover']['name'];
    $tmp = $_FILES['cover']['tmp_name'];
    $uploadDir = 'cover/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if ($cover != '') {
        $targetPath = $uploadDir . basename($cover);
        move_uploaded_file($tmp, $targetPath);
    } else {
        $cover = 'default.jpg';
    }

    $stmt = $conn->prepare("INSERT INTO buku (judul, penulis, tahun, cover) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $judul, $penulis, $tahun, $cover);
    $stmt->execute();

    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Buku - GardaBook</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap + Google Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(to bottom right, #f1f5f9, #e2e8f0);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .form-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            padding: 40px;
            max-width: 600px;
            width: 100%;
            backdrop-filter: blur(10px);
            animation: fadeInUp 0.5s ease;
        }
        @keyframes fadeInUp {
            from {opacity: 0; transform: translateY(30px);}
            to {opacity: 1; transform: translateY(0);}
        }
        .form-label {
            font-weight: 500;
        }
        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 0.2rem rgba(59,130,246,.25);
        }
        .btn-modern {
            border: none;
            border-radius: 12px;
            padding: 10px 20px;
            font-weight: 500;
            transition: 0.3s;
        }
        .btn-success-modern {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }
        .btn-success-modern:hover {
            background: linear-gradient(135deg, #059669, #047857);
        }
        .btn-secondary-modern {
            background: #cbd5e1;
            color: #1e293b;
        }
        .btn-secondary-modern:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="form-card">
        <h3 class="mb-4 text-center fw-semibold"><i class="bi bi-book-plus"></i> Tambah Buku Baru</h3>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="judul" class="form-label">Judul Buku</label>
                <input type="text" name="judul" id="judul" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="penulis" class="form-label">Penulis</label>
                <input type="text" name="penulis" id="penulis" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="tahun" class="form-label">Tahun Terbit</label>
                <input type="number" name="tahun" id="tahun" class="form-control" required>
            </div>
            <div class="mb-4">
                <label for="cover" class="form-label">Upload Cover Buku</label>
                <input type="file" name="cover" id="cover" class="form-control">
            </div>
            <div class="d-flex justify-content-between">
                <a href="index.php" class="btn btn-secondary-modern btn-modern"><i class="bi bi-arrow-left"></i> Kembali</a>
                <button type="submit" class="btn btn-success-modern btn-modern"><i class="bi bi-save2"></i> Simpan</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
