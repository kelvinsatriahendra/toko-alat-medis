<?php
// FILE: admin/edit_kategori.php

// Path yang sudah disesuaikan dengan struktur folder Anda
include 'header.php';
include '../koneksi/koneksi.php';

// Ambil ID dari URL dan pastikan tipenya integer
$id_kategori = (int)$_GET['id'];

// Persiapkan dan eksekusi query dengan aman menggunakan prepared statements
$stmt = mysqli_prepare($conn, "SELECT * FROM kategori WHERE id_kategori = ?");
mysqli_stmt_bind_param($stmt, "i", $id_kategori);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$kategori = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Cek jika kategori tidak ditemukan agar tidak error di form
if (!$kategori) {
    die("Error: Kategori dengan ID tersebut tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Kategori</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style> body { padding: 30px; } </style>
</head>
<body>

<div class="container">
    <h2>Edit Kategori</h2>
    <hr>

    <form action="proses/proses_kategori.php" method="POST">
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="id_kategori" value="<?= htmlspecialchars($kategori['id_kategori']); ?>">

        <div class="form-group">
            <label for="nama_kategori">Nama Kategori:</label>
            <input type="text" name="nama_kategori" id="nama_kategori" class="form-control" 
                   value="<?= htmlspecialchars($kategori['nama_kategori']); ?>" required>
        </div>

        <button type="submit" class="btn btn-success">Update Kategori</button>
        <a href="kelola_kategori.php" class="btn btn-default">Batal</a>
    </form>

</div>

</body>
</html>