<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
include 'db.php';

$id = (int)$_GET['id'];
$result = $conn->query("SELECT * FROM buku WHERE id=$id");
$row = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $tahun = $_POST['tahun'];
    $cover = $row['cover'];

    if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
        $namaFile = $_FILES['cover']['name'];
        $tmpName = $_FILES['cover']['tmp_name'];
        $ext = pathinfo($namaFile, PATHINFO_EXTENSION);
        $namaBaru = uniqid() . '.' . $ext;

        if (!is_dir('cover')) {
            mkdir('cover', 0777, true);
        }
        move_uploaded_file($tmpName, 'cover/' . $namaBaru);

        if ($cover && file_exists('cover/' . $cover) && $cover !== 'default.jpg') {
            unlink('cover/' . $cover);
        }

        $cover = $namaBaru;
    }

    $conn->query("UPDATE buku SET judul='$judul', penulis='$penulis', tahun='$tahun', cover='$cover' WHERE id=$id");

    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Buku - GardaBook</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
        .btn-primary-modern {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
        }
        .btn-primary-modern:hover {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
        }
        .btn-secondary-modern {
            background: #cbd5e1;
            color: #1e293b;
        }
        .btn-secondary-modern:hover {
            background: #94a3b8;
        }
        .img-preview {
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>
    <div class="form-card">
        <h3 class="mb-4 text-center fw-semibold"><i class="bi bi-pencil-square"></i> Edit Buku</h3>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="judul" class="form-label">Judul Buku</label>
                <input type="text" name="judul" id="judul" class="form-control" value="<?= htmlspecialchars($row['judul']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="penulis" class="form-label">Penulis</label>
                <input type="text" name="penulis" id="penulis" class="form-control" value="<?= htmlspecialchars($row['penulis']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="tahun" class="form-label">Tahun Terbit</label>
                <input type="number" name="tahun" id="tahun" class="form-control" value="<?= $row['tahun'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Cover Saat Ini</label><br>
                <img src="cover/<?= $row['cover'] ?? 'default.jpg' ?>" alt="Cover buku" width="120" class="img-preview mt-1">
            </div>
            <div class="mb-4">
                <label for="cover" class="form-label">Ganti Cover (opsional)</label>
                <input type="file" name="cover" id="cover" class="form-control">
            </div>
            <div class="d-flex justify-content-between">
                <a href="index.php" class="btn btn-secondary-modern btn-modern"><i class="bi bi-arrow-left"></i> Batal</a>
                <button type="submit" class="btn btn-primary-modern btn-modern"><i class="bi bi-save"></i> Simpan Perubahan</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
