<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_pelanggan'], $_POST['bayar'], $_POST['tanggal'], $_POST['kembalian'])) {
    $id_pelanggan = (int) $_POST['id_pelanggan'];
    $bayar = (int) $_POST['bayar'];
    $tanggal = $_POST['tanggal'];
    $kembalian = (int) $_POST['kembalian'];

    // Ambil semua pesanan dari pelanggan
    $query = "SELECT m.harga, p.jumlah
              FROM pesanan p
              JOIN menu m ON p.idmenu = m.idmenu
              WHERE p.id_pelanggan = $id_pelanggan";
    $result = $conn->query($query);

    $total = 0;
    if ($result->num_rows > 0) {
        while ($data = $result->fetch_assoc()) {
            $total += $data['harga'] * $data['jumlah'];
        }
    } else {
        echo "<script>alert('Pesanan tidak ditemukan untuk pelanggan ini!'); window.history.back();</script>";
        exit;
    }

    if ($bayar < $total) {
        echo "<script>alert('Uang bayar kurang!'); window.history.back();</script>";
        exit;
    }

    // Simpan transaksi dengan tanggal dan kembalian
    $insert = "INSERT INTO transaksi (id_pelanggan, total, bayar, kembalian, tanggal_transaksi)
               VALUES ($id_pelanggan, $total, $bayar, $kembalian, '$tanggal')";

    if ($conn->query($insert) === TRUE) {
        echo "<script>alert('Transaksi berhasil!'); window.location.href='kasir_transaksi.php';</script>";
    } else {
        echo "Gagal menyimpan transaksi: " . $conn->error;
    }
} else {
    echo "<script>alert('Data tidak lengkap!'); window.history.back();</script>";
}
?>
