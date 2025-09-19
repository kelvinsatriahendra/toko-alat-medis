<?php
// ======================================================================
// PENGATURAN KONEKSI DATABASE
// ======================================================================
$nama_server = "localhost";
$nama_user = "root";
$password_db = "";
$nama_db = "toko_medis_prima"; // <-- PASTIKAN NAMA DATABASE SUDAH BENAR

// 1. BUAT KONEKSI KE DATABASE
$koneksi = mysqli_connect($nama_server, $nama_user, $password_db, $nama_db);
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// ======================================================================
// PEMROSESAN FORM
// ======================================================================

// 2. AMBIL DATA DARI FORM
$nama = $_POST['nama'];
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$konfirmasi = $_POST['konfirmasi'];
$no_telp = $_POST['telp']; 
$dob = !empty($_POST['dob']) ? $_POST['dob'] : NULL;
$gender = $_POST['gender'];
$address = $_POST['address'];
$city = $_POST['city'];
$paypal_id = $_POST['paypal_id'];

// 3. VALIDASI & KEAMANAN
if ($password !== $konfirmasi) {
    // Tampilkan pesan error dengan JavaScript dan kembali ke halaman sebelumnya
    echo "<script>alert('Password dan Konfirmasi tidak cocok!'); window.history.back();</script>";
    exit(); // Hentikan skrip
}
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// ---> [FIX 1] BUAT KODE CUSTOMER UNIK SECARA OTOMATIS <---
$kode_customer = 'CS' . strtoupper(uniqid());


// 4. PERINTAH INSERT BARU DENGAN KOLOM kode_customer
// ---> [FIX 2] TAMBAHKAN `kode_customer` KE DALAM DAFTAR KOLOM <---
$sql = "INSERT INTO customer (kode_customer, nama, username, email, password, no_telp, dob, gender, address, city, paypal_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; // Tambahkan satu '?'

$stmt = mysqli_prepare($koneksi, $sql);

// ---> [FIX 3] TAMBAHKAN $kode_customer KE BIND_PARAM <---
// Ubah tipe data menjadi "sssssssssss" (11 's') dan tambahkan variabelnya
mysqli_stmt_bind_param($stmt, "sssssssssss", 
    $kode_customer, // Variabel baru ditambahkan di sini
    $nama, 
    $username, 
    $email, 
    $hashed_password, 
    $no_telp,
    $dob, 
    $gender, 
    $address, 
    $city, 
    $paypal_id
);

// 5. EKSEKUSI DAN REDIRECT
if (mysqli_stmt_execute($stmt)) {
    // Registrasi berhasil, tampilkan pesan sukses dengan redirect
    echo "<script>
        alert('Registrasi Berhasil! Akun untuk " . htmlspecialchars($nama) . " telah dibuat.');
        window.location.href = '../user_login.php';
    </script>";

} else {
    // Registrasi gagal, tampilkan pesan error dengan redirect
    echo "<script>
        alert('Registrasi Gagal. Error: " . mysqli_stmt_error($stmt) . "');
        window.history.back();
    </script>";
}

// 6. TUTUP KONEKSI
mysqli_stmt_close($stmt);
mysqli_close($koneksi);

?>