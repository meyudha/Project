<?php
session_start();
require 'db.php';

if (!isset($_SESSION['account_loggedin'])) {
    header('Location: index.php');
    exit;
}

$id = $_SESSION['account_id'];
$stmt = mysqli_prepare($con, "SELECT fullname, email FROM accounts WHERE id = ?");
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
<p>Nama Lengkap: <?php echo htmlspecialchars($row['fullname']); ?></p>
<p>Email: <?php echo htmlspecialchars($row['email']); ?></p>
<p><a href="home.php">Kembali</a></p>
</body>
</html>
