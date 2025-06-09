<?php
session_start();
require 'db.php';

if (!isset($_SESSION['account_loggedin'])) {
    header('Location: index.php');
    exit;
}

$id = $_SESSION['account_id'];
$stmt = mysqli_prepare($con, "SELECT username, password FROM accounts WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html>
<head><title>Profil</title></head>
<body>
<h2>Profil Pengguna</h2>
<p>Username: <?php echo htmlspecialchars($row['username']); ?></p>
<p>Password (hashed): <?php echo htmlspecialchars($row['password']); ?></p>
<p><a href="home.php">Kembali</a></p>
</body>
</html>
