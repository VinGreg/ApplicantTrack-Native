<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'superuser') {
    header('Location: dashboard.php');
    exit;
}
include 'koneksi.php';

// Hapus user
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($koneksi, "DELETE FROM user WHERE id_user=$id");
    header('Location: user_management.php');
    exit;
}

// Edit user
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$edit = false;
if ($id) {
    $result = mysqli_query($koneksi, "SELECT * FROM user WHERE id_user=$id");
    $data = mysqli_fetch_assoc($result);
    $edit = true;
}

// Simpan user
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $role = $_POST['role'];
    if ($edit) {
        if (!empty($_POST['pwd'])) {
            $pwd = password_hash($_POST['pwd'], PASSWORD_DEFAULT);
            mysqli_query($koneksi, "UPDATE user SET username='$username', pwd='$pwd', role='$role' WHERE id_user=$id");
        } else {
            mysqli_query($koneksi, "UPDATE user SET username='$username', role='$role' WHERE id_user=$id");
        }
    } else {
        $pwd = password_hash($_POST['pwd'], PASSWORD_DEFAULT);
        mysqli_query($koneksi, "INSERT INTO user (username, pwd, role) VALUES ('$username', '$pwd', '$role')");
    }
    header('Location: user_management.php');
    exit;
}

// Tampilkan user
$result = mysqli_query($koneksi, "SELECT * FROM user");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manajemen User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <h1>ApplicantTrack</h1>
        <div class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="user_management.php">Manajemen User</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    
    <div class="container">
        <h2>Manajemen User</h2>
        <form method="post">
            <h3> Tambah/Edit User</h3>
            <label>Username</label>
            <input type="text" name="username" value="<?= $edit ? htmlspecialchars($data['username']) : '' ?>" required>
            <label>Password <?= $edit ? '(isi jika ingin ganti)' : '' ?></label>
            <input type="password" name="pwd" <?= $edit ? '' : 'required' ?>>
            <label>Role</label>
            <select name="role" required>
                <option value="admin" <?= $edit && $data['role']=='admin' ? 'selected' : '' ?>>Admin</option>
                <option value="superuser" <?= $edit && $data['role']=='superuser' ? 'selected' : '' ?>>Superuser</option>
            </select>
            <button type="submit">Simpan</button>
            <a href="user_management.php" class="button-link" style="background: #6c757d;">Batal</a>
        </form>
        
        <h3>Daftar User</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Username</th><th>Role</th><th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['role']) ?></td>
                    <td class="action-links">
                        <a href="user_management.php?id=<?= $row['id_user'] ?>">Edit</a>
                        <a href="user_management.php?delete=<?= $row['id_user'] ?>" onclick="return confirm('Hapus user ini?')" style="color: var(--error-color);">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="dashboard.php">Kembali ke Dashboard</a>
    </div>

    <footer class="footer">
        <p>&copy; 2025 ApplicantTrack. By: Vincent GG.</p>
    </footer>
</body>
</html>
