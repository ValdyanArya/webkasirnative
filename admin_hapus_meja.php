<?php
$conn = new mysqli("localhost", "root", "", "webkasir");

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (isset($_GET["nomor_meja"])) {
    $nomor_meja = $_GET["nomor_meja"];

    // Gunakan prepared statement untuk keamanan
    $stmt = $conn->prepare("DELETE FROM meja WHERE Nomor = ?");
    $stmt->bind_param("s", $nomor_meja);

    if ($stmt->execute()) {
        echo "<script>alert('Meja berhasil dihapus!'); window.location='admin_meja.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
