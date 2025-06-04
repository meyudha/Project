<?php
session_start();
// TODO C2-3: Hapus seluruh data sesi sebelum destroy
session_destroy();
// TODO C2-4: Regenerasi ID sesi untuk menonaktifkan sesi lama
header('Location: index.php');
exit;
?>
