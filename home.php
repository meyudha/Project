<?php
ini_set('session.cookie_httponly', 1);       
ini_set('session.cookie_secure', 0);         
ini_set('session.cookie_samesite', 'Strict'); 

session_start();
require 'db.php';

if (!isset($_SESSION['account_loggedin'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
</head>
<body>
    <h2>Selamat datang, <?php echo htmlspecialchars($_SESSION['account_name']); ?></h2>
    <p><a href="profile.php?id=<?php echo (int)$_SESSION['account_id']; ?>">Lihat Profil</a></p>
    <p><a href="logout.php">Logout</a></p>
</body>
</html>


