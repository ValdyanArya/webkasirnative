<?php
session_start();

// Hapus semua session
$_SESSION = [];
session_destroy();

// Header untuk mencegah cache (agar tidak bisa diakses via tombol back)
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
header("Location: login.php");
exit;
?>
