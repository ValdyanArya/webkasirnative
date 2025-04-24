<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "webkasir");

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari tabel user
$sql = "SELECT * FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Kelola User - Bass Restoran</title>
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
                <a href="admin_dashboard.php" class="nav-link">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="admin_user.php" class="nav-link active">
                    <i class="fas fa-users"></i>
                    <span>Pengguna</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="admin_menu.php" class="nav-link">
                    <i class="fas fa-utensils"></i>
                    <span>Menu</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="admin_meja.php" class="nav-link">
                    <i class="fas fa-chair"></i>
                    <span>Meja</span>
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
            <h2 class="page-title"><i class="fas fa-users-cog"></i> Manajemen Pengguna</h2>
            <a href="admin_tambah_user.php" class="btn btn-add">
                <i class="fas fa-user-plus"></i> Tambah Pengguna
            </a>
        </div>

        <div class="card glass-card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="userTable" class="table dataTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Nama Lengkap</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $roleClass = '';
                                    switch(strtolower($row["role"])) {
                                        case 'admin': $roleClass = 'badge-admin'; break;
                                        case 'waiter': $roleClass = 'badge-waiter'; break;
                                        case 'kasir': $roleClass = 'badge-kasir'; break;
                                        default: $roleClass = 'badge-secondary';
                                    }
                                    
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row["id_user"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["username"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["nama_lengkap"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                                    echo "<td><span class='badge-role $roleClass'>" . htmlspecialchars($row["role"]) . "</span></td>";
                                    echo "<td>
                                            <div class='d-flex flex-wrap'>
                                                <a href='admin_edit_user.php?id_user=" . $row["id_user"] . "' class='btn btn-action btn-edit'>
                                                    <i class='fas fa-edit'></i> Edit
                                                </a>
                                                <a href='admin_hapus_user.php?id_user=" . $row["id_user"] . "' class='btn btn-action btn-delete' onclick='return confirm(\"Apakah Anda yakin ingin menghapus pengguna ini?\")'>
                                                    <i class='fas fa-trash'></i> Hapus
                                                </a>
                                            </div>
                                          </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center'>Tidak ada data pengguna</td></tr>";
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
            $('#userTable').DataTable({
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
                    { orderable: false, targets: 5 } // Disable sorting for action column
                ]
            });
        });

        // Confirm before delete
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function(e) {
                if (!confirm('Apakah Anda yakin ingin menghapus pengguna ini?')) {
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