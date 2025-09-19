<?php
session_start();
include '../koneksi/koneksi.php';

if (!isset($_SESSION['admin'])) {
    header('location:index.php');
    exit();
}

// DATA UNTUK PESANAN (KODE ASLI ANDA)
$result1 = mysqli_query($conn, "SELECT DISTINCT invoice FROM pesanan WHERE terima = 0 AND tolak = 0");
$jml1 = mysqli_num_rows($result1);

$result2 = mysqli_query($conn, "SELECT DISTINCT invoice FROM pesanan WHERE tolak = 1");
$jml2 = mysqli_num_rows($result2);

$result3 = mysqli_query($conn, "SELECT DISTINCT invoice FROM pesanan WHERE terima = 1");
$jml3 = mysqli_num_rows($result3);


// [PENAMBAHAN] DATA UNTUK RINGKASAN STOK
// 1. Menghitung total semua produk
$result_total_produk = mysqli_query($conn, "SELECT COUNT(kode_produk) as total FROM produk");
$row_total_produk = mysqli_fetch_assoc($result_total_produk);
$jml_produk = $row_total_produk['total'];

// 2. Menghitung produk yang stoknya menipis (misalnya, antara 1 dan 10)
$result_stok_menipis = mysqli_query($conn, "SELECT COUNT(kode_produk) as total FROM produk WHERE jumlah_stok > 0 AND jumlah_stok <= 10");
$row_stok_menipis = mysqli_fetch_assoc($result_stok_menipis);
$jml_stok_menipis = $row_stok_menipis['total'];

// 3. Menghitung produk yang stoknya habis (stok = 0)
$result_stok_habis = mysqli_query($conn, "SELECT COUNT(kode_produk) as total FROM produk WHERE jumlah_stok = 0");
$row_stok_habis = mysqli_fetch_assoc($result_stok_habis);
$jml_stok_habis = $row_stok_habis['total'];

?>

<!DOCTYPE html>
<html>

<head>
    <title>Prima Medical - Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="../js/jquery.js"></script>
    <script src="../js/bootstrap.min.js"></script>

    <style>
        :root {
            --primary-color: #1e3a5f;
            --secondary-color: #27ae60;
            --light-bg: #f5f7fa;
            --text-dark: #333;
            --text-light: #fefefe;
            --shadow-light: rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Montserrat', sans-serif;
            color: var(--text-dark);
            background-color: var(--light-bg);
            margin: 0;
            padding: 0;
        }

        .navbar-default {
            background-color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
            border-radius: 0;
            padding: 10px 0;
            margin-bottom: 0;
        }

        .navbar-default .navbar-brand {
            color: var(--text-light);
            font-weight: 700;
            font-size: 1.5em;
        }

        .navbar-default .navbar-nav>li>a {
            color: var(--text-light);
            font-weight: 500;
            letter-spacing: 0.5px;
            padding: 15px;
            transition: all 0.3s ease;
        }

        .navbar-default .navbar-nav>li>a:hover {
            color: var(--secondary-color) !important;
            background-color: transparent;
        }

        .dropdown-menu {
            background-color: #fff;
            border: none;
            box-shadow: 0 5px 20px var(--shadow-light);
            border-radius: 8px;
            margin-top: 10px;
        }

        .dropdown-menu>li>a {
            color: var(--text-dark);
            padding: 10px 20px;
            font-weight: 500;
        }

        .dropdown-menu>li>a:hover {
            background-color: var(--light-bg);
            color: var(--primary-color);
        }

        .navbar-default .navbar-nav>li.active>a {
            background-color: var(--secondary-color) !important;
            color: #fff !important;
        }

        .navbar-nav .fa,
        .navbar-nav .fas {
            margin-right: 8px;
        }

        .dashboard-container {
            padding-top: 50px;
            padding-bottom: 50px;
            min-height: calc(100vh - 150px);
        }

        .dashboard-card {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 5px 20px var(--shadow-light);
            padding: 30px;
            text-align: center;
            margin-bottom: 30px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .card-title {
            font-size: 1.1em;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-dark);
            margin-bottom: 10px;
        }

        .card-value {
            font-size: 4em;
            font-weight: 700;
            margin-top: 0;
        }

        /* WARNA KARTU PESANAN */
        .card-new {
            border-bottom: 3px solid #3498db;
        }

        .card-new .card-value {
            color: #3498db;
        }

        .card-cancelled {
            border-bottom: 3px solid #e74c3c;
        }

        .card-cancelled .card-value {
            color: #e74c3c;
        }

        .card-received {
            border-bottom: 3px solid #27ae60;
        }

        .card-received .card-value {
            color: #27ae60;
        }

        /* [PENAMBAHAN] WARNA KARTU STOK */
        .card-total-produk {
            border-bottom: 3px solid #555;
        }

        .card-total-produk .card-value {
            color: #555;
        }

        .card-stok-menipis {
            border-bottom: 3px solid #f39c12;
        }

        .card-stok-menipis .card-value {
            color: #f39c12;
        }

        .card-stok-habis {
            border-bottom: 3px solid #e74c3c;
        }

        .card-stok-habis .card-value {
            color: #e74c3c;
        }

        .footer {
            background-color: var(--primary-color);
            color: var(--text-light);
            padding: 20px 0;
            text-align: center;
            width: 100%;
        }

        .footer p {
            margin: 0;
            font-size: 0.9em;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-default">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="halaman_utama.php">Admin Panel</a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-left">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fas fa-database"></i> Data Master <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="m_produk.php">Master Produk</a></li>
                            <li><a href="m_customer.php">Master Customer</a></li>
                            <li><a href="kelola_kategori.php">Master Kategori</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fas fa-exchange-alt"></i> Data Transaksi <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="pesanan.php">Pesanan</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fas fa-chart-bar"></i> Laporan <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="laporan_penjualan.php">Laporan Penjualan</a></li>
                            <li><a href="laporan_pesanan.php">Laporan Pesanan</a></li>
                        </ul>
                    </li>

                    <li><a href="view_guestbook.php"><i class="fas fa-book-open"></i> Buku Tamu</a></li>

                    <li><a href="halaman_utama.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">

                    <li><a href="../index.php" target="_blank"><i class="fas fa-globe"></i> Visit Site</a></li>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fas fa-tools"></i> Pemeliharaan <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="../DATABASE/backup.php">Backup Database</a></li>
                            <li><a href="../DATABASE/retrieve.php">Retrieve Database</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user-circle"></i> Admin <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="proses/logout.php">Log Out</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container dashboard-container">

        <!-- BAGIAN PESANAN -->
        <div class="row">
            <div class="col-md-4">
                <div class="dashboard-card card-new">
                    <h4 class="card-title">PESANAN BARU</h4>
                    <h4 class="card-value"><?= $jml1; ?></h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="dashboard-card card-cancelled">
                    <h4 class="card-title">PESANAN DIBATALKAN</h4>
                    <h4 class="card-value"><?= $jml2; ?></h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="dashboard-card card-received">
                    <h4 class="card-title">PESANAN DITERIMA</h4>
                    <h4 class="card-value"><?= $jml3; ?></h4>
                </div>
            </div>
        </div>

        <hr>

        <!-- [PENAMBAHAN] BAGIAN RINGKASAN STOK -->
        <div class="row">
            <div class="col-md-4">
                <div class="dashboard-card card-total-produk">
                    <h4 class="card-title">TOTAL PRODUK</h4>
                    <h4 class="card-value"><?= $jml_produk; ?></h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="dashboard-card card-stok-menipis">
                    <h4 class="card-title">STOK MENIPIS</h4>
                    <h4 class="card-value"><?= $jml_stok_menipis; ?></h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="dashboard-card card-stok-habis">
                    <h4 class="card-title">STOK HABIS</h4>
                    <h4 class="card-value"><?= $jml_stok_habis; ?></h4>
                </div>
            </div>
        </div>

    </div>
    <div class="footer">
        <p>Copyright &copy; <?= date('Y'); ?> Prima Medical. All Rights Reserved.</p>
    </div>
</body>

</html>