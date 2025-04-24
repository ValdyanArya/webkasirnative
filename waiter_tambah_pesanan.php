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

// Cek jika form dikirime
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_menu = $_POST["nama_menu"];
    $id_pelanggan = $_POST["nama_pelanggan"];
    $jumlah = $_POST["jumlah"];
    $id_meja = $_POST["nomor_meja"];
    $username = trim($_POST["username"]);

    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";

    // Ambil id_user dari tabel users berdasarkan username
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $id_user = $user['id'] ?? null;
    $stmt->close();

    if ($id_user) {
        // Insert data ke tabel pesanan
        $stmt = $conn->prepare("INSERT INTO pesanan (idmenu, id_pelanggan, jumlah, nomor, id_user) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiiii", $id_menu, $id_pelanggan, $jumlah, $id_meja, $id_user);


        if ($stmt->execute()) {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Pesanan berhasil ditambahkan.'
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
                            text: 'Terjadi kesalahan saat menambahkan pesanan.'
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

// Ambil daftar menu
$menu_query = $conn->query("SELECT idmenu, nama FROM menu");
$menus = $menu_query->fetch_all(MYSQLI_ASSOC);

// Ambil daftar pelanggan
$pelanggan_query = $conn->query("SELECT id_pelanggan, nama_pelanggan FROM pelanggan");
$pelanggans = $pelanggan_query->fetch_all(MYSQLI_ASSOC);

// Ambil daftar nomor meja
$meja_query = $conn->query("SELECT Nomor FROM meja where status = 'Tersedia'");
$mejas = $meja_query->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pesanan</title>
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
        <h2>Tambah Pesanan</h2>
        <form action="proses_pesanan.php" method="POST" id="orderForm">
            <div id="menuContainer">
                <div class="menu-group mb-3">
                    <label class="form-label">Menu</label>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <select name="nama_menu[]" class="form-control" required>
                                <option value="">Pilih Menu</option>
                                <?php foreach ($menus as $menu): ?>
                                    <option value="<?= $menu['idmenu'] ?>"><?= $menu['nama'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <input type="number" class="form-control" name="jumlah[]" placeholder="Jumlah" required>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger remove-menu">-</button>
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-success mb-3" id="addMenuBtn">+ Tambah Menu</button>

            <div class="mb-3">
                <label for="nama_pelanggan" class="form-label">Nama Pelanggan</label>
                <select class="form-control" id="nama_pelanggan" name="nama_pelanggan" required>
                    <option value="">Pilih Pelanggan</option>
                    <?php foreach ($pelanggans as $pelanggan): ?>
                        <option value="<?= htmlspecialchars($pelanggan['id_pelanggan']); ?>">
                            <?= htmlspecialchars($pelanggan['nama_pelanggan']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="nomor_meja" class="form-label">Nomor Meja</label>
                <select class="form-control" id="nomor_meja" name="nomor_meja" required>
                    <option value="">Pilih Meja</option>
                    <?php foreach ($mejas as $meja): ?>
                        <option value="<?= htmlspecialchars($meja['Nomor']); ?>"><?= htmlspecialchars($meja['Nomor']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>

            <button type="submit" class="btn btn-primary">Kirim</button>
            <a href="waiter_pesanan.php" class="btn btn-secondary">Batal</a>
        </form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById("addMenuBtn").addEventListener("click", function () {
            const container = document.getElementById("menuContainer");
            const group = document.querySelector(".menu-group");
            const clone = group.cloneNode(true);

            // Kosongkan input baru
            clone.querySelector("select").value = "";
            clone.querySelector("input").value = "";

            container.appendChild(clone);
        });

        document.addEventListener("click", function (e) {
            if (e.target && e.target.classList.contains("remove-menu")) {
                const allGroups = document.querySelectorAll(".menu-group");
                if (allGroups.length > 1) {
                    e.target.closest(".menu-group").remove();
                }
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>