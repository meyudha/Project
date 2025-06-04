<?php
// TODO C4-1: Ganti kredensial 'root' ini dengan user database ber-privilege minimum sebelum deploy.
$con = mysqli_connect('localhost','root','','phplogin') or
       error_log('Koneksi MySQL gagal: '.mysqli_connect_error());
?>
