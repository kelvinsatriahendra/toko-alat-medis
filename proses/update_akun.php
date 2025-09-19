<?php
session_start();
// [PERBAIKAN] Path ke file koneksi diubah dari '../../' menjadi '../'
include '../koneksi/koneksi.php'; 

// Pastikan user login dan request berasal dari method POST
if (!isset($_SESSION['kd_cs']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Akses tidak diizinkan.");
}

// 1. Ambil semua data dari form
$kode_customer = $_POST['kode_customer'];
$nama = $_POST['nama'];
$email = $_POST['email'];
$username = $_POST['username'];
$no_telp = $_POST['no_telp'];
$dob = $_POST['dob'];
$gender = $_POST['gender'];
$address = $_POST['address'];
$city = $_POST['city'];
$paypal_id = $_POST['paypal_id'];


// 2. Siapkan query UPDATE yang aman dengan Prepared Statement
$sql = "UPDATE customer SET 
            nama = ?, 
            email = ?, 
            username = ?, 
            no_telp = ?, 
            dob = ?, 
            gender = ?, 
            address = ?, 
            city = ?, 
            paypal_id = ? 
        WHERE kode_customer = ?";

$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    // 'ssssssssss' = 10 variabel string (9 untuk SET, 1 untuk WHERE)
    mysqli_stmt_bind_param($stmt, "ssssssssss",
        $nama,
        $email,
        $username,
        $no_telp,
        $dob,
        $gender,
        $address,
        $city,
        $paypal_id,
        $kode_customer // Ini untuk klausa WHERE
    );

    // 3. Eksekusi query dan berikan feedback
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>
            alert('Informasi akun berhasil diperbarui!');
            window.location.href = '../detail_akun.php';
        </script>";
    } else {
        echo "<script>
            alert('Gagal memperbarui informasi akun. Silakan coba lagi.');
            window.history.back(); // Kembali ke halaman edit jika gagal
        </script>";
    }

    mysqli_stmt_close($stmt);
} else {
    // Menampilkan pesan error jika query gagal disiapkan
    echo "<script>
        alert('Terjadi kesalahan pada sistem. Error: " . mysqli_error($conn) . "');
        window.history.back();
    </script>";
}

mysqli_close($conn);

?>