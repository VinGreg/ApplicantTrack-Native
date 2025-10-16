<?php
session_start();
include 'koneksi.php';

if (isset($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];
    $query = "SELECT * FROM user WHERE username='$username'";
    $result = mysqli_query($koneksi, $query);
    $user = mysqli_fetch_assoc($result);
    if ($user && password_verify($password, $user['pwd'])) {
        $_SESSION['user'] = [
            'id_user' => $user['id_user'],
            'username' => $user['username'],
            'role' => $user['role']
        ];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Username atau password salah!';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - ApplicantTrack</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-box">
        <h2>Login</h2>
        <form method="post">
            <label>Username</label>
            <input type="text" name="username" required>
            <label>Password</label>
            <input type="password" name="password" required>
            <button type="submit">Login</button>
        </form>
        <?php if ($error) echo "<p class='error'>$error</p>"; ?>
    </div>
    
    <footer class="footer">
        <p>&copy; 2025 ApplicantTrack. By: Vincent GG.</p>
    </footer>
</body>
</html>