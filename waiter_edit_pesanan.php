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

if (isset($_GET['id_pesanan'])) {
    $id_pesanan = $_GET['id_pesanan'];
    echo "ID Pesanan: " . $id_pesanan;  // Menampilkan ID Pesanan untuk memastikan diterima dengan benar
} else {
    die("ID pesanan tidak disertakan.");
}

// Ambil data pesanan berdasarkan id_pesanan
$query_pesanan = $conn->prepare("SELECT * FROM pesanan WHERE id_pesanan = ?");
$query_pesanan->bind_param("i", $id_pesanan);
$query_pesanan->execute();
$pesanan_result = $query_pesanan->get_result();

// Jika pesanan ditemukan
if ($pesanan_result->num_rows > 0) {
    $pesanan = $pesanan_result->fetch_assoc();
} else {
    die("Pesanan tidak ditemukan.");
}

// Ambil daftar menu
$menu_query = $conn->query("SELECT idmenu, Nama FROM menu");
$menus = $menu_query->fetch_all(MYSQLI_ASSOC);

// Ambil daftar pelanggan
$pelanggan_query = $conn->query("SELECT id_pelanggan, nama_pelanggan FROM pelanggan");
$pelanggans = $pelanggan_query->fetch_all(MYSQLI_ASSOC);

// Ambil daftar nomor meja
$meja_query = $conn->query("SELECT Nomor FROM meja WHERE status = '1'");
$mejas = $meja_query->fetch_all(MYSQLI_ASSOC);

// Cek jika form dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_menu = $_POST["nama_menu"];
    $id_pelanggan = $_POST["nama_pelanggan"];
    $jumlah = $_POST["jumlah"];
    $id_meja = $_POST["nomor_meja"];
    $username = isset($_POST["username"]) ? trim($_POST["username"]) : '';  // Cek jika username ada

    // Ambil id_user dari tabel users berdasarkan username
    $stmt = $conn->prepare("SELECT id_user FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $id_user = $user['id_user'] ?? null;  // Gunakan id_user jika kolomnya memang id_user
    $stmt->close();

    if ($id_user) {
        // Update data pesanan
        $stmt = $conn->prepare("UPDATE pesanan SET idmenu = ?, id_pelanggan = ?, jumlah = ?, nomor = ?, id_user = ? WHERE id_pesanan = ?");
        $stmt->bind_param("iiiiii", $id_menu, $id_pelanggan, $jumlah, $id_meja, $id_user, $id_pesanan);

        if ($stmt->execute()) {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Pesanan berhasil diperbarui.'
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
                            text: 'Terjadi kesalahan saat memperbarui pesanan.'
                        }).then(() => {
                            window.history.back();
                        });
                    });
                  </script>";
        }
        $stmt->close();
    } else {
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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url('css/bg.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: 'Arial', sans-serif;
        }

        .container {
            max-width: 500px;
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }

        h2 {
            text-align: center;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #007bff, #0056b3);
            border: none;
        }

        .btn-secondary {
            background: #6c757d;
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #0056b3, #003f7f);
        }

        .btn-secondary:hover {
            background: #5a6268;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2>Edit Pesanan</h2>
        <form action="waiter_edit_pesanan.php?id_pesanan=<?= $id_pesanan ?>" method="POST">
            <div class="mb-3">
                <label for="nama_menu" class="form-label">Nama Menu</label>
                <select class="form-control" id="nama_menu" name="nama_menu" required>
                    <option value="">Pilih Menu</option>
                    <?php foreach ($menus as $menu) : ?>
                        <option value="<?= htmlspecialchars($menu['idmenu']); ?>" <?= $menu['idmenu'] == $pesanan['idmenu'] ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($menu['Nama']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="nama_pelanggan" class="form-label">Nama Pelanggan</label>
                <select class="form-control" id="nama_pelanggan" name="nama_pelanggan" required>
                    <option value="">Pilih Pelanggan</option>
                    <?php foreach ($pelanggans as $pelanggan) : ?>
                        <option value="<?= htmlspecialchars($pelanggan['id_pelanggan']); ?>" <?= $pelanggan['id_pelanggan'] == $pesanan['id_pelanggan'] ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($pelanggan['nama_pelanggan']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="jumlah" class="form-label">Jumlah</label>
                <input type="number" class="form-control" id="jumlah" name="jumlah" value="<?= $pesanan['jumlah']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="nomor_meja" class="form-label">Nomor Meja</label>
                <select class="form-control" id="nomor_meja" name="nomor_meja" required>
                    <option value="">Pilih Meja</option>
                    <?php foreach ($mejas as $meja) : ?>
                        <option value="<?= htmlspecialchars($meja['Nomor']); ?>" <?= $meja['Nomor'] == $pesanan['nomor'] ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($meja['Nomor']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?= isset($pesanan['username']) ? htmlspecialchars($pesanan['username']) : ''; ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Perbarui</button>
            <a href="waiter_pesanan.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>