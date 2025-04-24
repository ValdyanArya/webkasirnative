<?php
$conn = new mysqli("localhost", "root", "", "webkasir");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Pastikan ada parameter ID
if (isset($_GET["idmenu"])) { // Ganti id menjadi idmenu
    $idmenu = $_GET["idmenu"];
    $sql = "SELECT * FROM menu WHERE idmenu = $idmenu"; // Ganti id menjadi idmenu
    $result = $conn->query($sql);
    $menu = $result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST["nama"];
    $harga = $_POST["harga"];
    $jenis = $_POST["jenis"];
    $gambar_lama = $_POST["gambar_lama"];

    // Cek apakah ada file gambar baru yang diunggah
    if (!empty($_FILES["gambar"]["name"])) {
        $gambar = $_FILES["gambar"]["name"];
        $target_dir = "uploads/menu/";
        $target_file = $target_dir . basename($gambar);

        // Hapus gambar lama jika ada
        if (file_exists($target_dir . $gambar_lama)) {
            unlink($target_dir . $gambar_lama);
        }

        // Pindahkan file baru ke folder uploads/menu
        if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
            $sql = "UPDATE menu SET nama='$nama', harga='$harga', jenis='$jenis', gambar='$gambar' WHERE idmenu=$idmenu"; // Ganti id menjadi idmenu
        } else {
            echo "<script>alert('Gagal mengupload gambar!');</script>";
        }
    } else {
        // Jika tidak ada gambar baru, update data tanpa mengganti gambar
        $sql = "UPDATE menu SET nama='$nama', harga='$harga', jenis='$jenis' WHERE idmenu=$idmenu"; // Ganti id menjadi idmenu
    }

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Menu berhasil diperbarui!'); window.location='admin_menu.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        /* Import Google Font */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

        /* Global Styles */
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
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .form-label {
            font-weight: 500;
            color: #34495e;
        }

        .form-control {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            font-size: 14px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 5px rgba(52, 152, 219, 0.5);
            outline: none;
        }

        .btn {
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 500;
            border-radius: 8px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-primary {
            background-color: #3498db;
            border: none;
        }

        .btn-primary:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: #95a5a6;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #7f8c8d;
            transform: translateY(-2px);
        }

        img {
            border-radius: 8px;
            margin-top: 10px;
            border: 1px solid #ddd;
            padding: 5px;
        }

        /* File Input Custom Style */
        input[type="file"] {
            display: none;
        }

        .custom-file-upload {
            border: 2px dashed #3498db;
            border-radius: 8px;
            padding: 10px;
            text-align: center;
            cursor: pointer;
            background-color: #f9f9f9;
            color: #3498db;
            transition: background-color 0.3s ease;
        }

        .custom-file-upload:hover {
            background-color: #e8f4fd;
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        /* CSS Kustom di atas bisa ditempatkan di sini */
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2>Edit Menu</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" name="nama" value="<?= $menu["nama"] ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Harga</label>
                <input type="number" name="harga" value="<?= $menu["harga"] ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Jenis</label>
                <select name="jenis" class="form-control" required>
                    <option value="Makanan" <?= $menu["jenis"] == "Makanan" ? "selected" : "" ?>>Makanan</option>
                    <option value="Minuman" <?= $menu["jenis"] == "Minuman" ? "selected" : "" ?>>Minuman</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Gambar Saat Ini</label><br>
                <img src="uploads/menu/<?= $menu['gambar'] ?>" width="100"><br>
                <input type="hidden" name="gambar_lama" value="<?= $menu['gambar'] ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Upload Gambar Baru</label>
                <label for="gambar" class="custom-file-upload">
                    <i class="fas fa-upload"></i> Pilih Gambar
                </label>
                <input type="file" name="gambar" id="gambar" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="admin_menu.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>

    <!-- Font Awesome untuk ikon -->
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>

</html>