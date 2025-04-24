<?php
$servername = "localhost";
$username = "root";
$password = "";     
$dbname = "webkasir";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir | Transaksi</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
            --danger-color: #f72585;
            --success-color: #4cc9f0;
            --warning-color: #f8961e;
            --dark-color: #212529;
            --light-color: #f8f9fa;
            --sidebar-width: 280px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            color: var(--dark-color);
            overflow-x: hidden;
        }

        /* Sidebar - Modern Glass Morphism */
        .sidebar {
            height: 100vh;
            width: var(--sidebar-width);
            position: fixed;
            top: 0;
            left: 0;
            background: rgba(67, 97, 238, 0.9);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            color: white;
            padding: 1.5rem;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .sidebar .logo {
            display: flex;
            align-items: center;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 2rem;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .sidebar .logo:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar .logo i {
            margin-right: 12px;
            font-size: 1.8rem;
            color: #fff;
        }

        .sidebar .nav-item {
            margin: 0.5rem 0;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
            color: rgba(255, 255, 255, 0.9);
            padding: 0.8rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }

        .sidebar .nav-link i {
            width: 24px;
            text-align: center;
            font-size: 1.2rem;
            margin-right: 12px;
            transition: all 0.3s;
        }

        .sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            transform: translateX(5px);
        }

        .sidebar .nav-link:hover i {
            color: white;
        }

        .sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            font-weight: 600;
        }

        .sidebar .nav-link.active i {
            color: white;
        }

        .divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.2);
            margin: 1.5rem 0;
        }

        .logout-btn {
            width: 100%;
            background: linear-gradient(135deg, #f72585, #b5179e);
            border: none;
            padding: 0.8rem;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            margin-top: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .logout-btn:hover {
            background: linear-gradient(135deg, #b5179e, #7209b7);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .logout-btn i {
            margin-right: 8px;
        }

        /* Main Content */
        .content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            transition: all 0.3s;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }

        .page-title {
            font-weight: 600;
            color: var(--dark-color);
            margin: 0;
            display: flex;
            align-items: center;
        }

        .page-title i {
            margin-right: 10px;
            color: var(--primary-color);
        }

        /* Card Styling */
        .main-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            color: white;
            padding: 1.25rem 1.5rem;
            border-bottom: none;
        }

        .card-header h5 {
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
        }

        .card-header h5 i {
            margin-right: 10px;
        }

        /* Button Styling */
        .btn-add {
            background: linear-gradient(135deg, #4cc9f0, #4895ef);
            border: none;
            padding: 0.5rem 1.25rem;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-add:hover {
            background: linear-gradient(135deg, #4895ef, #4361ee);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-add i {
            margin-right: 8px;
        }

        /* Table Styling */
        .table-container {
            overflow-x: auto;
        }

        .dataTable {
            width: 100% !important;
            border-collapse: separate;
            border-spacing: 0 8px;
        }

        .dataTable thead th {
            background-color: #f8f9fa;
            color: #495057;
            font-weight: 600;
            border-bottom: 2px solidrgb(7, 35, 63);
            padding: 1rem;
        }

        .dataTable tbody tr {
            background-color: white;
            transition: all 0.2s;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .dataTable tbody tr:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        .dataTable tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-top: none;
        }

        .dataTable tbody tr:first-child td {
            border-top: none;
        }

        /* Action Buttons */
        .btn-action {
            padding: 0.35rem 0.75rem;
            font-size: 0.875rem;
            border-radius: 6px;
            transition: all 0.2s;
        }

        .btn-edit {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #212529;
        }

        .btn-edit:hover {
            background-color: #e0a800;
            border-color: #d39e00;
            color: #212529;
        }

        .btn-delete {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-delete:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
                z-index: 1050;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .content {
                margin-left: 0;
            }
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
        }
    </style>
</head>

<body>
<div class="sidebar">
        <div class="logo">
            <i class="fa-solid fa-drumstick-bite"></i>
            <span>Bass Restoran</span>
        </div>

        <div class="divider"></div>

        <nav class="nav flex-column">
            <div class="nav-item">
                <a href="kasir_dashboard.php" class="nav-link">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="kasir_transaksi.php" class="nav-link active">
                    <i class="fa-solid fa-credit-card"></i>
                    <span>Entri Transaksi</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="kasir_laporan.php" class="nav-link">
                    <i class="fa-solid fa-file-pdf"></i>
                    <span>Generate Laporan</span>
                </a>
            </div>
        </nav>

        <div class="divider"></div>

        <button class="logout-btn" onclick="window.location.href='logout.php'">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </button>
    </div>

    <div class="content animate-fade">
        <div class="page-header">
            <h1 class="page-title">Daftar Transaksi</h1>
            <a href="kasir_tambah_transaksi.php" class="btn btn-add">
                <i class="fas fa-plus"></i>
                Tambah Transaksi
            </a>
        </div>

        <div class="card hover-shadow">
            <div class="card-header">
                <i class="fas fa-receipt me-2"></i>
                Riwayat Transaksi
            </div>
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID Transaksi</th>
                                <th>Pelanggan</th>
                                <th>Total</th>
                                <th>Bayar</th>
                                <th>Kembalian</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT t.id_transaksi, p.nama_pelanggan, t.total, t.bayar, (t.bayar - t.total) AS kembalian
                                      FROM transaksi t
                                      JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
                                      ORDER BY t.id_transaksi DESC";
                            $result = $conn->query($query);
                            
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>#" . $row["id_transaksi"] . "</td>";
                                    echo "<td>" . $row["nama_pelanggan"] . "</td>";
                                    echo "<td>Rp " . number_format($row["total"], 0, ',', '.') . "</td>";
                                    echo "<td>Rp " . number_format($row["bayar"], 0, ',', '.') . "</td>";
                                    echo "<td><span class='badge bg-success'>Rp " . number_format($row["kembalian"], 0, ',', '.') . "</span></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr>
                                        <td colspan='6' class='text-center py-5'>
                                            <div class='empty-state'>
                                                <i class='fas fa-cash-register'></i>
                                                <h4>Belum Ada Transaksi</h4>
                                                <p>Mulai dengan membuat transaksi baru</p>
                                            </div>
                                        </td>
                                      </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Action Button -->
    <button class="fab" onclick="window.location.href='kasir_tambah_transaksi.php'">
        <i class="fas fa-plus"></i>
    </button>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script>
        // Add active class to current nav item
        document.querySelectorAll('.nav-link').forEach(link => {
            if(link.href === window.location.href) {
                link.classList.add('active');
            }
        });

        // Smooth transitions
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.animate-fade');
            elements.forEach(el => {
                el.style.opacity = '0';
                setTimeout(() => {
                    el.style.opacity = '1';
                }, 100);
            });
        });

        // Confirm before delete
        document.querySelectorAll('.btn-outline-danger').forEach(btn => {
            btn.addEventListener('click', function(e) {
                if(!confirm('Apakah Anda yakin ingin menghapus transaksi ini?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>

</html>