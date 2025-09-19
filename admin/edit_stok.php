<?php
include 'header.php';

// Ambil kode produk dari URL
if (!isset($_GET['kode'])) {
    echo "<div class='container'><h1>Error: Kode produk tidak ditemukan.</h1></div>";
    include 'footer.php';
    exit;
}

$kode_produk = mysqli_real_escape_string($conn, $_GET['kode']);

// Ambil data produk dari database
$result = mysqli_query($conn, "SELECT * FROM produk WHERE kode_produk = '$kode_produk'");

// Cek apakah produk ada
if (mysqli_num_rows($result) == 0) {
    echo "<div class='container'><h1>Error: Produk dengan kode '$kode_produk' tidak ditemukan.</h1></div>";
    include 'footer.php';
    exit;
}

$produk = mysqli_fetch_assoc($result);

?>

<!-- [PENAMBAHAN] Google Fonts & Font Awesome untuk ikon -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8f9fa;
    }

    .card-custom {
        background-color: #fff;
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        padding: 30px;
        margin-top: 30px;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .page-title {
        color: #333;
        border-bottom: 3px solid #0d6efd;
        padding-bottom: 10px;
        margin-bottom: 25px;
        font-weight: 600;
    }

    .form-label {
        font-weight: 500;
    }
</style>

<div class="container">
    <div class="card-custom">
        <h2 class="page-title">Update Stok Barang</h2>

        <h4><?= htmlspecialchars($produk['nama']); ?></h4>
        <p>Kode Produk: <strong><?= htmlspecialchars($produk['kode_produk']); ?></strong></p>

        <hr>

        <form action="proses/proses_update_stok.php" method="POST">
            <!-- Kirim kode produk secara tersembunyi -->
            <input type="hidden" name="kode_produk" value="<?= htmlspecialchars($produk['kode_produk']); ?>">

            <div class="form-group">
                <label for="jumlah_stok" class="form-label">Jumlah Stok Baru</label>
                <input type="number" class="form-control" id="jumlah_stok" name="jumlah_stok"
                    value="<?= htmlspecialchars($produk['jumlah_stok']); ?>"
                    required
                    min="0">
                <small class="form-text text-muted">Masukkan jumlah stok total yang baru. Bukan jumlah penambahan.</small>
            </div>

            <br>

            <button type="submit" name="update_stok" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
            <a href="m_stok.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

<br><br>

<?php
include 'footer.php';
?>