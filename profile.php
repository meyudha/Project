<?php
require 'db.php';
session_start();

$id = $_GET['id'] ?? 0; // TODO C3-2: Pastikan user hanya melihat profilnya sendiri

// TODO C1-7: Ganti query ini dengan prepared statement
$sql = "SELECT fullname, email FROM accounts WHERE id = $id";
$res = mysqli_query($con, $sql) or die(mysqli_error($con));

$row = mysqli_fetch_assoc($res);
?>
<!DOCTYPE html>
<html>
<head><title>Profil</title></head>
<body>
<h2>Profil Pengguna</h2>
<p>Nama Lengkap: <?php echo $row['fullname']; // TODO C3-3: htmlspecialchars() ?></p>
<p>Email: <?php echo $row['email']; // TODO C3-4: htmlspecialchars() ?></p>
<p><a href="home.php">Kembali</a></p>
</body>
</html>
