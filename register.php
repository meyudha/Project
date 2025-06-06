<?php
session_start();
require 'db.php';

if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

function valid_username($user) {
    return preg_match('/^[a-zA-Z0-9_]{3,20}$/', $user);
}
function valid_password($pass) {
    return strlen($pass) >= 8 && strlen($pass) <= 64;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['username'] ?? '');
    $pass = $_POST['password'] ?? '';
    $token = $_POST['token'] ?? '';

    if (!hash_equals($_SESSION['token'], $token)) {
        die('CSRF token tidak valid.');
    }

    if (!valid_username($user)) {
        die('Username tidak valid. Gunakan huruf, angka, dan underscore (3-20 karakter).');
    }
    if (!valid_password($pass)) {
        die('Password minimal 8 karakter.');
    }

    $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

    $stmt = mysqli_prepare($con, "INSERT INTO accounts (username, password) VALUES (?, ?)");
    if (!$stmt) {
        die("Gagal menyiapkan query.");
    }
    mysqli_stmt_bind_param($stmt, "ss", $user, $hashed_password);

    if (mysqli_stmt_execute($stmt)) {
        header('Location: index.php');
        exit;
    } else {
        echo 'Gagal mendaftar. Username mungkin sudah digunakan.';
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Register</title></head>
<body>
<h2>Register</h2>
<form method="POST">
    <label>Username:</label>
    <input type="text" name="username" required><br>
    <label>Password:</label>
    <input type="password" name="password" required><br>
    <input type="hidden" name="token" value="<?php echo htmlspecialchars($_SESSION['token']); ?>">
    <button type="submit">Register</button>
</form>
</body>
</html>
