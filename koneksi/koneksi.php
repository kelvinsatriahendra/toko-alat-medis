<?php 

$host = "localhost";
$user = "root";
$pass = "";

// NAMA DATABASE SUDAH DIPERBAIKI SESUAI GAMBAR DARI KAMU
$db = "toko_medis_prima"; 


// Kode di bawah ini jangan diubah lagi.
$conn = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi
if (!$conn) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}

?>

