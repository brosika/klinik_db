<?php
session_start();

// Hapus semua data sesi
session_unset();

// Hapus sesi
session_destroy();

// Arahkan ke halaman login setelah logout
header("Location: login.php");
exit();
?>
