<?php
session_start();
include '../koneksi/koneksi.php';

// 1. Validasi Login
if (!isset($_SESSION['kd_cs'])) {
    echo "<script>
        alert('Anda harus login terlebih dahulu!');
        window.location = '../user_login.php';
    </script>";
    exit;
}

// 2. Ambil data dari form
$kode_produk = mysqli_real_escape_string($conn, $_GET['produk']);
$kode_cs     = mysqli_real_escape_string($conn, $_SESSION['kd_cs']);
$qty_dipesan = isset($_GET['jml']) ? (int)$_GET['jml'] : 1;

// pastikan qty minimal 1
if ($qty_dipesan < 1) {
    $qty_dipesan = 1;
}

// 3. Cek apakah produk sudah ada di keranjang
$stmt = mysqli_prepare($conn, "SELECT qty FROM cart WHERE kode_produk = ? AND kode_customer = ?");
mysqli_stmt_bind_param($stmt, "ss", $kode_produk, $kode_cs);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$existing_cart_item = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if ($existing_cart_item) {
    // Produk sudah ada → update qty
    $qty_baru = $existing_cart_item['qty'] + $qty_dipesan;

    $stmt_update = mysqli_prepare($conn, "UPDATE cart SET qty = ? WHERE kode_produk = ? AND kode_customer = ?");
    mysqli_stmt_bind_param($stmt_update, "iss", $qty_baru, $kode_produk, $kode_cs);
    mysqli_stmt_execute($stmt_update);
    mysqli_stmt_close($stmt_update);
} else {
    // Produk belum ada → ambil data dari tabel produk
    $stmt_produk = mysqli_prepare($conn, "SELECT nama, harga FROM produk WHERE kode_produk = ?");
    mysqli_stmt_bind_param($stmt_produk, "s", $kode_produk);
    mysqli_stmt_execute($stmt_produk);
    $result_produk = mysqli_stmt_get_result($stmt_produk);
    $data_produk = mysqli_fetch_assoc($result_produk);
    mysqli_stmt_close($stmt_produk);

    if ($data_produk) {
        $nama_produk  = $data_produk['nama'];
        $harga_produk = $data_produk['harga'];

        $stmt_insert = mysqli_prepare($conn, "INSERT INTO cart (kode_customer, kode_produk, nama_produk, qty, harga) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt_insert, "sssid", $kode_cs, $kode_produk, $nama_produk, $qty_dipesan, $harga_produk);
        mysqli_stmt_execute($stmt_insert);
        mysqli_stmt_close($stmt_insert);
    }
}

// 4. Redirect kembali ke keranjang
echo "
<script>
    alert('Produk berhasil ditambahkan ke keranjang!');
    window.location = '../keranjang.php';
</script>
";
exit;
