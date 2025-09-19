<?php 
// Selalu mulai session di baris paling atas
session_start();
include '../koneksi/koneksi.php';

// 1. Ambil data dari form
$username = $_POST['username'];
$password = $_POST['pass'];

// 2. Gunakan Prepared Statement untuk keamanan (Mencegah SQL Injection)
// Siapkan query dengan placeholder (?)
$sql = "SELECT * FROM customer WHERE username = ?";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    // Ikat variabel $username ke placeholder sebagai string ("s")
    mysqli_stmt_bind_param($stmt, "s", $username);

    // Eksekusi statement
    mysqli_stmt_execute($stmt);

    // Ambil hasilnya
    $result = mysqli_stmt_get_result($stmt);

    // 3. Verifikasi user dan password dalam satu blok kondisi
    // Cek apakah ada user yang ditemukan
    if ($row = mysqli_fetch_assoc($result)) {
        
        // Jika user ada, verifikasi passwordnya
        if (password_verify($password, $row['password'])) {
            
            // Jika password cocok, login berhasil
            $_SESSION['user'] = $row['nama'];
            $_SESSION['kd_cs'] = $row['kode_customer'];
            
            // Arahkan ke halaman utama dan hentikan skrip
            header('location:../index.php');
            exit(); // PENTING: Hentikan eksekusi skrip setelah redirect

        }
    }

    // 4. Jika user tidak ditemukan ATAU password salah, tampilkan satu pesan error
    echo "
    <script>
        alert('USERNAME ATAU PASSWORD SALAH');
        window.location = '../user_login.php';
    </script>
    ";

    // Tutup statement
    mysqli_stmt_close($stmt);

} else {
    // Gagal menyiapkan query, bisa jadi ada error di SQL atau koneksi
    echo "
    <script>
        alert('Terjadi kesalahan pada sistem. Silakan coba lagi nanti.');
        window.location = '../user_login.php';
    </script>
    ";
}

?>