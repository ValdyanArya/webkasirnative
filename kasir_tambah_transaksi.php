<?php
include 'config.php';

// Ambil semua pelanggan yang belum ada transaksinya
$query = "SELECT p.id_pelanggan, pl.nama_pelanggan, GROUP_CONCAT(m.nama SEPARATOR ', ') as daftar_menu, SUM(m.harga * p.jumlah) as total
          FROM pesanan p
          JOIN menu m ON p.idmenu = m.idmenu
          LEFT JOIN transaksi t ON p.id_pelanggan = t.id_pelanggan
          JOIN pelanggan pl ON p.id_pelanggan = pl.id_pelanggan
          WHERE t.id_pelanggan IS NULL
          GROUP BY p.id_pelanggan";
$result = $conn->query($query);

// Ambil semua menu
$menu_query = "SELECT idmenu, nama FROM menu";
$menus = $conn->query($menu_query)->fetch_all(MYSQLI_ASSOC);

// Ambil daftar nama pelanggan
$pelanggan_query = "SELECT id_pelanggan, nama_pelanggan FROM pelanggan";
$pelanggans = $conn->query($pelanggan_query)->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Tambah Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
            max-width: 500px;
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }

        h2 {
            text-align: center;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #007bff, #0056b3);
            border: none;
        }

        .btn-secondary {
            background: #6c757d;
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #0056b3, #003f7f);
        }

        .btn-secondary:hover {
            background: #5a6268;
        }
    </style>
</head>

<body class="p-4">
    <div class="container">
        <h3>Tambah Transaksi</h3>
        <form action="proses_transaksi.php" method="POST">
            <!-- Input untuk memilih pelanggan -->
            <div class="mb-3">
                <label for="id_pelanggan" class="form-label">Pilih Pelanggan</label>
                <select name="id_pelanggan" id="id_pelanggan" class="form-select" required>
                    <option value="">-- Pilih Pelanggan --</option>
                    <?php
                    foreach ($pelanggans as $pelanggan) {
                        echo "<option value='{$pelanggan['id_pelanggan']}'>Pelanggan: {$pelanggan['nama_pelanggan']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="id_pelanggan" class="form-label">Pilih Pesanan</label>
                <select name="id_pelanggan" id="id_pelanggan" class="form-select" required>
                    <option value="">-- Pilih Pesanan --</option>
                    <?php
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['id_pelanggan']}'>Pelanggan: {$row['nama_pelanggan']} - {$row['daftar_menu']} (Total: Rp " . number_format($row['total'], 0, ',', '.') . ")</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Input untuk jumlah bayar -->
            <div class="mb-3">
                <label for="bayar" class="form-label">Jumlah Bayar</label>
                <input type="number" name="bayar" id="bayar" class="form-control" required>
            </div>

            <!-- Input untuk kembalian -->
            <div class="mb-3">
                <label for="kembalian" class="form-label">Kembalian</label>
                <input type="number" name="kembalian" id="kembalian" class="form-control" readonly>
            </div>

            <!-- Input tanggal otomatis -->
            <div class="mb-3">
                <label for="tanggal" class="form-label">Tanggal Transaksi</label>
                <input type="date" name="tanggal" id="tanggal" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
        </form>
    </div>
    <script>
        const bayarInput = document.getElementById('bayar');
        const kembalianInput = document.getElementById('kembalian');
        const pesananSelect = document.querySelectorAll('select[name="id_pelanggan"]')[1];
        const tanggalInput = document.getElementById('tanggal');
        const form = document.querySelector('form');

        let total = 0;

        // Fungsi untuk ambil total dari tulisan option
        function getTotalFromOptionText(text) {
            const match = text.match(/Total: Rp\s([\d.]+)/);
            if (match && match[1]) {
                return parseInt(match[1].replace(/\./g, ''));
            }
            return 0;
        }

        // Fungsi isi tanggal hari ini otomatis
        function setTodayDate() {
            const today = new Date().toISOString().split('T')[0];
            tanggalInput.value = today;
        }

        // Hitung kembalian otomatis
        function updateKembalian() {
            const bayar = parseInt(bayarInput.value || 0);
            const kembali = bayar - total;
            const hasil = kembali >= 0 ? kembali : 0;
            kembalianInput.value = hasil;
            document.getElementById('kembalian_hidden').value = hasil;
        }

        // Set total berdasarkan pesanan
        pesananSelect.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            total = getTotalFromOptionText(selectedOption.text);
            updateKembalian();
        });

        bayarInput.addEventListener('input', updateKembalian);

        // Validasi sebelum kirim form
        form.addEventListener('submit', function (e) {
            if (isNaN(total) || total <= 0) {
                alert('Pilih pesanan yang valid terlebih dahulu.');
                e.preventDefault();
                return;
            }

            const bayar = parseInt(bayarInput.value || 0);
            if (bayar < total) {
                alert('Jumlah bayar tidak cukup!');
                e.preventDefault();
            }
        });

        // Panggil saat pertama kali halaman dibuka
        setTodayDate();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Menambahkan input pesanan
        document.getElementById("addPesananBtn").addEventListener("click", function () {
            const container = document.getElementById("pesananContainer");
            const group = document.querySelector(".pesanan-group");
            const clone = group.cloneNode(true);

            // Kosongkan input baru
            clone.querySelector("select").value = "";
            clone.querySelector("input").value = "";

            container.appendChild(clone);
        });
    </script>
</body>

</html>