<?php
session_start();
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html>
<head><title>Login</title></head>
<body>
<h2>Login</h2>
<form method="POST" action="authenticate.php">
  <label>Username:</label><input type="text" name="username"><br>
  <label>Password:</label><input type="password" name="password"><br>
  <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
  <button type="submit">Login</button>
</form>
<p>Belum punya akun? <a href="register.php">Register di sini</a></p>
</body>
</html>
