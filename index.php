<?php
// index.php
session_start();
?>
<!DOCTYPE html>
<html>
<head><title>Login</title></head>
<body>
<h2>Login</h2>
<form method="GET" action="authenticate.php"><!-- TODO C1-1: Ganti GET menjadi POST dan tambahkan hidden token CSRF -->
  <label>Username:</label><input type="text" name="username"><br>
  <label>Password:</label><input type="password" name="password"><br>
  <button type="submit">Login</button>
</form>
<!-- TODO C0-1: Arahkan semua traffic ke HTTPS dan pertimbangkan header HSTS -->
<p>Belum punya akun? <a href="register.php">Register di sini</a></p>
</body>
</html>
