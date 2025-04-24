<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "root", "", "webkasir");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_menus = $_POST["nama_menu"]; // array
    $id_pelanggan = $_POST["nama_pelanggan"];
    $jumlahs = $_POST["jumlah"]; // array
    $id_meja = $_POST["nomor_meja"];
    $username = trim($_POST["username"]);

    // Ambil ID user
    $stmt = $conn->prepare("SELECT id_user FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $id_user = $user['id_user'] ?? null;
    $stmt->close();

    if ($id_user) {
        $berhasil = true;

        $stmt = $conn->prepare("INSERT INTO pesanan (idmenu, id_pelanggan, jumlah, nomor, id_user) VALUES (?, ?, ?, ?, ?)");

        for ($i = 0; $i < count($id_menus); $i++) {
            $id_menu = $id_menus[$i];
            $jumlah = $jumlahs[$i];

            // Validasi jika id_menu tidak kosong
            if (!empty($id_menu) && !empty($jumlah)) {
                $stmt->bind_param("iiiii", $id_menu, $id_pelanggan, $jumlah, $id_meja, $id_user);

                if (!$stmt->execute()) {
                    $berhasil = false;
                    break;
                }
            }
        }

        $stmt->close();

        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        if ($berhasil) {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Semua pesanan berhasil ditambahkan.'
                        }).then(() => {
                            window.location.href = 'waiter_pesanan.php';
                        });
                    });
                </script>";
        } else {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat menambahkan salah satu pesanan.'
                        }).then(() => {
                            window.history.back();
                        });
                    });
                </script>";
        }
    } else {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'User tidak ditemukan!'
                    }).then(() => {
                        window.history.back();
                    });
                });
              </script>";
    }
}

$conn->close();
?>
