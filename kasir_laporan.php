<?php
include 'config.php';

// Ambil tanggal dari form atau default ke hari ini
$tanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');

// Query transaksi berdasarkan tanggal
$query = "SELECT t.id_transaksi, t.tanggal, pl.nama_pelanggan, t.total, t.bayar, t.kembalian 
          FROM transaksi t
          JOIN pelanggan pl ON t.id_pelanggan = pl.id_pelanggan
          WHERE DATE(t.tanggal) = '$tanggal'
          ORDER BY t.tanggal DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Background gradient lebih modern */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: #ffffff;
            color: #000;
        }

        .costume-card {
            background-color: rgb(30, 43, 109);
        }

        /* Sidebar */
        .sidebar {
            height: 100vh;
            width: 260px;
            position: fixed;
            top: 0;
            left: 0;
            background: linear-gradient(135deg, #0f5eb2, #4285F4);
            color: #fff;
            padding: 20px;
            transition: all 0.3s;
        }

        .sidebar .logo {
            display: flex;
            align-items: center;
            font-size: 1.6em;
            font-weight: bold;
            margin-bottom: 30px;
        }

        .sidebar .logo i {
            margin-right: 10px;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            color: #fff;
            padding: 12px;
            gap: 10px;
            margin: 10px 0;
            border-radius: 8px;
            text-decoration: none;
            font-size: 16px;
            transition: 0.3s;
        }

        .sidebar a i {
            width: 25px;
            text-align: center;
            font-size: 1.3em;
        }

        .sidebar a:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.05);
        }

        .sidebar a.active {
            background: rgba(255, 255, 255, 0.3);
        }

        .sidebar .btn-danger {
            width: 100%;
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            border: none;
            padding: 12px;
            margin-top: 20px;
            font-size: 16px;
            transition: 0.3s;
        }

        .sidebar .btn-danger:hover {
            background: linear-gradient(135deg, #c0392b, #a8322d);
            transform: scale(1.05);
        }

        /* Content */
        .content {
            margin-left: 280px;
            padding: 30px;
        }

        .content h2 {
            font-weight: bold;
            color: #fff;
            margin-bottom: 20px;
        }

        /* Card modern */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
        }

        .card i {
            float: right;
            opacity: 0.8;
            font-size: 3em;
        }

        .btn i {
            font-size: 18px;
            /* Sesuaikan ukuran ikon */
        }

        /* Custom separator line */
        .custom-line {
            height: 2px;
            background-color: #ffffff;
            border: none;
            margin: 20px 0;
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
        <h2 class="text-center mb-4">Laporan Transaksi</h2>

        <!-- Form filter tanggal -->
        <form method="GET" class="row mb-4 no-print">
            <div class="col-md-4">
                <input type="date" name="tanggal" class="form-control" value="<?= $tanggal ?>" required>
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


        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nama Pelanggan</th>
                    <th>Total</th>
                    <th>Bayar</th>
                    <th>Kembalian</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    $no = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$no}</td>
                            <td>{$row['tanggal']}</td>
                            <td>{$row['nama_pelanggan']}</td>
                            <td>Rp " . number_format($row['total'], 0, ',', '.') . "</td>
                            <td>Rp " . number_format($row['bayar'], 0, ',', '.') . "</td>
                            <td>Rp " . number_format($row['kembalian'], 0, ',', '.') . "</td>
                        </tr>";
                        $no++;
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>Tidak ada transaksi di tanggal ini.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>