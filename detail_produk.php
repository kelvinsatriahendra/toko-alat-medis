<?php
include 'header.php';
$kode_produk = mysqli_real_escape_string($conn, $_GET['produk']);
$result = mysqli_query($conn, "SELECT * FROM produk WHERE kode_produk = '$kode_produk'");
$row = mysqli_fetch_assoc($result);

// ======================================================================
// KODE TAMBAHAN UNTUK MENGAMBIL DATA ULASAN
// ======================================================================
// --- Ambil semua ulasan untuk produk ini, disesuaikan dengan database Anda ---
$query_ulasan = mysqli_query($conn, "SELECT ulasan.*, customer.nama 
                                     FROM ulasan 
                                     JOIN customer ON ulasan.id_customer = customer.kode_customer 
                                     WHERE id_produk = '$kode_produk' 
                                     ORDER BY tanggal_ulasan DESC");

// --- Hitung rata-rata rating dan jumlah ulasan ---
$query_avg_rating = mysqli_query($conn, "SELECT AVG(rating) as rata_rata, COUNT(id_ulasan) as jumlah 
                                         FROM ulasan 
                                         WHERE id_produk = '$kode_produk'");
$avg_data = mysqli_fetch_assoc($query_avg_rating);
$rata_rata_rating = round($avg_data['rata_rata'], 1);
$jumlah_ulasan = $avg_data['jumlah'];
// ======================================================================
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Playfair+Display:ital,wght@1,400;1,700&display=swap" rel="stylesheet">

<style>
    /* -------------------------------------
       ... (Kode CSS Asli Anda) ...
       ------------------------------------- */

    :root {
        --primary-color: #1e3a5f;
        /* Deep Navy Blue */
        --secondary-color: #27ae60;
        /* Emerald Green */
        --accent-color: #f39c12;
        /* Orange Gold */
        --light-bg: #f5f7fa;
        /* Off-White */
        --text-dark: #333;
        --text-light: #fefefe;
        --shadow-light: rgba(0, 0, 0, 0.08);
        --shadow-heavy: rgba(0, 0, 0, 0.15);
    }

    body {
        font-family: 'Montserrat', sans-serif;
        color: var(--text-dark);
        background-color: var(--light-bg);
        line-height: 1.6;
    }

    .main-container {
        padding-top: 50px;
        padding-bottom: 100px;
    }

    .section-title {
        font-size: 2.5em;
        font-weight: 700;
        color: var(--primary-color);
        border-bottom: 3px solid var(--accent-color);
        padding-bottom: 15px;
        margin-bottom: 40px;
        text-transform: uppercase;
        letter-spacing: 1.5px;
    }

    .product-detail-card {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 10px 30px var(--shadow-light);
        padding: 40px;
        margin-top: 20px;
    }

    .product-image-container {
        padding: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: var(--light-bg);
        border-radius: 10px;
    }

    .product-image {
        max-width: 100%;
        height: auto;
        display: block;
        transition: transform 0.3s ease;
    }

    .product-image:hover {
        transform: scale(1.05);
    }

    .product-info-section {
        padding-left: 30px;
    }

    .product-info-section h2 {
        font-size: 2.2em;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 10px;
    }

    .product-price {
        font-size: 1.8em;
        font-weight: 700;
        color: var(--secondary-color);
        margin-bottom: 20px;
    }

    .product-description {
        font-size: 1em;
        line-height: 1.8;
        color: var(--text-dark);
        margin-bottom: 30px;
        border-left: 3px solid var(--accent-color);
        padding-left: 15px;
    }

    .quantity-selector {
        display: flex;
        align-items: center;
        margin-bottom: 30px;
    }

    .quantity-selector label {
        font-weight: 600;
        margin-right: 15px;
        color: var(--primary-color);
    }

    .quantity-input {
        width: 80px;
        text-align: center;
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 8px;
    }

    .action-buttons {
        display: flex;
        gap: 15px;
    }

    .btn {
        padding: 12px 25px;
        border-radius: 50px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        border: 2px solid;
        transition: all 0.3s ease;
    }

    .btn-add-to-cart {
        background-color: var(--secondary-color);
        color: var(--text-light);
        border-color: var(--secondary-color);
    }

    .btn-add-to-cart:hover {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .btn-back {
        background-color: var(--accent-color);
        color: var(--text-light);
        border-color: var(--accent-color);
    }

    .btn-back:hover {
        background-color: #e67e22;
        border-color: #e67e22;
    }

    /* ================================== */
    /* KODE CSS TAMBAHAN UNTUK ULASAN     */
    /* ================================== */
    .rating-summary {
        font-size: 1.2em;
        font-weight: 600;
        color: #f39c12;
        margin-bottom: 20px;
        margin-top: -15px;
    }

    .reviews-container {
        margin-top: 60px;
        padding-top: 30px;
        border-top: 1px solid #eee;
    }

    .review-item {
        border-bottom: 1px solid #f0f0f0;
        padding: 20px 0;
    }

    .review-item:last-child {
        border-bottom: none;
    }

    .review-author {
        font-weight: 700;
        color: var(--primary-color);
    }

    .review-rating {
        color: #f39c12;
    }

    .review-form {
        margin-top: 30px;
        background-color: var(--light-bg);
        padding: 30px;
        border-radius: 10px;
    }

    .review-form h4 {
        color: var(--primary-color);
        font-weight: 700;
        margin-bottom: 20px;
    }
</style>

<div class="container main-container">
    <h2 class="section-title text-center">Detail Produk</h2>

    <div class="row product-detail-card">
        <div class="col-md-5">
            <div class="product-image-container">
                <img src="image/produk/<?= $row['image']; ?>" alt="<?= $row['nama']; ?>" class="product-image">
            </div>
        </div>

        <div class="col-md-7">
            <div class="product-info-section">
                <h2><?= $row['nama']; ?></h2>
                <p class="product-price">Rp.<?= number_format($row['harga']); ?></p>

                <div class="rating-summary">
                    <span>⭐ <?= $rata_rata_rating; ?>/5</span>
                    <span>(<?= $jumlah_ulasan; ?> ulasan)</span>
                </div>

                <p class="product-description">
                    <?= $row['deskripsi']; ?>
                </p>

                <form action="proses/add.php" method="GET">
                    <input type="hidden" name="kd_cs" value="<?= $kode_cs; ?>">
                    <input type="hidden" name="produk" value="<?= $kode_produk; ?>">
                    <input type="hidden" name="hal" value="2">

                    <div class="quantity-selector">
                        <label for="quantity">Jumlah:</label>
                        <input class="form-control quantity-input" type="number" min="1" name="jml" value="1">
                    </div>

                    <div class="action-buttons">
                        <?php
                        if (isset($_SESSION['user'])) {
                        ?>
                            <button type="submit" class="btn btn-add-to-cart"><i class="glyphicon glyphicon-shopping-cart"></i> Tambahkan ke Keranjang</button>
                        <?php
                        } else {
                        ?>
                            <a href="keranjang.php" class="btn btn-add-to-cart"><i class="glyphicon glyphicon-shopping-cart"></i> Tambahkan ke Keranjang</a>
                        <?php
                        }
                        ?>
                        <a href="index.php" class="btn btn-back"> Kembali Belanja</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row reviews-container">
        <div class="col-md-12">
            <h3 class="section-title" style="font-size: 2em; text-align:left; border:none; padding-bottom:0;">Ulasan Pelanggan</h3>

            <?php if (mysqli_num_rows($query_ulasan) > 0): ?>
                <?php while ($ulasan = mysqli_fetch_assoc($query_ulasan)): ?>
                    <div class="review-item">
                        <p class="review-author"><?= htmlspecialchars($ulasan['nama']); ?></p>
                        <p class="review-rating">Rating: <?= $ulasan['rating']; ?>/5 ⭐</p>
                        <p class="review-comment"><?= htmlspecialchars($ulasan['komentar']); ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Jadilah yang pertama mengulas produk ini!</p>
            <?php endif; ?>

            <?php if (isset($_SESSION['user'])): // Tampilkan form hanya jika user sudah login 
            ?>
                <div class="review-form">
                    <h4>Tulis Ulasan Anda</h4>
                    <form action="proses/proses_ulasan.php" method="POST">
                        <input type="hidden" name="id_produk" value="<?= $kode_produk; ?>">
                        <input type="hidden" name="id_customer" value="<?= $_SESSION['kd_cs']; // Pastikan session ini berisi kode_customer saat login 
                                                                        ?>">

                        <div class="form-group" style="margin-bottom:15px;">
                            <label for="rating">Rating (1-5):</label>
                            <input type="number" id="rating" name="rating" min="1" max="5" class="form-control" required>
                        </div>

                        <div class="form-group" style="margin-bottom:15px;">
                            <label for="komentar">Komentar:</label>
                            <textarea id="komentar" name="komentar" rows="4" class="form-control"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary" style="border-radius:5px; border-color:#337ab7;" onclick="this.disabled=true; this.innerText='Mengirim...'; this.form.submit();">Kirim Ulasan</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php
include 'footer.php';
?>