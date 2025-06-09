<?php
$con = mysqli_connect('db', '123', '123', 'phplogin');

if (!$con) {
    die('❌ Koneksi MySQL gagal: ' . mysqli_connect_error());
} else {
    echo '✅ Koneksi MySQL berhasil ke database phplogin';
}
?>
