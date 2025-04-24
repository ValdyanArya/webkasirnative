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

// Cek jika form dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = trim($_POST["nama"]);
    $harga = trim($_POST["harga"]);
    $jenis = trim($_POST["jenis"]);
    $gambar = isset($_FILES["gambar"]) ? $_FILES["gambar"] : null;

    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";

    // Cek apakah nama menu sudah ada
    $check_sql = "SELECT * FROM menu WHERE nama = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("s", $nama);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Nama menu sudah digunakan. Silakan gunakan nama lain.',
                    }).then(() => {
                        window.history.back();
                    });
                });
              </script>";
    } else {
        // Cek apakah gambar diunggah
        if ($gambar && $gambar["error"] === UPLOAD_ERR_OK) {
            $upload_dir = "uploads/menu/";

            // Buat folder jika belum ada
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            // Dapatkan nama file dan path
            $gambar_name = basename($gambar["name"]);
            $gambar_path = $upload_dir . $gambar_name;

            // Pindahkan file ke folder
            if (move_uploaded_file($gambar["tmp_name"], $gambar_path)) {
                // Simpan ke database jika upload berhasil
                $sql = "INSERT INTO menu (nama, harga, jenis, gambar) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssss", $nama, $harga, $jenis, $gambar_name);

                if ($stmt->execute()) {
                    echo "<script>
                            document.addEventListener('DOMContentLoaded', function() {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'Menu berhasil ditambahkan.'
                                }).then(() => {
                                    window.location.href = 'waiter_menu.php';
                                });
                            });
                          </script>";
                } else {
                    echo "<script>
                            document.addEventListener('DOMContentLoaded', function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: 'Terjadi kesalahan saat menambahkan menu.'
                                }).then(() => {
                                    window.history.back();
                                });
                            });
                          </script>";
                }
            } else {
                echo "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Gagal mengupload gambar.'
                            }).then(() => {
                                window.history.back();
                            });
                        });
                      </script>";
            }
        } else {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Gagal!',
                            text: 'Silakan pilih gambar sebelum mengirim!'
                        }).then(() => {
                            window.history.back();
                        });
                    });
                  </script>";
        }
    }
    $stmt->close();
}
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Menu</title>
    <!-- Bootstrap CSS -->
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- SweetAlert JS -->
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
        <h2>Tambah Menu Baru</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Menu</label>
                <input type="text" class="form-control" id="nama" name="nama" required>
            </div>
            <div class="mb-3">
                <label for="harga" class="form-label">Harga</label>
                <input type="number" class="form-control" id="harga" name="harga" required>
            </div>
            <div class="mb-3">
                <label for="jenis" class="form-label">Jenis</label>
                <select class="form-control" id="jenis" name="jenis" required>
                    <option value="Makanan">Makanan</option>
                    <option value="Minuman">Minuman</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="gambar" class="form-label">Upload Gambar</label>
                <input type="file" class="form-control" id="gambar" name="gambar" required>
            </div>
            <button type="submit" class="btn btn-primary">Kirim</button>
            <a href="waiter_menu.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
