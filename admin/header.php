<?php
session_start();
include '../koneksi/koneksi.php';

if (!isset($_SESSION['admin'])) {
    header('location:index.php');
    exit();
}
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