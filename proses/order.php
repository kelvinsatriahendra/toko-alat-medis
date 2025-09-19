<?php
// Aktifkan error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../koneksi/koneksi.php';
require '../vendor/autoload.php';

$kd_cs = $_POST['kode_cs'];
$prov = $_POST['prov'];
$kota = $_POST['kota'];
$alamat = $_POST['almt'];
$kopos = $_POST['kopos'];
$tanggal = date('Y-m-d');
$payment_method = $_POST['payment_method'];
$email_pembeli = $_POST['email'];

// Generate nomor invoice baru
$kode = mysqli_query($conn, "SELECT invoice FROM pesanan ORDER BY invoice DESC LIMIT 1");
$data = mysqli_fetch_assoc($kode);
$num = $data ? substr($data['invoice'], 3, 4) : '0000';
$add = (int) $num + 1;
$format = 'INV' . str_pad($add, 4, '0', STR_PAD_LEFT);

// Ambil data keranjang sesuai customer
$keranjang = mysqli_query($conn, "SELECT * FROM cart WHERE kode_customer = '$kd_cs'");

$order_success = false;

while ($row = mysqli_fetch_assoc($keranjang)) {
    $kd_produk   = $row['kode_produk'];
    $nama_produk = $row['nama_produk'];
    $qty         = (int)$row['qty'];   // <-- ambil qty dari keranjang
    $harga       = $row['harga'];
    $status      = "Pesanan Baru";

    // INSERT dengan sebutkan nama kolom agar qty masuk ke kolom yang benar
    $order = mysqli_query($conn, "INSERT INTO pesanan 
        (invoice, email_pembeli, kode_customer, kode_produk, nama_produk, qty, harga, status, tanggal, provinsi, kota, alamat, kode_pos, terima, tolak, cek)
        VALUES (
            '$format', '$email_pembeli', '$kd_cs', '$kd_produk', '$nama_produk',
            '$qty', '$harga', '$status', '$tanggal',
            '$prov', '$kota', '$alamat', '$kopos', '0', '0', '0'
        )");

    if (!$order) {
        echo "Error insert pesanan: " . mysqli_error($conn);
        exit;
    }

    // Kurangi stok produk sesuai qty
    $update_stok = mysqli_query($conn, "UPDATE stok_barang 
        SET jumlah_stok = jumlah_stok - $qty 
        WHERE kode_barang = '$kd_produk'");

    if (!$update_stok) {
        echo "Error update stok: " . mysqli_error($conn);
        exit;
    }

    $order_success = true;
}

// Hapus keranjang hanya jika pesanan berhasil dimasukkan
if ($order_success) {
    $del_keranjang = mysqli_query($conn, "DELETE FROM cart WHERE kode_customer = '$kd_cs'");
    if (!$del_keranjang) {
        echo "Error hapus keranjang: " . mysqli_error($conn);
        exit;
    }

    // Redirect ke halaman nota
    header("Location: nota.php?kode_cs=$kd_cs&prov=$prov&kota=$kota&almt=$alamat&kopos=$kopos&payment_method=$payment_method&email=$email_pembeli");
    exit();
} else {
    echo "Tidak ada item dalam keranjang atau gagal memproses pesanan.";
}
