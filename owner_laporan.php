<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner | Pilih Laporan</title>
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

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .header h2 {
            font-weight: 600;
            color: var(--dark-color);
            margin: 0;
        }

        .welcome-text {
            color: #6c757d;
            font-size: 1.1rem;
        }

        /* Cards - Modern Design */
        .stats-card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            margin-bottom: 1.5rem;
            height: 100%;
        }

        .stats-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
        }

        .stats-card .card-body {
            padding: 1.5rem;
            position: relative;
        }

        .stats-card .card-icon {
            position: absolute;
            right: 1.5rem;
            top: 1.5rem;
            font-size: 2.5rem;
            opacity: 0.2;
            transition: all 0.3s;
        }

        .stats-card:hover .card-icon {
            opacity: 0.3;
            transform: scale(1.1);
        }

        .stats-card .card-title {
            font-size: 1rem;
            font-weight: 500;
            color: #6c757d;
            margin-bottom: 0.5rem;
        }

        .stats-card .card-value {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .card-primary {
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            color: white;
        }

        .card-success {
            background: linear-gradient(135deg, #4cc9f0, #4895ef);
            color: white;
        }

        .card-warning {
            background: linear-gradient(135deg, #f8961e, #f3722c);
            color: white;
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
                <a href="owner_dashboard.php" class="nav-link">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="owner_laporan.php" class="nav-link active">
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
    <!-- Tambahkan di dalam <body> menggantikan bagian container laporan -->
<div class="content">
    <div class="header">
        <div>
            <h2>Pilih Jenis Laporan</h2>
            <p class="welcome-text">Silakan pilih jenis laporan yang ingin dicetak</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card stats-card card-primary text-white text-center">
                <div class="card-body">
                    <i class="fa-solid fa-utensils card-icon"></i>
                    <div class="card-title">Laporan Pesanan</div>
                    <div class="card-value">Generate Sekarang</div>
                    <a href="laporan_pesanan.php" class="btn btn-light mt-3">
                        <i class="fa-solid fa-print me-2"></i> Cetak Laporan
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card stats-card card-success text-white text-center">
                <div class="card-body">
                    <i class="fa-solid fa-money-bill-wave card-icon"></i>
                    <div class="card-title">Laporan Transaksi</div>
                    <div class="card-value">Generate Sekarang</div>
                    <a href="laporan_transaksi.php" class="btn btn-light mt-3">
                        <i class="fa-solid fa-print me-2"></i> Cetak Laporan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>