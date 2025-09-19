<?php
include 'header.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['kd_cs'])) {
    echo "<script>alert('Anda harus login untuk melihat halaman ini.'); window.location.href='user_login.php';</script>";
    exit();
}

$kode_customer = $_SESSION['kd_cs'];

// Mengambil data pelanggan dari database menggunakan prepared statement
$stmt = mysqli_prepare($conn, "SELECT * FROM customer WHERE kode_customer = ?");
mysqli_stmt_bind_param($stmt, "s", $kode_customer);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$customer = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);


// Jika data pelanggan tidak ditemukan, tampilkan pesan error
if (!$customer) {
    echo "<div class='container' style='padding: 50px;'><h2 class='text-center'>Data Akun Tidak Ditemukan</h2><p class='text-center'>Mohon maaf, terjadi kesalahan. Silakan coba lagi.</p></div>";
    include 'footer.php';
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Akun - Prima Medical Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLpQ3d/QzT7cT7r9T9yFwW5bNq9QpT8Lp+V5o3t4C6Gf7A7S8L8G7e3QzP6D5C..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        :root {
            --primary-color: #3f51b5;
            --bg-light: #f4f6f9;
            --text-dark: #333;
            --shadow-color: rgba(0, 0, 0, 0.1);
        }

        /* [PERBAIKAN] Hapus styling flex dari body */
        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg-light);
            margin: 0;
        }
        
        /* [BARU] Buat container untuk konten utama halaman */
        .page-container {
            padding: 40px 15px; /* Memberi jarak atas-bawah dan kiri-kanan */
            display: flex;
            justify-content: center;
        }

        .account-card {
            background: #fff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px var(--shadow-color);
            width: 100%;
            max-width: 700px;
        }
        .account-card h2 {
            font-size: 2em;
            font-weight: 600;
            color: var(--primary-color);
            margin-top: 0;
            margin-bottom: 10px;
            text-align: center;
        }
        .account-card p.subtitle {
            color: #777;
            margin-bottom: 30px;
            text-align: center;
        }
        .form-row {
            display: flex;
            gap: 20px;
            width: 100%;
            margin-bottom: 20px;
        }
        .info-group {
            flex: 1;
            text-align: left;
        }
        .info-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
        }
        .info-group .info-display {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
            background-color: #f9f9f9;
            color: var(--text-dark);
        }
        .button-group {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 30px;
        }
        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 10px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s, transform 0.2s;
        }
        .btn-edit {
            background-color: var(--primary-color);
            color: #fff;
        }
        .btn-edit:hover { background-color: #303f9f; }
        .btn-password {
            background-color: #fff;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
        }
        .btn-password:hover {
            background-color: var(--primary-color);
            color: #fff;
        }
        @media (max-width: 600px) {
            .form-row { flex-direction: column; gap: 20px; }
        }
    </style>
</head>
<body>

    <div class="page-container">

        <div class="account-card">
            <h2>Detail Akun</h2>
            <p class="subtitle">Lihat detail akun Anda di sini.</p>
            
            <div class="form-row">
                <div class="info-group">
                    <label>Nama :</label>
                    <div class="info-display"><?= htmlspecialchars($customer['nama'] ?? 'Tidak ada data'); ?></div>
                </div>
                <div class="info-group">
                    <label>E-mail :</label>
                    <div class="info-display"><?= htmlspecialchars($customer['email'] ?? 'Tidak ada data'); ?></div>
                </div>
            </div>
            
            <div class="form-row">
                <div class="info-group">
                    <label>Username :</label>
                    <div class="info-display"><?= htmlspecialchars($customer['username'] ?? 'Tidak ada data'); ?></div>
                </div>
                <div class="info-group">
                    <label>Contact no :</label>
                    <div class="info-display"><?= htmlspecialchars($customer['no_telp'] ?? 'Tidak ada data'); ?></div>
                </div>
            </div>
            
            <div class="form-row">
                <div class="info-group">
                    <label>Date of birth :</label>
                    <div class="info-display"><?= htmlspecialchars($customer['dob'] ?? 'Tidak ada data'); ?></div>
                </div>
                <div class="info-group">
                    <label>Gender :</label>
                    <div class="info-display"><?= htmlspecialchars($customer['gender'] ?? 'Tidak ada data'); ?></div>
                </div>
            </div>

            <div class="form-row">
                <div class="info-group">
                    <label>Address :</label>
                    <div class="info-display"><?= htmlspecialchars($customer['address'] ?? 'Tidak ada data'); ?></div>
                </div>
            </div>

            <div class="form-row">
                <div class="info-group">
                    <label>City :</label>
                    <div class="info-display"><?= htmlspecialchars($customer['city'] ?? 'Tidak ada data'); ?></div>
                </div>
                <div class="info-group">
                    <label>Pay-pal id :</label>
                    <div class="info-display"><?= htmlspecialchars($customer['paypal_id'] ?? 'Tidak ada data'); ?></div>
                </div>
            </div>

            <div class="button-group">
                <a href="edit_akun.php?kode=<?= htmlspecialchars($customer['kode_customer']); ?>" class="btn btn-edit">Edit Akun</a>
                <a href="ganti_password.php?kode=<?= htmlspecialchars($customer['kode_customer']); ?>" class="btn btn-password">Ganti Password</a>
            </div>
        </div>

    </div>

</body>
</html>
<?php 
include 'footer.php';
?>