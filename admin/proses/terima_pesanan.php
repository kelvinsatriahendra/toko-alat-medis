<?php
session_start();
// Path ke file koneksi sudah benar
include '../../koneksi/koneksi.php';

// Pastikan hanya admin yang bisa akses
if (!isset($_SESSION['admin'])) {
    header('location:../index.php');
    exit();
}

// Pastikan ada 'invoice' yang dikirim dari file pesanan.php
if (!isset($_GET['invoice'])) {
    echo "<script>alert('Aksi tidak valid: invoice tidak ditemukan!'); window.location = '../pesanan.php';</script>";
    exit;
}

$invoice = $_GET['invoice'];

// Mulai Transaksi
mysqli_begin_transaction($koneksi); // <- Diubah dari $conn

try {
    // 1. Ambil SEMUA item pesanan dari invoice ini (Gunakan Prepared Statement)
    $stmt_pesanan = mysqli_prepare($koneksi, "SELECT kode_produk, qty FROM pesanan WHERE invoice = ?"); // <- Diubah dari $conn
    mysqli_stmt_bind_param($stmt_pesanan, "s", $invoice);
    mysqli_stmt_execute($stmt_pesanan);
    $result_pesanan = mysqli_stmt_get_result($stmt_pesanan);

    if (mysqli_num_rows($result_pesanan) == 0) {
        throw new Exception("Detail pesanan tidak ditemukan untuk invoice ini.");
    }

    // 2. Looping untuk setiap item dan kurangi stoknya
    while ($item = mysqli_fetch_assoc($result_pesanan)) {
        $kode_produk = $item['kode_produk'];
        $jumlah_dibeli = (int)$item['qty'];

        // Query untuk mengurangi stok (Gunakan Prepared Statement)
        // Cek juga agar stok tidak menjadi minus
        $stmt_update_stok = mysqli_prepare($koneksi, "UPDATE produk SET jumlah_stok = jumlah_stok - ? WHERE kode_produk = ? AND jumlah_stok >= ?"); // <- Diubah dari $conn
        mysqli_stmt_bind_param($stmt_update_stok, "isi", $jumlah_dibeli, $kode_produk, $jumlah_dibeli);
        mysqli_stmt_execute($stmt_update_stok);

        // Jika tidak ada baris yang ter-update (misal karena stok tidak cukup), lempar error
        if (mysqli_stmt_affected_rows($stmt_update_stok) == 0) {
            throw new Exception("Gagal update stok untuk produk $kode_produk (kemungkinan stok tidak mencukupi).");
        }
    }

    // 3. Jika semua stok berhasil diupdate, ubah status pesanan (Gunakan Prepared Statement)
    $stmt_update_pesanan = mysqli_prepare($koneksi, "UPDATE pesanan SET terima = 1, tolak = 0 WHERE invoice = ?"); // <- Diubah dari $conn
    mysqli_stmt_bind_param($stmt_update_pesanan, "s", $invoice);
    mysqli_stmt_execute($stmt_update_pesanan);

    // Jika semua query berhasil, simpan semua perubahan secara permanen
    mysqli_commit($koneksi); // <- Diubah dari $conn
    
    echo "<script>alert('Pesanan BERHASIL diterima dan stok telah diperbarui!'); window.location = '../pesanan.php';</script>";

} catch (Exception $e) {
    // Jika terjadi error di salah satu langkah, batalkan SEMUA perubahan
    mysqli_rollback($koneksi); // <- Diubah dari $conn
    
    echo "<script>alert('ERROR: " . addslashes($e->getMessage()) . " Transaksi dibatalkan.'); window.location = '../pesanan.php';</script>";
}
?>