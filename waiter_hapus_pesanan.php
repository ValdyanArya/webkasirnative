<?php
// Aktifkan debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "webkasir");

// Periksa koneksi database
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Periksa apakah ada id_pesanan yang diterima
if (isset($_GET['id_pesanan'])) {
    $id_pesanan = $_GET['id_pesanan'];
} else {
    die("ID pesanan tidak disertakan.");
}

// Cek apakah pesanan ada di database
$query = $conn->prepare("SELECT * FROM pesanan WHERE id_pesanan = ?");
$query->bind_param("i", $id_pesanan);
$query->execute();
$result = $query->get_result();

if ($result->num_rows == 0) {
    die("Pesanan tidak ditemukan.");
}

// Hapus pesanan jika konfirmasi diterima
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Siapkan query untuk menghapus pesanan
    $delete_query = $conn->prepare("DELETE FROM pesanan WHERE id_pesanan = ?");
    $delete_query->bind_param("i", $id_pesanan);
    
    if ($delete_query->execute()) {
        // Redirect setelah penghapusan berhasil
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Pesanan berhasil dihapus.'
                    }).then(() => {
                        window.location.href = 'waiter_pesanan.php';
                    });
                });
              </script>";
    } else {
        // Tampilkan pesan error jika penghapusan gagal
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat menghapus pesanan.'
                    }).then(() => {
                        window.history.back();
                    });
                });
              </script>";
    }
    $delete_query->close();
}

// Menutup koneksi
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapus Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Hapus Pesanan</h2>
        <div class="alert alert-warning" role="alert">
            Anda yakin ingin menghapus pesanan ini?
        </div>
        <form action="waiter_hapus_pesanan.php?id_pesanan=<?= $id_pesanan ?>" method="POST">
            <button type="submit" class="btn btn-danger">Ya, Hapus Pesanan</button>
            <a href="daftar_pesanan.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
