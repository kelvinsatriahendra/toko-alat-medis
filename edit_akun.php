<?php
include 'header.php';

// Pastikan pengguna sudah login dan ada kode customer di URL
if (!isset($_SESSION['kd_cs']) || !isset($_GET['kode'])) {
    echo "<script>alert('Akses tidak valid.'); window.location.href='index.php';</script>";
    exit();
}

$kode_customer = $_GET['kode'];

// [FIX-KEAMANAN] Ambil data menggunakan Prepared Statement
$stmt = mysqli_prepare($conn, "SELECT * FROM customer WHERE kode_customer = ?");
mysqli_stmt_bind_param($stmt, "s", $kode_customer);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$customer = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);


if (!$customer) {
    echo "<script>alert('Data akun tidak ditemukan.'); window.location.href='detail_akun.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Akun</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #3f51b5;
            --secondary-color: #27ae60; /* Tombol simpan */
            --danger-color: #f44336;  /* Tombol batal */
            --bg-light: #f4f6f9;
        }
        
        /* [FIX-LAYOUT] Hapus styling flex dari body */
        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg-light);
            margin: 0;
        }

        /* [FIX-LAYOUT] Buat container untuk konten */
        .page-container {
            padding: 40px 15px;
            display: flex;
            justify-content: center;
        }

        .edit-card {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 750px;
        }
        .edit-card h2 {
            font-size: 2em;
            font-weight: 600;
            color: var(--primary-color);
            margin-top: 0;
            margin-bottom: 30px;
            text-align: center;
        }
        .form-row { display: flex; gap: 20px; margin-bottom: 20px; }
        .input-group { flex: 1; }
        .input-group label { display: block; margin-bottom: 8px; color: #555; font-weight: 500; }
        .input-group input, .input-group textarea, .input-group select { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 15px; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        .gender-options { display: flex; align-items: center; gap: 20px; height: 49px; }
        .button-group { display: flex; justify-content: flex-end; gap: 15px; margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px;}
        .btn { padding: 10px 25px; border-radius: 8px; border: none; font-size: 15px; font-weight: 500; cursor: pointer; text-decoration: none; text-transform: uppercase; }
        .btn-submit { background-color: var(--secondary-color); color: white; }
        .btn-cancel { background-color: var(--danger-color); color: white; }
    </style>
</head>
<body>

<div class="page-container">
    <div class="edit-card">
        <h2>Edit Informasi Akun</h2>
        
        <form action="proses/update_akun.php" method="POST" autocomplete="off">
            <input type="hidden" name="kode_customer" value="<?= htmlspecialchars($customer['kode_customer']); ?>">
            
            <div class="form-row">
                <div class="input-group">
                    <label for="nama">Nama Lengkap:</label>
                    <input type="text" id="nama" name="nama" value="<?= htmlspecialchars($customer['nama']); ?>" required>
                </div>
                <div class="input-group">
                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($customer['email']); ?>" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="input-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" value="<?= htmlspecialchars($customer['username']); ?>" required>
                </div>
                <div class="input-group">
                    <label for="no_telp">No. Telepon:</label>
                    <!-- [FIX-DATA] Ganti name dan value dari 'telp' menjadi 'no_telp' -->
                    <input type="tel" id="no_telp" name="no_telp" value="<?= htmlspecialchars($customer['no_telp']); ?>" pattern="\d*">
                </div>
            </div>
            
            <div class="form-row">
                <div class="input-group">
                    <label for="dob">Date of birth:</label>
                    <input type="date" id="dob" name="dob" value="<?= htmlspecialchars($customer['dob']); ?>">
                </div>
                <div class="input-group">
                    <label>Gender:</label>
                    <div class="gender-options">
                        <input type="radio" id="male" name="gender" value="Male" <?= ($customer['gender'] == 'Male') ? 'checked' : ''; ?>>
                        <label for="male">Male</label>
                        <input type="radio" id="female" name="gender" value="Female" <?= ($customer['gender'] == 'Female') ? 'checked' : ''; ?>>
                        <label for="female">Female</label>
                    </div>
                </div>
            </div>

            <div class="input-group" style="margin-bottom: 20px;">
                <label for="address">Address:</label>
                <textarea id="address" name="address" rows="3"><?= htmlspecialchars($customer['address']); ?></textarea>
            </div>

            <div class="form-row">
                <div class="input-group">
                    <label for="city">City:</label>
                    <select id="city" name="city">
                        <option value="Jakarta" <?= ($customer['city'] == 'Jakarta') ? 'selected' : ''; ?>>Jakarta</option>
                        <option value="Surabaya" <?= ($customer['city'] == 'Surabaya') ? 'selected' : ''; ?>>Surabaya</option>
                    </select>
                </div>
                <div class="input-group">
                    <label for="paypal_id">Pay-pal id:</label>
                    <input type="email" id="paypal_id" name="paypal_id" value="<?= htmlspecialchars($customer['paypal_id']); ?>">
                </div>
            </div>

            <div class="button-group">
                <a href="detail_akun.php" class="btn btn-cancel">Batal</a>
                <button type="submit" class="btn btn-submit">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
<?php 
include 'footer.php';
?>