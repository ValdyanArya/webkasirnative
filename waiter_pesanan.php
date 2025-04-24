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
    <title>Waiter | Kelola Pesanan - Bass Restoran</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
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

        .btn-print {
            background: linear-gradient(135deg, #f8961e, #f3722c);
            border: none;
            padding: 0.5rem 1.25rem;
            font-weight: 500;
            transition: all 0.3s;
            color: white;
        }

        .btn-print:hover {
            background: linear-gradient(135deg, #f3722c, #e85d04);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            color: white;
        }

        .btn-print i {
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
            border-bottom: 2px solid #dee2e6;
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

        /* Status Badges */
        .badge-status {
            padding: 0.5rem 0.75rem;
            border-radius: 50px;
            font-weight: 500;
            font-size: 0.8rem;
        }

        .badge-new {
            background-color: #e3f2fd;
            color: #1976d2;
        }

        .badge-processing {
            background-color: #fff8e1;
            color: #ff8f00;
        }

        .badge-completed {
            background-color: #e8f5e9;
            color: #388e3c;
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

        .btn-view {
            background-color: #17a2b8;
            border-color: #17a2b8;
        }

        .btn-view:hover {
            background-color: #138496;
            border-color: #117a8b;
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
            
            .btn-action {
                margin-bottom: 0.5rem;
                width: 100%;
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
                <a href="waiter_dashboard.php" class="nav-link">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="waiter_pelanggan.php" class="nav-link">
                    <i class="fas fa-users"></i>
                    <span>Pelanggan</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="waiter_menu.php" class="nav-link">
                    <i class="fas fa-utensils"></i>
                    <span>Menu</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="waiter_pesanan.php" class="nav-link active">
                    <i class="fas fa-clipboard-list"></i>
                    <span>Pesanan</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="waiter_generatelaporan.php" class="nav-link">
                    <i class="fas fa-file-alt"></i>
                    <span>Laporan</span>
                </a>
            </div>
        </nav>

        <div class="divider"></div>

        <button class="logout-btn" onclick="window.location.href='logout.php'">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </button>
    </div>

    <div class="content">
        <div class="page-header">
            <h2 class="page-title"><i class="fas fa-clipboard-list"></i> Kelola Pesanan</h2>
            <div>
                <a href="waiter_tambah_pesanan.php" class="btn btn-primary btn-add">
                    <i class="fas fa-plus"></i> Tambah Pesanan
                </a>
                <a href="waiter_generatelaporan.php" class="btn btn-print">
                    <i class="fas fa-print"></i> Cetak Laporan
                </a>
            </div>
        </div>

        <div class="card main-card">
            <div class="card-header">
                <h5><i class="fas fa-list"></i> Daftar Pesanan</h5>
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table id="pesananTable" class="table table-hover dataTable">
                        <thead>
                            <tr>
                                <th>ID Pesanan</th>
                                <th>Menu</th>
                                <th>Pelanggan</th>
                                <th>Jumlah</th>
                                <th>Waiter</th>
                                <th>Meja</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row["id_pesanan"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["menu_nama"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["nama_pelanggan"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["jumlah"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["username"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["nomor"]) . "</td>";
                                    echo "<td>
                                            <div class='d-flex flex-wrap gap-2'>
                                                <a href='waiter_edit_pesanan.php?id_pesanan=" . $row["id_pesanan"] . "' class='btn btn-warning btn-action btn-edit'>
                                                    <i class='fas fa-edit'></i>
                                                </a>
                                                <a href='waiter_hapus_pesanan.php?id_pesanan=" . $row["id_pesanan"] . "' class='btn btn-danger btn-action btn-delete' onclick='return confirm(\"Apakah Anda yakin ingin menghapus pesanan ini?\")'>
                                                    <i class='fas fa-trash'></i>
                                                </a>
                                            </div>
                                          </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7' class='text-center'>Tidak ada data pesanan</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        // Initialize DataTable
        $(document).ready(function() {
            $('#pesananTable').DataTable({
                responsive: true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                },
                dom: '<"top"f>rt<"bottom"lip><"clear">',
                initComplete: function() {
                    $('.dataTables_filter input').addClass('form-control');
                    $('.dataTables_length select').addClass('form-select');
                },
                columnDefs: [
                    { orderable: false, targets: 6 } // Disable sorting for action column
                ]
            });
        });

        // Confirm before delete
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function(e) {
                if (!confirm('Apakah Anda yakin ingin menghapus pesanan ini?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>

</html>
<?php
$conn->close();
?>