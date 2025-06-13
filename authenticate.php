<?php
session_start();
require 'db.php';

if (!isset($_POST['token']) || $_POST['token'] !== ($_SESSION['token'] ?? '')) {
    die('Token CSRF tidak valid.');
}

$user = trim($_POST['username'] ?? '');
$pass = $_POST['password'] ?? '';

if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $user)) {
    die('Username tidak valid. Hanya boleh huruf, angka, dan garis bawah (3-20 karakter).');
}

if (strlen($pass) < 6) {
    die('Password minimal 6 karakter.');
}

if (!isset($_SESSION['login_attempt'])) {
    $_SESSION['login_attempt'] = 0;
    $_SESSION['last_attempt_time'] = time();
}
if ($_SESSION['login_attempt'] >= 5) {
    $elapsed = time() - $_SESSION['last_attempt_time'];
    if ($elapsed < 300) {
        die('Terlalu banyak percobaan login. Coba lagi dalam ' . (300 - $elapsed) . ' detik.');
    } else {
        $_SESSION['login_attempt'] = 0;
        $_SESSION['last_attempt_time'] = time();
    }
}

$stmt = mysqli_prepare($con, "SELECT id, username, password FROM accounts WHERE username = ?");
mysqli_stmt_bind_param($stmt, "s", $user);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    // Verifikasi password hash
    if (password_verify($pass, $row['password'])) {
        session_regenerate_id(true); 
        $_SESSION['account_loggedin'] = true;
        $_SESSION['account_id'] = $row['id'];
        $_SESSION['account_name'] = $row['username'];
        $_SESSION['login_attempt'] = 0;
        header('Location: home.php');
        exit;
    }
}

$_SESSION['login_attempt']++;
$_SESSION['last_attempt_time'] = time();
echo 'Username atau password salah.';
