<?php 
include 'header.php';
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Playfair+Display:ital,wght@1,400;1,700&display=swap" rel="stylesheet">

<style>
    /* -------------------------------------
       CSS Kustom - Desain UI Super Profesional
       ------------------------------------- */

    :root {
        --primary-color: #1e3a5f;      /* Deep Navy Blue */
        --secondary-color: #27ae60;    /* Emerald Green */
        --accent-color: #f39c12;       /* Orange Gold */
        --light-bg: #f5f7fa;           /* Off-White */
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

    /* --- Efek Transisi Global --- */
    .btn, .product-card {
        transition: all 0.3s ease-in-out;
    }

    /* --- Banner Utama --- */
    .main-banner {
        position: relative;
        height: 600px;
        overflow: hidden;
        margin-top: -21px;
    }
    .banner-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .banner-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        color: var(--text-light);
    }
    .banner-overlay h1 {
        font-family: 'Playfair Display', serif;
        font-size: 4.5em;
        font-weight: 700;
        text-shadow: 2px 2px 6px var(--primary-color);
        margin-bottom: 15px;
        animation: fadeIn 1.5s ease-in-out;
    }
    .banner-overlay p {
        font-size: 1.5em;
        font-weight: 400;
        font-style: italic;
        opacity: 0;
        animation: slideIn 1.5s ease-in-out 0.5s forwards;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* --- Tentang Kami --- */
    .about-us {
        padding: 80px 0;
    }
    .about-us h4 {
        line-height: 1.8;
        padding: 30px;
        border-top: 3px solid var(--secondary-color);
        border-bottom: 3px solid var(--secondary-color);
        color: var(--primary-color);
        font-weight: 500;
        font-size: 1.1em;
    }

    /* --- Bagian Produk --- */
    .product-section {
        padding: 60px 0;
    }
    .section-title {
        border-bottom: 3px solid var(--accent-color);
        padding-bottom: 15px;
        margin-bottom: 50px;
        font-weight: 700;
        font-size: 2.5em;
        color: var(--primary-color);
        text-transform: uppercase;
        letter-spacing: 1.5px;
    }
    .product-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 10px 30px var(--shadow-light);
        overflow: hidden;
        margin-bottom: 40px;
    }
    .product-card:hover {
        transform: translateY(-15px);
        box-shadow: 0 15px 40px var(--shadow-heavy);
    }
    .product-image-container {
        width: 100%;
        height: 280px;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }
    .product-image {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
    }
    .card-body {
        padding: 25px;
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .product-name {
        font-size: 1.3em;
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 10px;
    }
    .product-price {
        font-size: 1.6em;
        font-weight: 700;
        color: var(--secondary-color);
        margin-bottom: 25px;
    }
    .product-actions {
        display: flex;
        justify-content: center;
        gap: 15px;
    }
    .btn {
        padding: 12px 25px;
        border-radius: 50px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        border: 2px solid;
    }
    .btn-detail {
        background-color: transparent;
        color: var(--primary-color);
        border-color: var(--primary-color);
    }
    .btn-detail:hover {
        background-color: var(--primary-color);
        color: var(--text-light);
    }
    .btn-cart {
        background-color: var(--secondary-color);
        color: #fff;
        border-color: var(--secondary-color);
    }
    .btn-cart:hover {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
</style>

<div class="main-banner">
    <img src="image/home/1.jpg" alt="Prima Medical Store" class="banner-image">
    <div class="banner-overlay">
        <h1>Prima Medical Store</h1>
        <p>Pusat Peralatan Medis Terlengkap dan Terpercaya</p>
    </div>
</div>

<div class="container about-us">
    <h4 class="text-center">
        Prima Medical Store adalah salah satu pelopor dalam bisnis peralatan medis di Indonesia. Didirikan pada tahun 1998, saat ini dikelola di bawah PT. Prima Makmur Medika. Produk kami lengkap, teruji klinis, dan terjangkau.
    </h4>
</div>

<div class="container product-section">
    <h2 class="section-title text-center">Produk Kami</h2>
    <div class="row product-grid">
        <?php 
        $result = mysqli_query($conn, "SELECT * FROM produk");
        while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <div class="col-sm-6 col-md-4">
                <div class="product-card">
                    <div class="product-image-container">
                        <img src="image/produk/<?= $row['image']; ?>" alt="<?= $row['nama']; ?>" class="product-image">
                    </div>
                    <div class="card-body">
                        <h3 class="product-name"><?= $row['nama']; ?></h3>
                        <p class="product-price">Rp.<?= number_format($row['harga']); ?></p>
                        <div class="product-actions">
                            <a href="detail_produk.php?produk=<?= $row['kode_produk']; ?>" class="btn btn-detail">Detail</a> 
                            <?php if(isset($_SESSION['kd_cs'])){ ?>
                                <a href="proses/add.php?produk=<?= $row['kode_produk']; ?>&kd_cs=<?= $kode_cs; ?>&hal=1" class="btn btn-cart">
                                    <i class="glyphicon glyphicon-shopping-cart"></i> Tambah
                                </a>
                            <?php 
                            } else {
                                ?>
                                <a href="keranjang.php" class="btn btn-cart">
                                    <i class="glyphicon glyphicon-shopping-cart"></i> Tambah
                                </a>
                            <?php 
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
        }
        ?>
    </div>
</div>

<?php 
include 'footer.php';
?>