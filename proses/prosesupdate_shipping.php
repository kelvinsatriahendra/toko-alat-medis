<?php
// include '../koneksi/koneksi.php';

// Pastikan request adalah POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Ambil data dari form
    $id_pesanan = $_POST['id_pesanan'];
    $kurir = $_POST['kurir'];
    $nomor_resi = $_POST['nomor_resi'];
    $status = $_POST['status'];

    // Siapkan query yang aman
    $sql = "UPDATE shipping SET kurir = ?, nomor_resi = ?, status_pengiriman = ? WHERE id_pesanan = ?";
    $stmt = mysqli_prepare($conn, $sql);

    // Bind parameter
    mysqli_stmt_bind_param($stmt, "ssss", $kurir, $nomor_resi, $status, $id_pesanan);

    // Eksekusi dan redirect
    if (mysqli_stmt_execute($stmt)) {
        header("Location: ../kelola_pengiriman.php?status=sukses");
    } else {
        header("Location: ../kelola_pengiriman.php?status=gagal");
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

} else {
    // Jika akses tidak valid
    die("Akses tidak diizinkan.");
}
?>