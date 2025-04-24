<?php
$conn = new mysqli("localhost", "root", "", "webkasir");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomor_meja = $_POST["nomor_meja"] ?? ''; // Pastikan variabel tidak kosong

    if (!empty($nomor_meja)) {
        // Cek apakah nomor meja sudah ada
        $check_stmt = $conn->prepare("SELECT * FROM meja WHERE Nomor = ?");
        $check_stmt->bind_param("s", $nomor_meja);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            // Jika nomor meja sudah ada, tampilkan notifikasi SweetAlert
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Nomor meja sudah digunakan. Gunakan nomor lain.',
                        }).then(() => {
                            window.history.back();
                        });
                    });
                  </script>";
        } else {
            // Jika belum ada, tambahkan ke database
            $stmt = $conn->prepare("INSERT INTO meja (Nomor) VALUES (?)");
            $stmt->bind_param("s", $nomor_meja);

            if ($stmt->execute()) {
                echo "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Meja berhasil ditambahkan.',
                            }).then(() => {
                                window.location.href = 'admin_meja.php';
                            });
                        });
                      </script>";
            } else {
                echo "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan saat menambahkan meja.',
                            }).then(() => {
                                window.history.back();
                            });
                        });
                      </script>";
            }

            $stmt->close();
        }

        $check_stmt->close();
    } else {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Gagal!',
                        text: 'Nomor meja tidak boleh kosong!',
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
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Meja</title>
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
    <div class="container">
        <h2>Tambah Meja</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nomor Meja</label>
                <input type="text" name="nomor_meja" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Kirim</button>
            <a href="admin_meja.php" class="btn btn-secondary w-100 mt-2">Batal</a>
        </form>
    </div>
</body>
</html>