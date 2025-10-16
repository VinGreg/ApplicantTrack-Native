<?php
session_start();
if (!isset($_SESSION['user'])) header('Location: login.php');
include 'koneksi.php';

// Hapus data
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($koneksi, "DELETE FROM pelamar WHERE id_pelamar=$id");
    header('Location: dashboard.php');
    exit;
}

// Edit data
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$edit = false;
if ($id) {
    $result = mysqli_query($koneksi, "SELECT * FROM pelamar WHERE id_pelamar=$id");
    $data = mysqli_fetch_assoc($result);
    $edit = true;
}

// Simpan data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_pelamar']);
    $lulusan = mysqli_real_escape_string($koneksi, $_POST['lulusan']);
    $ipk = floatval($_POST['ipk']);
    $cat_porto = mysqli_real_escape_string($koneksi, $_POST['cat_porto']);
    if ($edit) {
        mysqli_query($koneksi, "UPDATE pelamar SET nama_pelamar='$nama', lulusan='$lulusan', ipk='$ipk', cat_porto='$cat_porto' WHERE id_pelamar=$id");
    } else {
        mysqli_query($koneksi, "INSERT INTO pelamar (nama_pelamar, lulusan, ipk, cat_porto) VALUES ('$nama', '$lulusan', '$ipk', '$cat_porto')");
    }
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $edit ? 'Edit' : 'Tambah' ?> Data Pelamar</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2><?= $edit ? 'Edit' : 'Tambah' ?> Data Pelamar</h2>
        <form method="post">
            <label>Nama Pelamar</label>
            <input type="text" name="nama_pelamar" value="<?= $edit ? htmlspecialchars($data['nama_pelamar']) : '' ?>" required>
            <label>Lulusan</label>
            <input type="text" name="lulusan" value="<?= $edit ? htmlspecialchars($data['lulusan']) : '' ?>" required>
            <label>IPK</label>
            <input type="number" step="0.01" name="ipk" value="<?= $edit ? htmlspecialchars($data['ipk']) : '' ?>" required>
            <label>Catatan / Portfolio</label>
            <textarea name="cat_porto" required><?= $edit ? htmlspecialchars($data['cat_porto']) : '' ?></textarea>
            
            <button type="submit">Simpan</button>
            <a href="dashboard.php" class="button-link" style="background: #6c757d;">Batal</a>
        </form>
    </div>
    
    <footer class="footer">
        <p>&copy; 2025 ApplicantTrack. By: Vincent GG.</p>
    </footer>
</body>
</html>
