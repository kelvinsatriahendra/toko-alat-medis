<?php
// Hubungkan ke database
include '../koneksi/koneksi.php'; // Sesuaikan path jika perlu

// Cek apakah ID dikirim melalui URL
if (isset($_GET['id'])) {
    
    // Ambil ID dan pastikan itu adalah angka untuk keamanan
    $id_to_delete = (int)$_GET['id'];

    // Siapkan query DELETE menggunakan prepared statement (lebih aman)
    $query = "DELETE FROM guestbook WHERE id = ?";
    
    $stmt = mysqli_prepare($conn, $query);

    // Bind parameter ID ke query
    mysqli_stmt_bind_param($stmt, "i", $id_to_delete);

    // Eksekusi query
    if (mysqli_stmt_execute($stmt)) {
        // Jika berhasil, redirect kembali ke halaman utama
        header("Location: view_guestbook.php?status=sukses_hapus");
    } else {
        // Jika gagal
        echo "Error: Gagal menghapus data. " . mysqli_stmt_error($stmt);
    }

    // Tutup statement
    mysqli_stmt_close($stmt);

} else {
    // Jika tidak ada ID yang dikirim, kembalikan ke halaman utama
    header("Location: view_guestbook.php");
}

// Tutup koneksi
mysqli_close($conn);
exit();
?>