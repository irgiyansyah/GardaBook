<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
include 'db.php';

$limit = 8;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$orderBy = $_GET['sort'] ?? 'id';
$allowed = ['id', 'judul', 'tahun'];
if (!in_array($orderBy, $allowed)) $orderBy = 'id';

$where = '';
if (isset($_GET['cari']) && $_GET['cari'] != '') {
    $cari = $conn->real_escape_string($_GET['cari']);
    $where = "WHERE judul LIKE '%$cari%' OR penulis LIKE '%$cari%'";
}

$totalQuery = $conn->query("SELECT COUNT(*) as count FROM buku $where");
$total = $totalQuery->fetch_assoc()['count'];
$pages = ceil($total / $limit);

$sql = "SELECT * FROM buku $where ORDER BY $orderBy ASC LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>GardaBook</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #1e293b;
            --primary-light: #334155;
            --accent-blue: #3b82f6;
            --accent-green: #10b981;
            --accent-red: #ef4444;
            --accent-yellow: #f59e0b;
            --bg-light: #f8fafc;
            --text-muted: #64748b;
            --border-radius: 16px;
            --shadow-soft: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-medium: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            min-height: 100vh;
        }

        .navbar-custom {
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-medium);
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 15px 30px;
            margin-top: 20px;
        }

        .navbar-brand {
            font-weight: 600;
            color: var(--primary-color) !important;
            font-size: 1.25rem;
        }

        .nav-link {
            font-weight: 500;
            color: var(--text-muted) !important;
            transition: color 0.2s ease;
            border-radius: 8px;
            padding: 8px 16px !important;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
            background-color: rgba(30, 41, 59, 0.05);
        }

        /* Modern Button Styles */
        .btn-modern {
            position: relative;
            font-weight: 500;
            font-size: 0.875rem;
            padding: 10px 20px;
            border-radius: var(--border-radius);
            border: none;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            overflow: hidden;
        }

        .btn-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-modern:hover::before {
            left: 100%;
        }

        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
            text-decoration: none;
        }

        .btn-modern:active {
            transform: translateY(0);
        }

        /* Primary Button */
        .btn-primary-modern {
            background: linear-gradient(135deg, var(--accent-blue) 0%, #2563eb 100%);
            color: white;
            box-shadow: var(--shadow-soft);
        }

        .btn-primary-modern:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
        }

        /* Success Button */
        .btn-success-modern {
            background: linear-gradient(135deg, var(--accent-green) 0%, #059669 100%);
            color: white;
            box-shadow: var(--shadow-soft);
        }

        .btn-success-modern:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: white;
        }

        /* Danger Button */
        .btn-danger-modern {
            background: linear-gradient(135deg, var(--accent-red) 0%, #dc2626 100%);
            color: white;
            box-shadow: var(--shadow-soft);
        }

        .btn-danger-modern:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: white;
        }

        /* Warning Button */
        .btn-warning-modern {
            background: linear-gradient(135deg, var(--accent-yellow) 0%, #d97706 100%);
            color: white;
            box-shadow: var(--shadow-soft);
        }

        .btn-warning-modern:hover {
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
            color: white;
        }

        /* Small Button Variant */
        .btn-modern.btn-sm {
            padding: 8px 16px;
            font-size: 0.8rem;
            border-radius: 12px;
        }

        /* Icon Button */
        .btn-icon {
            width: 40px;
            height: 40px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }

        .btn-icon.btn-sm {
            width: 32px;
            height: 32px;
            border-radius: 10px;
        }

        /* Search Form */
        .search-form {
            position: relative;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .search-input {
            padding: 10px 16px;
            border: 1px solid rgba(30, 41, 59, 0.1);
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            font-size: 0.875rem;
            transition: all 0.2s ease;
            width: 200px;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--accent-blue);
            background: rgba(255, 255, 255, 0.95);
            transform: translateY(-1px);
            box-shadow: var(--shadow-soft);
        }

        /* Card Styles */
        .card {
            border: none;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow-soft);
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-medium);
        }

        .card-img-top {
            height: 250px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .card:hover .card-img-top {
            transform: scale(1.05);
        }

        .card-body {
            padding: 20px;
        }

        .card-title {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 12px;
        }

        .card-text {
            color: var(--text-muted);
            line-height: 1.5;
        }

        .card-footer {
            background: rgba(248, 250, 252, 0.8);
            border-top: 1px solid rgba(30, 41, 59, 0.1);
            padding: 16px 20px;
        }

        /* Pagination */
        .pagination .page-link {
            border: none;
            border-radius: 12px;
            color: var(--text-muted);
            background: rgba(255, 255, 255, 0.8);
            margin: 0 4px;
            padding: 10px 16px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .pagination .page-link:hover {
            color: var(--primary-color);
            background: rgba(255, 255, 255, 0.95);
            transform: translateY(-2px);
            box-shadow: var(--shadow-soft);
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, var(--accent-blue) 0%, #2563eb 100%);
            color: white;
            box-shadow: var(--shadow-soft);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-muted);
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .col-md-3 {
            animation: fadeInUp 0.6s ease forwards;
        }

        .col-md-3:nth-child(1) { animation-delay: 0.1s; }
        .col-md-3:nth-child(2) { animation-delay: 0.2s; }
        .col-md-3:nth-child(3) { animation-delay: 0.3s; }
        .col-md-3:nth-child(4) { animation-delay: 0.4s; }
    </style>
</head>
<body>
<div class="container">
    <nav class="navbar navbar-expand-lg navbar-custom mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><i class="bi bi-book-half"></i> GardaBook</a>
            <div class="collapse navbar-collapse justify-content-center">
                <ul class="navbar-nav mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Tentang</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Layanan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Kontak</a></li>
                </ul>
            </div>
            <div class="d-flex align-items-center gap-3">
                <form method="GET" class="search-form">
                    <input type="text" name="cari" class="search-input" placeholder="Cari buku..." value="<?= isset($_GET['cari']) ? htmlspecialchars($_GET['cari']) : '' ?>">
                    <button class="btn-modern btn-primary-modern btn-icon btn-sm" type="submit"><i class="bi bi-search"></i></button>
                </form>
                <a href="logout.php" class="btn-modern btn-danger-modern btn-sm"><i class="bi bi-box-arrow-right"></i> Logout</a>
            </div>
        </div>
    </nav>
    <div class="mb-3 text-end">
        <a href="tambah.php" class="btn-modern btn-success-modern"><i class="bi bi-plus-circle"></i> Tambah Buku</a>
    </div>
    <div class="row">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        <img src="cover/<?= $row['cover'] ?? 'default.jpg' ?>" class="card-img-top" alt="Cover buku">
                        <div class="card-body">
                            <h6 class="card-title"><?= htmlspecialchars($row['judul']) ?></h6>
                            <p class="card-text text-muted small">
                                Penulis: <?= htmlspecialchars($row['penulis']) ?><br>
                                Tahun: <?= $row['tahun'] ?>
                            </p>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <a href="edit.php?id=<?= $row['id'] ?>" class="btn-modern btn-warning-modern btn-icon btn-sm" title="Edit"><i class="bi bi-pencil-fill"></i></a>
                           <a href="hapus.php?id=<?= $row['id'] ?>" class="btn-modern btn-danger-modern btn-icon btn-sm btn-delete" title="Hapus"><i class="bi bi-trash-fill"></i></a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="empty-state">
                    <i class="bi bi-book"></i>
                    <h5>Buku tidak ditemukan</h5>
                    <p>Tidak ada buku yang sesuai dengan pencarian Anda.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <nav class="d-flex justify-content-center mt-4">
        <ul class="pagination">
            <?php for ($i = 1; $i <= $pages; $i++): ?>
                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>&sort=<?= $orderBy ?><?= isset($cari) ? "&cari=$cari" : '' ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const deleteButtons = document.querySelectorAll(".btn-delete");

        deleteButtons.forEach(function (btn) {
            btn.addEventListener("click", function (e) {
                e.preventDefault(); // hentikan link langsung

                const href = this.getAttribute("href");

                Swal.fire({
                    title: 'Hapus Buku?',
                    text: "Data ini akan dihapus secara permanen.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    backdrop: true,
                    allowOutsideClick: false,
                    showClass: {
                        popup: 'swal2-show animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = href;
                    }
                });
            });
        });
    });
</script>

</body>
</html>