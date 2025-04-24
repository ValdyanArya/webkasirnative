<?php
session_start();

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "webkasir");

// Pastikan ada parameter id_user yang dikirim
if (isset($_GET['id_user'])) { // Ganti id menjadi id_user
    $id_user = $_GET['id_user'];

    // Query untuk menghapus user berdasarkan id_user
    $sql = "DELETE FROM users WHERE id_user = '$id_user'"; // Ganti id menjadi id_user

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('User berhasil dihapus!'); window.location='admin_user.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus user!'); window.location='admin_user.php';</script>";
    }
} else {
    echo "<script>alert('ID user tidak ditemukan!'); window.location='admin_user.php';</script>";
}

$conn->close();
?>
