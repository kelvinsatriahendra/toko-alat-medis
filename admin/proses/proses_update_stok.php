<?php
session_start();
include '../../koneksi/koneksi.php'; // Path disesuaikan

if (isset($_POST['update_stok'])) {

    $kode_produk = $_POST['kode_produk'];
    $stok_baru = (int)$_POST['stok']; // Ambil dari input name="stok"

    if ($stok_baru < 0) {
        $stok_baru = 0;
    }

    // Gunakan PREPARED STATEMENT yang aman
    $stmt = mysqli_prepare($conn, "UPDATE produk SET stok = ? WHERE kode_produk = ?");

    // Perbaikan nama kolom dari 'jumlah_stok' menjadi 'stok'
    mysqli_stmt_bind_param($stmt, "is", $stok_baru, $kode_produk);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['pesan_sukses'] = "Stok berhasil diperbarui!";
    } else {
        $_SESSION['pesan_gagal'] = "FATAL: Gagal memperbarui stok!";
    }
    mysqli_stmt_close($stmt);
    header("Location: ../m_stok.php");
    exit;
} else {
    header("Location: ../halaman_utama.php");
    exit;
}
