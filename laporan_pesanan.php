<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "webkasir");

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari tabel pesanan dan lakukan JOIN dengan tabel menu, pelanggan, dan users
$sql = "
    SELECT 
        pesanan.id_pesanan, 
        pesanan.idmenu, 
        pesanan.id_pelanggan, 
        pesanan.jumlah, 
        pesanan.id_user, 
        pesanan.nomor,
        menu.nama AS menu_nama,
        pelanggan.nama_pelanggan,
        users.username
    FROM pesanan
    JOIN menu ON pesanan.idmenu = menu.idmenu
    JOIN pelanggan ON pesanan.id_pelanggan = pelanggan.id_pelanggan
    JOIN users ON pesanan.id_user = users.id_user
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pesanan</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        /* Background gradient lebih modern */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: #ffffff;
            color: #000;
        }

        .container {
            padding: 30px;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body * {
                visibility: hidden;
            }

            .container,
            .container * {
                visibility: visible;
            }

            .container {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <div class="container my-5">
        <h2 class="text-center fw-bold mb-4">Laporan Pesanan</h2>

        <!-- Form filter tanggal -->
        <form method="GET" class="row mb-4 no-print">
            <div class="col-md-4">
                <input type="date" name="tanggal" class="form-control" required>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Tampilkan</button>
            </div>
        </form>

        <!-- Tombol Cetak -->
        <div class="mb-3 no-print">
            <button onclick="window.print()" class="btn btn-success">
                <i class="fas fa-print"></i> Cetak Laporan
            </button>
        </div>

        <!-- Tabel Data Pesanan -->
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID Pesanan</th>
                    <th>Menu</th>
                    <th>Pelanggan</th>
                    <th>Jumlah</th>
                    <th>Username</th>
                    <th>Nomor Meja</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["id_pesanan"] . "</td>";
                        echo "<td>" . $row["menu_nama"] . "</td>";
                        echo "<td>" . $row["nama_pelanggan"] . "</td>";
                        echo "<td>" . $row["jumlah"] . "</td>";
                        echo "<td>" . $row["username"] . "</td>";
                        echo "<td>" . $row["nomor"] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>Tidak ada pesanan</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
