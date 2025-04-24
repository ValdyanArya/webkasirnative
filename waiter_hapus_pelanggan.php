<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "webkasir");

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Periksa apakah id_pelanggan dikirim
if (!isset($_GET['id'])) {
    header("Location: waiter_pelanggan.php");
    exit();
}

$id_pelanggan = $_GET['id'];

// Hapus pelanggan berdasarkan ID
$sql = "DELETE FROM pelanggan WHERE id_pelanggan = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_pelanggan);

if ($stmt->execute()) {
    echo "<script>
            setTimeout(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Data pelanggan berhasil dihapus.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location = 'waiter_pelanggan.php';
                });
            }, 500);
          </script>";
} else {
    echo "<script>
            setTimeout(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Data pelanggan gagal dihapus.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location = 'waiter_pelanggan.php';
                });
            }, 500);
          </script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapus Pelanggan</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
</body>
</html>
