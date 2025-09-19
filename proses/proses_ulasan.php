<?php
// Sertakan file koneksi Anda
include '../koneksi/koneksi.php';
session_start();

// 1. Validasi Akses: Pastikan request adalah POST dan user sudah login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['kd_cs'])) {

    // 2. Ambil data dari form dengan aman
    $id_produk = $_POST['id_produk'];
    $id_customer = $_POST['id_customer'];
    $rating = (int)$_POST['rating']; // Jadikan integer untuk keamanan
    $komentar = htmlspecialchars($_POST['komentar']); // Hindari XSS

    // 3. Validasi Data
    // Pastikan user yang mengirim ulasan adalah user yang sedang login
    if ($id_customer !== $_SESSION['kd_cs']) {
        die("Error: Aksi tidak valid.");
    }

    // Pastikan rating berada di antara 1 dan 5
    if ($rating < 1 || $rating > 5) {
        die("Error: Rating tidak valid.");
    }

    // 4. Siapkan perintah SQL yang aman dengan Prepared Statements
    $sql = "INSERT INTO ulasan (id_produk, id_customer, rating, komentar) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    // Periksa apakah prepare statement berhasil
    if ($stmt === false) {
        die("Error preparing statement: " . mysqli_error($conn));
    }

    // 5. Bind parameter ke placeholder (?)
    // Tipe data: s = string, i = integer
    mysqli_stmt_bind_param($stmt, "ssis", $id_produk, $id_customer, $rating, $komentar);

    // 6. Eksekusi perintah
    if (mysqli_stmt_execute($stmt)) {
        // Jika berhasil, kembalikan user ke halaman produk dengan pesan sukses
        echo "<script>
                alert('Terima kasih atas ulasan Anda!');
                window.location.href = '../detail_produk.php?produk=$id_produk';
              </script>";
    } else {
        // Jika gagal, tampilkan pesan error dan kembalikan user
        echo "<script>
                alert('Gagal mengirim ulasan. Silakan coba lagi.');
                window.location.href = '../detail_produk.php?produk=$id_produk';
              </script>";
    }

    // 7. Tutup statement
    mysqli_stmt_close($stmt);

} else {
    // Jika akses tidak valid (bukan POST atau belum login)
    die("Akses tidak diizinkan.");
}

// 8. Tutup koneksi
mysqli_close($conn);

?>