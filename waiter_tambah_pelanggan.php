<?php
include 'config.php'; // Pastikan file koneksi ada

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_pelanggan = htmlspecialchars($_POST['nama_pelanggan']);
    $jenis_kelamin = htmlspecialchars($_POST['jenis_kelamin']);
    $no_hp = htmlspecialchars($_POST['no_hp']);
    $alamat = htmlspecialchars($_POST['alamat']);

    $query = "INSERT INTO pelanggan (nama_pelanggan, jenis_kelamin, no_hp, alamat) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $nama_pelanggan, $jenis_kelamin, $no_hp, $alamat);

    if ($stmt->execute()) {
        // Redirect langsung ke halaman daftar pelanggan
        header("Location: waiter_pelanggan.php");
        exit;
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan, coba lagi.',
                    confirmButtonText: 'OK'
                });
              </script>";
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pelanggan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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
        }
        .container {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            color: #333;
            width: 400px;
        }
        h2 {
            color: #1e3c72;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }
        .form-control {
            border-radius: 10px;
            border: 1px solid #ddd;
            padding: 10px;
        }
        .btn-primary {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            transition: 0.3s;
            width: 100%;
        }
        .btn-primary:hover {
            opacity: 0.9;
            transform: scale(1.05);
        }
        .btn-secondary {
            background: #6c757d;
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            transition: 0.3s;
            width: 100%;
        }
        .btn-secondary:hover {
            opacity: 0.9;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-user-edit"></i> Tambah Pelanggan</h2>
        <form action="" method="POST">
            <div class="mb-3">
                <label class="form-label">Nama Pelanggan</label>
                <input type="text" name="nama_pelanggan" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="form-control" required>
                    <option value="">Pilih</option>
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">No HP</label>
                <input type="text" name="no_hp" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Alamat</label>
                <textarea name="alamat" class="form-control" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
            <a href="waiter_pelanggan.php" class="btn btn-secondary mt-2"><i class="fas fa-times"></i> Batal</a>
        </form>
    </div>
</body>
</html>
