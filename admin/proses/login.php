<?php
session_start();
// Path ../../ karena file ini ada di dalam admin/proses/
include '../../koneksi/koneksi.php';

// Cek apakah request datang dari method POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Ambil data dari form (sesuai name di HTML: "user" dan "pass")
    $username = $_POST['user'];
    $password = $_POST['pass'];

    // 1. Siapkan query dengan prepared statement untuk mencegah SQL Injection
    $query = "SELECT * FROM admin WHERE username = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        // 2. Bind username ke query
        mysqli_stmt_bind_param($stmt, "s", $username);

        // 3. Eksekusi query
        mysqli_stmt_execute($stmt);

        // 4. Ambil hasilnya
        $result = mysqli_stmt_get_result($stmt);

        // 5. Cek apakah username ditemukan (hanya ada 1 baris)
        if (mysqli_num_rows($result) == 1) {
            $admin_data = mysqli_fetch_assoc($result);
            $hashed_password = $admin_data['password'];

            // 6. Verifikasi password yang diinput dengan hash di database
            if (password_verify($password, $hashed_password)) {
                // Password cocok! Login berhasil.
                
                // Set session
                $_SESSION['admin'] = $admin_data['username'];
                $_SESSION['nama_admin'] = $admin_data['nama_lengkap'];

                // Arahkan ke halaman utama admin
                header("Location: ../halaman_utama.php");
                exit();

            } else {
                // Password salah
                header("Location: ../index.php?error=1"); // Kembali ke login dengan notif error
                exit();
            }

        } else {
            // Username tidak ditemukan
            header("Location: ../index.php?error=1"); // Kembali ke login dengan notif error
            exit();
        }

        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);

} else {
    // Jika halaman diakses langsung, redirect ke login
    header("Location: ../index.php");
    exit();
}
?>

