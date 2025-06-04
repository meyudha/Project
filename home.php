<?php
session_start();
require 'db.php';

// TODO C2-2: Set flag cookie Secure, HttpOnly, SameSite menggunakan ini_set atau header
if (!isset($_SESSION['account_loggedin'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><title>Home</title>
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="/css/bootstrap.min.css">
</head>
<body>
<h2>Selamat datang, <?php echo $_SESSION['account_name']; // TODO C3-1: Escape output dengan htmlspecialchars() ?></h2>
<p><a href="profile.php?id=<?php echo $_SESSION['account_id']; ?>">Lihat Profil</a></p>
<p><a href="logout.php">Logout</a></p>
<!-- jQuery & Bootstrap JS -->
<script src="/js/jquery.min.js"></script>
<script src="/js/bootstrap.bundle.min.js"></script>
</body>
</html>
