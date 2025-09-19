<?php
include 'header.php';

// Validasi awal untuk memastikan parameter produk ada di URL
if (!isset($_GET['produk']) || empty($_GET['produk'])) {
    echo "<div class='container'><h3 class='text-danger'>❌ Kode produk tidak valid.</h3></div>";
    include 'footer.php';
    exit;
}

// 1. Ambil kode produk dari URL
$kode_produk = $_GET['produk'];

// 2. Siapkan query SQL yang sudah di-JOIN dengan tabel stok_barang
// Mengambil semua data dari tabel 'produk' dan kolom 'jumlah' dari tabel 'stok_barang'
$sql = "SELECT 
            produk.*, 
            stok_barang.jumlah 
        FROM 
            produk 
        LEFT JOIN 
            stok_barang ON produk.kode_produk = stok_barang.kode_produk 
        WHERE 
            produk.kode_produk = ?";

// 3. Eksekusi query dengan prepared statement untuk keamanan
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $kode_produk);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$produk = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// 4. Validasi jika produk tidak ditemukan di database
if (!$produk) {
    echo "<div class='container'><h3 class='text-danger'>❌ Produk tidak ditemukan!</h3></div>";
    include 'footer.php';
    exit;
}

// Menentukan stok, jika produk tidak ada di tabel stok_barang, anggap stoknya 0
$stok_produk = isset($produk['jumlah']) ? (int)$produk['jumlah'] : 0;
?>

<div class="container" style="margin-top: 30px; margin-bottom: 30px;">
    <div class="row">
        <div class="col-md-5 text-center">
            <img src="image/produk/<?= htmlspecialchars($produk['image']); ?>"
                class="img-responsive img-thumbnail"
                alt="<?= htmlspecialchars($produk['nama']); ?>"
                style="max-height: 350px; object-fit: contain;">
        </div>

        <div class="col-md-7">
            <h2><?= htmlspecialchars($produk['nama']); ?></h2>
            <h3 class="text-success">Rp <?= number_format($produk['harga'], 0, ',', '.'); ?></h3>
            <p><?= nl2br(htmlspecialchars($produk['deskripsi'])); ?></p>
            <p><strong>Stok:</strong> <?= $stok_produk; ?> item</p>

            <?php if (isset($_SESSION['kd_cs'])): ?>
                <?php if ($stok_produk > 0): ?>
                    <form action="proses/add.php" method="get" class="form-inline" style="margin-top: 20px;">
                        <input type="hidden" name="produk" value="<?= htmlspecialchars($produk['kode_produk']); ?>">

                        <div class="form-group">
                            <label for="jml">Jumlah:</label>
                            <input type="number" id="jml" name="jml" value="1"
                                min="1" max="<?= $stok_produk; ?>"
                                class="form-control"
                                style="width: 100px; margin-left: 10px; margin-right: 10px;">
                        </div>

                        <button type="submit" class="btn btn-success">
                            <i class="glyphicon glyphicon-shopping-cart"></i> Tambah ke Keranjang
                        </button>
                    </form>
                <?php else: ?>
                    <button class="btn btn-danger" disabled>Stok Habis</button>
                <?php endif; ?>
            <?php else: ?>
                <a href="user_login.php" class="btn btn-success">
                    <i class="glyphicon glyphicon-user"></i> Login untuk membeli
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>