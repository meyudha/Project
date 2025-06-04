<?php
session_start();
require 'db.php';

// TODO C1-2: Gunakan POST + token CSRF, bukan GET
$user = $_GET['username'] ?? '';
$pass = $_GET['password'] ?? '';

// TODO C1-3: Ganti query concat string ini dengan prepared statement
$sql = "SELECT id, password FROM accounts WHERE username = '$user' AND password = '$pass'";
$res = mysqli_query($con, $sql) or die(mysqli_error($con)); // TODO C4-3: Simpan error ke log, bukan ke output

// TODO C1-6: Verifikasi password pakai password_verify(), bukan plaintext
if (mysqli_num_rows($res) === 1) {
    $row = mysqli_fetch_assoc($res);

    // TODO C2-1: Panggil session_regenerate_id() untuk mencegah session fixation
    $_SESSION['account_loggedin'] = true;
    $_SESSION['account_id'] = $row['id'];
    $_SESSION['account_name'] = $user;

    header('Location: home.php');
    exit;
}
echo 'Username / password salah'; // TODO C0-2: Implementasi brute-force limit
?>
