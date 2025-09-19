<?php
// Hubungkan ke database
// [PERBAIKAN KRUSIAL] Path diubah menjadi '../' untuk naik satu level direktori
include '../koneksi/koneksi.php'; 

// Cek apakah data dikirim melalui metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Ambil data dari form
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $pesan = $_POST['pesan'];

    // Siapkan query INSERT menggunakan prepared statement (sudah aman)
    $query = "INSERT INTO guestbook (nama, email, pesan) VALUES (?, ?, ?)";
    
    $stmt = mysqli_prepare($conn, $query);

    // Bind parameter ke query
    mysqli_stmt_bind_param($stmt, "sss", $nama, $email, $pesan);

    // Eksekusi query
    if (mysqli_stmt_execute($stmt)) {
        // Jika berhasil, redirect kembali ke form dengan pesan sukses
        header("Location: ../form_guestbook.php?status=sukses");
        exit();
    } else {
        // Jika gagal, tampilkan pesan error yang jelas
        die("Error: Gagal menyimpan data. " . mysqli_stmt_error($stmt));
    }

    // Tutup statement dan koneksi
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

} else {
    // Jika halaman diakses langsung, redirect ke form
    header("Location: ../form_guestbook.php");
    exit();
}
?>

