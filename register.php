<?php
require 'db.php';
session_start();
if (isset($_POST['username'], $_POST['password'])) {
    $user = $_POST['username']; // TODO C1-4: Validasi input & gunakan prepared statement
    $pass = $_POST['password']; // TODO C1-5: Simpan password dengan password_hash() bukan plaintext
    $sql = "INSERT INTO accounts (username, password) VALUES ('$user', '$pass')"; // Rentan SQLi
    mysqli_query($con, $sql) or die(mysqli_error($con)); // TODO C4-2: Jangan tampilkan error MySQL ke user
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><title>Register</title></head>
<body>
<h2>Register</h2>
<form method="POST">
    <label>Username:</label><input type="text" name="username"><br>
    <label>Password:</label><input type="password" name="password"><br>
    <button type="submit">Register</button>
</form>
</body>
</html>
