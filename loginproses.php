<?php
session_start();
include 'config.php'; // Pastikan file koneksi database sudah benar

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validasi input tidak boleh kosong
    if (empty($username) || empty($password)) {
        echo "<script>alert('Username dan password wajib diisi!'); window.location='login.php';</script>";
        exit();
    }

    // Query untuk mendapatkan user berdasarkan username
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verifikasi password dengan password_hash
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect berdasarkan peran
            switch ($user['role']) {
                case 'administrator':
                    header("Location: admin_dashboard.php");
                    break;
                case 'waiter':
                    header("Location: waiter_dashboard.php");
                    break;
                case 'kasir':
                    header("Location: kasir_dashboard.php");
                    break;
                case 'owner':
                    header("Location: owner_dashboard.php");
                    break;
                default:
                    header("Location: login.php"); // Jika role tidak dikenali
                    break;
            }
            exit();
        } else {
            echo "<script>alert('Password salah!'); window.location='login.php';</script>";
        }
    } else {
        echo "<script>alert('Username tidak ditemukan!'); window.location='login.php';</script>";
    }
} else {
    header("Location: login.php");
    exit();
}
?>
