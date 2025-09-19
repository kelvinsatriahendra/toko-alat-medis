<?php
// FILE: admin/proses/proses_kategori.php

include '../../koneksi/koneksi.php';

// Cek apakah ada aksi yang dikirim
$action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '');
$sukses = false; // Penanda apakah aksi berhasil

if ($action) {
    switch ($action) {
        // KASUS: MENAMBAH KATEGORI BARU
        case 'add':
            // [SARAN] Tambahkan validasi agar nama kategori tidak kosong
            if (!empty($_POST['nama_kategori'])) {
                $nama_kategori = $_POST['nama_kategori'];
                $stmt = mysqli_prepare($conn, "INSERT INTO kategori (nama_kategori) VALUES (?)");
                mysqli_stmt_bind_param($stmt, "s", $nama_kategori);
                if (mysqli_stmt_execute($stmt)) { // Cek jika eksekusi berhasil
                    $sukses = true;
                }
                mysqli_stmt_close($stmt);
            }
            break;

        // KASUS: MENGUPDATE KATEGORI
        case 'update':
            // [SARAN] Tambahkan validasi
            if (!empty($_POST['id_kategori']) && !empty($_POST['nama_kategori'])) {
                $id_kategori = (int)$_POST['id_kategori'];
                $nama_kategori = $_POST['nama_kategori'];
                $stmt = mysqli_prepare($conn, "UPDATE kategori SET nama_kategori = ? WHERE id_kategori = ?");
                mysqli_stmt_bind_param($stmt, "si", $nama_kategori, $id_kategori);
                if (mysqli_stmt_execute($stmt)) { // Cek jika eksekusi berhasil
                    $sukses = true;
                }
                mysqli_stmt_close($stmt);
            }
            break;

        // KASUS: MENGHAPUS KATEGORI
        case 'delete':
            if (!empty($_GET['id'])) {
                $id_kategori = (int)$_GET['id'];
                $stmt = mysqli_prepare($conn, "DELETE FROM kategori WHERE id_kategori = ?");
                mysqli_stmt_bind_param($stmt, "i", $id_kategori);
                if (mysqli_stmt_execute($stmt)) { // Cek jika eksekusi berhasil
                    $sukses = true;
                }
                mysqli_stmt_close($stmt);
            }
            break;
    }
}

// [SARAN] Redirect berdasarkan status keberhasilan
if ($sukses) {
    // Jika salah satu aksi berhasil, redirect dengan status sukses
    header("Location: ../kelola_kategori.php?status=sukses");
} else {
    // Jika gagal, redirect dengan status error
    header("Location: ../kelola_kategori.php?status=gagal");
}
exit();
?>