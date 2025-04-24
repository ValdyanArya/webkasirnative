<?php
$conn = new mysqli("localhost", "root", "", "webkasir");

// Periksa koneksi database
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Pastikan ada parameter idmenu
if (isset($_GET["idmenu"])) {
    $idmenu = $_GET["idmenu"];

    // Cek apakah idmenu ada di database
    $check_sql = "SELECT * FROM menu WHERE idmenu = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("i", $idmenu);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Jika menu ditemukan, hapus dari database
        $delete_sql = "DELETE FROM menu WHERE idmenu = ?";
        $stmt = $conn->prepare($delete_sql);
        $stmt->bind_param("i", $idmenu);

        if ($stmt->execute()) {
            echo "<script>
                    alert('Menu berhasil dihapus!');
                    window.location = 'admin_menu.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Gagal menghapus menu!');
                    window.location = 'admin_menu.php';
                  </script>";
        }
    } else {
        echo "<script>
                alert('Menu tidak ditemukan!');
                window.location = 'admin_menu.php';
              </script>";
    }
    $stmt->close();
} else {
    echo "<script>
            alert('ID menu tidak valid!');
            window.location = 'admin_menu.php';
          </script>";
}

$conn->close();
?>