<?php
include 'header.php';

// Pastikan pengguna sudah login dan ada kode customer di URL
if (!isset($_SESSION['kd_cs']) || !isset($_GET['kode'])) {
    echo "<script>alert('Akses tidak valid.'); window.location.href='user_login.php';</script>";
    exit();
}

$kode_customer = $_GET['kode'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLpQ3d/QzT7cT7r9T9yFwW5bNq9QpT8Lp+V5o3t4C6Gf7A7S8L8G7e3QzP6D5C..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        :root {
            --primary-color: #3f51b5;
            --bg-light: #f4f6f9;
            --green-color: #27ae60;
            --danger-color: #f44336;
        }

        /* [PERBAIKAN] Hapus styling flex dari body */
        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg-light);
            margin: 0;
        }

        /* [BARU] Buat container untuk konten */
        .page-container {
            padding: 40px 15px;
            display: flex;
            justify-content: center;
        }

        .password-card {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 450px;
        }
        .password-card h2 {
            font-size: 1.8em;
            font-weight: 600;
            color: var(--primary-color);
            margin-top: 0;
            margin-bottom: 30px;
            text-align: center;
        }
        .input-group { position: relative; margin-bottom: 25px; }
        .input-group label { display: block; margin-bottom: 8px; color: #555; font-weight: 500; }
        .password-container { position: relative; }
        .password-container input { width: 100%; padding: 12px 45px 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 16px; box-sizing: border-box; }
        .password-toggle { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #888; }
        .button-group { display: flex; gap: 15px; margin-top: 30px; }
        .btn { flex-grow: 1; padding: 12px; border-radius: 8px; border: none; font-size: 15px; font-weight: 500; cursor: pointer; text-decoration: none; text-align: center; text-transform: uppercase; }
        .btn-submit { background-color: var(--green-color); color: white; }
        .btn-cancel { background-color: var(--danger-color); color: white; }
    </style>
</head>
<body>

<div class="page-container">
    <div class="password-card">
        <h2>Ganti Password</h2>
        <form action="proses/update_password.php" method="POST">
            <input type="hidden" name="kode_customer" value="<?= htmlspecialchars($kode_customer); ?>">
            
            <div class="input-group">
                <label for="password_lama">Password Lama</label>
                <div class="password-container">
                    <input type="password" id="password_lama" name="password_lama" required autocomplete="current-password">
                    <span class="password-toggle" onclick="togglePasswordVisibility('password_lama')"><i class="far fa-eye"></i></span>
                </div>
            </div>
            
            <div class="input-group">
                <label for="password_baru">Password Baru</label>
                <div class="password-container">
                    <input type="password" id="password_baru" name="password_baru" required autocomplete="new-password">
                    <span class="password-toggle" onclick="togglePasswordVisibility('password_baru')"><i class="far fa-eye"></i></span>
                </div>
            </div>

            <div class="input-group">
                <label for="konfirmasi_password_baru">Konfirmasi Password Baru</label>
                <div class="password-container">
                    <input type="password" id="konfirmasi_password_baru" name="konfirmasi_password_baru" required autocomplete="new-password">
                    <span class="password-toggle" onclick="togglePasswordVisibility('konfirmasi_password_baru')"><i class="far fa-eye"></i></span>
                </div>
            </div>
            
            <div class="button-group">
                <a href="detail_akun.php" class="btn btn-cancel">Batal</a>
                <button type="submit" class="btn btn-submit">Simpan Password</button>
            </div>
        </form>
    </div>
</div>

<script>
    function togglePasswordVisibility(fieldId) {
        const passwordInput = document.getElementById(fieldId);
        const toggleIcon = passwordInput.parentNode.querySelector('.password-toggle i');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }
</script>

</body>
</html>
<?php 
include 'footer.php';
?>