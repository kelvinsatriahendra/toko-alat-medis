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
// $qty_dipesan = isset($_GET['jml']) ? (int)$_GET['jml'] : 1;
$qty_dipesan = isset($_GET['qty']) ? (int)$_GET['qty'] : 1;

// pastikan qty minimal 1
if ($qty_dipesan < 1) {
    $qty_dipesan = 1;
}

// // 3. Cek apakah produk sudah ada di keranjang
// $stmt = mysqli_prepare($conn, "SELECT qty FROM cart WHERE kode_produk = ? AND kode_customer = ?");
// mysqli_stmt_bind_param($stmt, "ss", $kode_produk, $kode_cs);
// mysqli_stmt_execute($stmt);
// $result = mysqli_stmt_get_result($stmt);
// $existing_cart_item = mysqli_fetch_assoc($result);
// mysqli_stmt_close($stmt);

// if ($existing_cart_item) {
//     // Produk sudah ada → update qty
//     $qty_baru = $existing_cart_item['qty'] + $qty_dipesan;

//     $stmt_update = mysqli_prepare($conn, "UPDATE cart SET qty = ? WHERE kode_produk = ? AND kode_customer = ?");
//     mysqli_stmt_bind_param($stmt_update, "iss", $qty_baru, $kode_produk, $kode_cs);
//     mysqli_stmt_execute($stmt_update);
//     mysqli_stmt_close($stmt_update);
// } else {
//     // Produk belum ada → ambil data dari tabel produk
//     $stmt_produk = mysqli_prepare($conn, "SELECT nama, harga FROM produk WHERE kode_produk = ?");
//     mysqli_stmt_bind_param($stmt_produk, "s", $kode_produk);
//     mysqli_stmt_execute($stmt_produk);
//     $result_produk = mysqli_stmt_get_result($stmt_produk);
//     $data_produk = mysqli_fetch_assoc($result_produk);
//     mysqli_stmt_close($stmt_produk);

//     if ($data_produk) {
//         $nama_produk  = $data_produk['nama'];
//         $harga_produk = $data_produk['harga'];

//         $stmt_insert = mysqli_prepare($conn, "INSERT INTO cart (kode_customer, kode_produk, nama_produk, qty, harga) VALUES (?, ?, ?, ?, ?)");
//         mysqli_stmt_bind_param($stmt_insert, "sssid", $kode_cs, $kode_produk, $nama_produk, $qty_dipesan, $harga_produk);
//         mysqli_stmt_execute($stmt_insert);
//         mysqli_stmt_close($stmt_insert);
//     }
// }

// // 4. Redirect kembali ke keranjang
// echo "
// <script>
//     alert('Produk berhasil ditambahkan ke keranjang!');
//     window.location = '../keranjang.php';
// </script>
// ";
// exit;
// 3. Cek Stok Aktual di Database Produk
$stmt_stok = mysqli_prepare($conn, "SELECT jumlah_stok, nama FROM produk WHERE kode_produk = ?");
mysqli_stmt_bind_param($stmt_stok, "s", $kode_produk);
mysqli_stmt_execute($stmt_stok);
$result_stok = mysqli_stmt_get_result($stmt_stok);
$produk_data = mysqli_fetch_assoc($result_stok);
mysqli_stmt_close($stmt_stok);

if (!$produk_data) {
    echo "<script>alert('Produk tidak ditemukan!'); window.location = '../index.php';</script>";
    exit;
}

$stok_tersedia = $produk_data['jumlah_stok'];
$nama_produk   = $produk_data['nama'];

// 4. Cek kuantitas yang sudah ada di keranjang (jika ada)
$qty_di_keranjang = 0;
$stmt_cart_check = mysqli_prepare($conn, "SELECT qty FROM cart WHERE kode_produk = ? AND kode_customer = ?");
mysqli_stmt_bind_param($stmt_cart_check, "ss", $kode_produk, $kode_cs);
mysqli_stmt_execute($stmt_cart_check);
$result_cart_check = mysqli_stmt_get_result($stmt_cart_check);
if ($existing_item = mysqli_fetch_assoc($result_cart_check)) {
    $qty_di_keranjang = $existing_item['qty'];
}
mysqli_stmt_close($stmt_cart_check);

// 5. Validasi Total Kuantitas vs Stok
$total_qty_diinginkan = $qty_di_keranjang + $qty_dipesan;

if ($total_qty_diinginkan > $stok_tersedia) {
    echo "<script>
        alert('Maaf, stok untuk produk \"{$nama_produk}\" tidak mencukupi. Sisa stok: {$stok_tersedia}');
        window.location = '../keranjang.php';
    </script>";
    exit;
}

// ==================================================================
// == Lanjutkan proses INSERT atau UPDATE jika stok aman ==
// ==================================================================

// 6. Proses ke Keranjang
if ($qty_di_keranjang > 0) {
    // Produk sudah ada → UPDATE qty
    $stmt_update = mysqli_prepare($conn, "UPDATE cart SET qty = ? WHERE kode_produk = ? AND kode_customer = ?");
    mysqli_stmt_bind_param($stmt_update, "iss", $total_qty_diinginkan, $kode_produk, $kode_cs);
    mysqli_stmt_execute($stmt_update);
    mysqli_stmt_close($stmt_update);
} else {
    // Produk belum ada → INSERT
    // Ambil harga dari tabel produk (sudah kita dapatkan di atas)
    $stmt_harga = mysqli_prepare($conn, "SELECT harga FROM produk WHERE kode_produk = ?");
    mysqli_stmt_bind_param($stmt_harga, "s", $kode_produk);
    mysqli_stmt_execute($stmt_harga);
    $result_harga = mysqli_stmt_get_result($stmt_harga);
    $harga_produk = mysqli_fetch_assoc($result_harga)['harga'];
    mysqli_stmt_close($stmt_harga);

    $stmt_insert = mysqli_prepare($conn, "INSERT INTO cart (kode_customer, kode_produk, nama_produk, qty, harga) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt_insert, "sssid", $kode_cs, $kode_produk, $nama_produk, $qty_dipesan, $harga_produk);
    mysqli_stmt_execute($stmt_insert);
    mysqli_stmt_close($stmt_insert);
}

// 7. Redirect kembali ke keranjang
echo "<script>
    alert('Produk berhasil ditambahkan ke keranjang!');
    window.location = '../keranjang.php';
</script>";
exit;
