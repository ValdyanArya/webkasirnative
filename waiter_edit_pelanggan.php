<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "webkasir");

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data pelanggan berdasarkan ID
if (!isset($_GET['id'])) {
    header("Location: waiter_pelanggan.php");
    exit();
}

$id_pelanggan = $_GET['id'];
$sql = "SELECT * FROM pelanggan WHERE id_pelanggan = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_pelanggan);
$stmt->execute();
$result = $stmt->get_result();
$pelanggan = $result->fetch_assoc();

if (!$pelanggan) {
    header("Location: waiter_pelanggan.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_pelanggan = $_POST['nama_pelanggan'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $no_hp = $_POST['no_hp'];
    $alamat = $_POST['alamat'];
    
    // Update data pelanggan
    $sql_update = "UPDATE pelanggan SET nama_pelanggan=?, jenis_kelamin=?, no_hp=?, alamat=? WHERE id_pelanggan=?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssssi", $nama_pelanggan, $jenis_kelamin, $no_hp, $alamat, $id_pelanggan);
    
    if ($stmt_update->execute()) {
        header("Location: waiter_pelanggan.php");
        exit();
    } else {
        echo "<script>
                alert('Gagal memperbarui data!');
                window.location='waiter_pelanggan.php';
              </script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pelanggan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
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
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            color: #333;
        }

        h2 {
            color: #1e3c72;
            font-weight: bold;
            margin-bottom: 20px;
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
        }

        .btn-secondary:hover {
            opacity: 0.9;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2><i class="fas fa-user-edit"></i> Edit Pelanggan</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nama Pelanggan</label>
                <input type="text" name="nama_pelanggan" class="form-control" value="<?php echo htmlspecialchars($pelanggan['nama_pelanggan']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="form-control" required>
                    <option value="Laki-laki" <?php if ($pelanggan['jenis_kelamin'] == 'Laki-laki') echo 'selected'; ?>>Laki-laki</option>
                    <option value="Perempuan" <?php if ($pelanggan['jenis_kelamin'] == 'Perempuan') echo 'selected'; ?>>Perempuan</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Nomor HP</label>
                <input type="text" name="no_hp" class="form-control" value="<?php echo htmlspecialchars($pelanggan['no_hp']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Alamat</label>
                <textarea name="alamat" class="form-control" required><?php echo htmlspecialchars($pelanggan['alamat']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
            <a href="waiter_pelanggan.php" class="btn btn-secondary"><i class="fas fa-times"></i> Batal</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
