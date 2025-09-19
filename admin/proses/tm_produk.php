<?php
include '../../koneksi/koneksi.php';

// Cek apakah request dari method POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    // Jika tidak, tendang ke halaman produk
    header('location: ../m_produk.php');
    exit;
}

// Ambil data dari form
$id_kategori = (int)$_POST['id_kategori'];
$kode = $_POST['kode'];
$nama_produk = $_POST['nama'];
$harga = (int)$_POST['harga'];
$deskripsi = $_POST['desk'];

// [INI BAGIAN PENTING] Ambil data jumlah_stok dari form
$jumlah_stok = (int)$_POST['jumlah_stok'];

// Proses upload gambar
$nama_gambar = $_FILES['files']['name'];
$size_gambar = $_FILES['files']['size'];
$tmp_file = $_FILES['files']['tmp_name'];
$eror = $_FILES['files']['error'];

if ($eror === 4) {
    echo "<script>
        alert('TIDAK ADA GAMBAR YANG DIPILIH');
        window.location = '../tm_produk.php';
    </script>";
    die;
}

$ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
$ekstensiGambar = explode(".", $nama_gambar);
$ekstensiGambar = strtolower(end($ekstensiGambar));

if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
    echo "<script>
        alert('EKSTENSI GAMBAR HARUS JPG, JPEG, PNG');
        window.location = '../tm_produk.php';
    </script>";
    die;
}

if ($size_gambar > 2000000) { // batas 2MB
    echo "<script>
        alert('UKURAN GAMBAR TERLALU BESAR');
        window.location = '../tm_produk.php';
    </script>";
    die;
}

// Generate nama gambar baru yang unik
$namaGambarBaru = uniqid() . '.' . $ekstensiGambar;

// Pindahkan gambar ke folder tujuan
if (move_uploaded_file($tmp_file, "../../image/produk/" . $namaGambarBaru)) {

    // [INI BAGIAN PENTING] Tambahkan kolom 'jumlah_stok' ke dalam query SQL
    $sql = "INSERT INTO produk (kode_produk, nama, image, deskripsi, harga, id_kategori, jumlah_stok) VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        // [INI BAGIAN PENTING] Sesuaikan tipe data di bind_param. Stok adalah integer (i)
        // 'ssssdii' = string, string, string, string, double/integer, integer, integer
        mysqli_stmt_bind_param($stmt, "ssssdii", $kode, $nama_produk, $namaGambarBaru, $deskripsi, $harga, $id_kategori, $jumlah_stok);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>
                alert('PRODUK BERHASIL DITAMBAHKAN');
                window.location = '../m_produk.php';
            </script>";
        } else {
            echo "<script>alert('Gagal menambahkan produk ke database.'); window.history.back();</script>";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Terjadi kesalahan pada sistem. Error: " . mysqli_error($conn) . "'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Gagal mengupload gambar.'); window.history.back();</script>";
}

mysqli_close($conn);
