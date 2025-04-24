<?php
session_start();

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "webkasir");

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data user berdasarkan ID
if (isset($_GET['id_user'])) { // Ganti id menjadi id_user
    $id_user = $_GET['id_user'];
    $sql = "SELECT * FROM users WHERE id_user = '$id_user'"; // Ganti id menjadi id_user
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "<script>alert('User tidak ditemukan!'); window.location='admin_user.php';</script>";
        exit();
    }
}

// Jika tombol submit ditekan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password']; // Bisa dienkripsi dengan password_hash()
    $nama_lengkap = $_POST['nama_lengkap'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Cek apakah password diisi
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET username='$username', password='$hashed_password', nama_lengkap='$nama_lengkap', email='$email', role='$role' WHERE id_user='$id_user'"; // Ganti id menjadi id_user
    } else {
        $sql = "UPDATE users SET username='$username', nama_lengkap='$nama_lengkap', email='$email', role='$role' WHERE id_user='$id_user'"; // Ganti id menjadi id_user
    }

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('User berhasil diperbarui!'); window.location='admin_user.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui user!');</script>";
    }
}

$conn->close();
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
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit User</h2>
        <form method="POST">
            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $user['username']; ?>" required>
            </div>
            <div class="mb-3">
                <label>Password (biarkan kosong jika tidak ingin mengubah)</label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="mb-3">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" class="form-control" value="<?php echo $user['nama_lengkap']; ?>" required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo $user['email']; ?>" required>
            </div>
            <div class="mb-3">
                <label>Role</label>
                <select name="role" class="form-control">
                    <option value="administrator" <?php echo ($user['role'] == 'administrator') ? 'selected' : ''; ?>>Administrator</option>
                    <option value="waiter" <?php echo ($user['role'] == 'waiter') ? 'selected' : ''; ?>>Waiter</option>
                    <option value="kasir" <?php echo ($user['role'] == 'kasir') ? 'selected' : ''; ?>>Kasir</option>
                    <option value="owner" <?php echo ($user['role'] == 'owner') ? 'selected' : ''; ?>>Owner</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="admin_user.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>
</html>
