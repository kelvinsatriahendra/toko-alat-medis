<?php
include '../koneksi/koneksi.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['kd_cs'])) {
    $kode_customer = $_POST['kode_customer'];
    $password_lama = $_POST['password_lama'];
    $password_baru = $_POST['password_baru'];
    $konfirmasi_password_baru = $_POST['konfirmasi_password_baru'];

    // Ambil password lama dari database
    $query = mysqli_query($conn, "SELECT password FROM customer WHERE kode_customer = '$kode_customer'");
    $row = mysqli_fetch_assoc($query);
    $hashed_password_lama = $row['password'];

    // Verifikasi password lama
    if (!password_verify($password_lama, $hashed_password_lama)) {
        echo "<script>alert('Password lama salah.'); window.location.href='../ganti_password.php?kode=$kode_customer';</script>";
        exit();
    }

    // Periksa apakah password baru dan konfirmasinya sama
    if ($password_baru != $konfirmasi_password_baru) {
        echo "<script>alert('Konfirmasi password baru tidak sama.'); window.location.href='../ganti_password.php?kode=$kode_customer';</script>";
        exit();
    }

    // Enkripsi password baru
    $hashed_password_baru = password_hash($password_baru, PASSWORD_DEFAULT);

    // Update password di database
    $update_query = "UPDATE customer SET password = '$hashed_password_baru' WHERE kode_customer = '$kode_customer'";
    $result = mysqli_query($conn, $update_query);

    if ($result) {
        echo "<script>alert('Password berhasil diganti!'); window.location.href='../detail_akun.php';</script>";
    } else {
        echo "<script>alert('Gagal mengganti password. Silakan coba lagi.'); window.location.href='../ganti_password.php?kode=$kode_customer';</script>";
    }
} else {
    echo "<script>alert('Akses tidak valid.'); window.location.href='../user_login.php';</script>";
}
?>
