<?php
session_start();
if (!isset($_SESSION['user'])) header('Location: login.php');
include 'koneksi.php';

$result = mysqli_query($koneksi, "SELECT * FROM pelamar");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Data Pelamar</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <h1>ApplicantTrack</h1>
        <div class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <?php if ($_SESSION['user']['role'] == 'superuser'): ?>
                <a href="user_management.php">Manajemen User</a>
            <?php endif; ?>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="container">
        <h2>Data Pelamar</h2>
        
        <a href="pelamar_form.php" class="button-link btn-secondary" style="margin-bottom: 20px;">Tambah Pelamar</a>

        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama</th><th>Lulusan</th><th>IPK</th><th>Catatan / Portfolio</th><th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['nama_pelamar']) ?></td>
                    <td><?= htmlspecialchars($row['lulusan']) ?></td>
                    <td><?= htmlspecialchars($row['ipk']) ?></td>
                    <td><?= htmlspecialchars($row['cat_porto']) ?></td>
                    <td class="action-links">
                        <a href="pelamar_form.php?id=<?= $row['id_pelamar'] ?>">Edit</a>
                        <a href="pelamar_form.php?delete=<?= $row['id_pelamar'] ?>" onclick="return confirm('Hapus data ini?')" style="color: var(--error-color);">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        </div>

    <footer class="footer">
        <p>&copy; 2025 ApplicantTrack. By: Vincent GG.</p>
    </footer>
</body>
</html>