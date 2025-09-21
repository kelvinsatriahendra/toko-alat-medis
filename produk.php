<?php
include 'header.php';

// ==============================================================================
// == LANGKAH 1: TAMBAHKAN LOGIKA DARI index.php (PENTING UNTUK JAVASCRIPT) ==
// ==============================================================================

// Ambil kode customer jika sudah login, untuk digunakan nanti di JavaScript
$kode_cs_js = isset($_SESSION['kd_cs']) ? json_encode($_SESSION['kd_cs']) : 'null';

// Ambil semua data stok produk untuk JavaScript di awal
$all_products_query = mysqli_query($conn, "SELECT kode_produk, jumlah_stok FROM produk");
$products_for_js = [];
while ($p_row = mysqli_fetch_assoc($all_products_query)) {
    $products_for_js[$p_row['kode_produk']] = ['stok' => $p_row['jumlah_stok']];
}

// ==============================================================================
// == BAGIAN INI ADALAH LOGIKA ASLI DARI produk.php (TETAP DIPERTAHANKAN) ==
// ==============================================================================

// Logika untuk mengambil kategori dan memfilter produk
$query_kategori = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");

// Cek apakah ada kategori yang dipilih dari URL
$id_kategori_terpilih = isset($_GET['kategori']) ? (int)$_GET['kategori'] : 0;

// Siapkan query dasar untuk mengambil produk
$query_produk_sql = "SELECT * FROM produk";

// Jika ada kategori yang dipilih, tambahkan filter WHERE
if ($id_kategori_terpilih > 0) {
    $query_produk_sql .= " WHERE id_kategori = '$id_kategori_terpilih'";
}

$result = mysqli_query($conn, $query_produk_sql);
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Playfair+Display:ital,wght@1,400;1,700&display=swap" rel="stylesheet">

<style>
    /* Menggunakan CSS yang sama persis dengan index.php untuk tampilan produk */
    :root {
        --primary-color: #1e3a5f;
        --secondary-color: #27ae60;
        --accent-color: #f39c12;
        --light-bg: #f5f7fa;
        --text-dark: #333;
        --text-light: #fefefe;
        --shadow-light: rgba(0, 0, 0, 0.08);
        --shadow-heavy: rgba(0, 0, 0, 0.15);
    }

    body {
        font-family: 'Montserrat', sans-serif;
    }

    .btn,
    .product-card {
        transition: all 0.3s ease-in-out;
    }

    .product-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 10px 30px var(--shadow-light);
        overflow: hidden;
        margin-bottom: 40px;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .product-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px var(--shadow-heavy);
    }

    .product-image-container {
        width: 100%;
        height: 250px;
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
        flex-grow: 1;
    }

    .product-details {
        flex-grow: 1;
    }

    .product-name {
        font-size: 1.2em;
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 10px;
        min-height: 50px;
    }

    .product-price {
        font-size: 1.5em;
        font-weight: 700;
        color: var(--secondary-color);
        margin-bottom: 25px;
    }

    .product-actions {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: auto;
    }

    .btn {
        padding: 10px 20px;
        border-radius: 50px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        border: 2px solid;
        cursor: pointer;
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

    /* CSS untuk Sidebar Kategori */
    .category-sidebar {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .category-sidebar h4 {
        margin-top: 0;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--secondary-color);
        font-weight: bold;
        color: var(--primary-color);
    }

    .category-sidebar ul {
        list-style: none;
        padding: 0;
    }

    .category-sidebar ul li a {
        display: block;
        padding: 10px 15px;
        text-decoration: none;
        color: #333;
        border-radius: 5px;
        margin-top: 5px;
        transition: all 0.2s;
    }

    .category-sidebar ul li a:hover {
        background-color: #f0f0f0;
    }

    .category-sidebar ul li a.active {
        background-color: var(--secondary-color);
        color: white;
        font-weight: bold;
    }

    /* CSS untuk Modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.6);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 10% auto;
        padding: 30px;
        border: 1px solid #888;
        width: 90%;
        max-width: 500px;
        border-radius: 15px;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2);
        text-align: center;
        position: relative;
    }

    .close-btn {
        color: #aaa;
        position: absolute;
        top: 15px;
        right: 25px;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close-btn:hover,
    .close-btn:focus {
        color: black;
    }

    .modal-product-image {
        max-width: 150px;
        margin-bottom: 20px;
    }

    .qty-control {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 15px;
        margin: 25px 0;
    }

    .qty-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 2px solid var(--primary-color);
        background-color: #fff;
        color: var(--primary-color);
        font-size: 1.5em;
        font-weight: bold;
        cursor: pointer;
    }

    .qty-btn:hover {
        background-color: var(--primary-color);
        color: #fff;
    }

    #modalProductQty {
        width: 70px;
        height: 40px;
        text-align: center;
        font-size: 1.4em;
        border: 1px solid #ccc;
        border-radius: 8px;
    }

    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>

<div class="container" style="margin-top: 40px; margin-bottom: 40px;">
    <h2 style="width: 100%; border-bottom: 4px solid var(--secondary-color); padding-bottom: 15px; margin-bottom: 30px;"><b>Produk Kami</b></h2>

    <div class="row">
        <div class="col-md-3">
            <div class="category-sidebar">
                <h4>Kategori</h4>
                <ul>
                    <li>
                        <a href="produk.php" class="<?= ($id_kategori_terpilih == 0) ? 'active' : ''; ?>">
                            Semua Produk
                        </a>
                    </li>
                    <?php mysqli_data_seek($query_kategori, 0); // Reset pointer query kategori 
                    ?>
                    <?php while ($kategori = mysqli_fetch_assoc($query_kategori)) : ?>
                        <li>
                            <a href="produk.php?kategori=<?= $kategori['id_kategori']; ?>" class="<?= ($id_kategori_terpilih == $kategori['id_kategori']) ? 'active' : ''; ?>">
                                <?= htmlspecialchars($kategori['nama_kategori']); ?>
                            </a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>

        <div class="col-md-9">
            <div class="row">
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                        <div class="col-sm-6 col-md-4">
                            <div class="product-card">
                                <div class="product-image-container">
                                    <img src="image/produk/<?= $row['image']; ?>" alt="<?= htmlspecialchars($row['nama']); ?>" class="product-image">
                                </div>
                                <div class="card-body">
                                    <div class="product-details">
                                        <h3 class="product-name"><?= htmlspecialchars($row['nama']); ?></h3>
                                        <p class="product-price">Rp.<?= number_format($row['harga']); ?></p>
                                    </div>
                                    <div class="product-actions">
                                        <a href="detail_produk.php?produk=<?= $row['kode_produk']; ?>" class="btn btn-detail">Detail</a>

                                        <button class="btn btn-cart open-modal-btn"
                                            data-kode="<?= $row['kode_produk']; ?>"
                                            data-nama="<?= htmlspecialchars($row['nama']); ?>"
                                            data-image="image/produk/<?= $row['image']; ?>">
                                            <i class="glyphicon glyphicon-shopping-cart"></i> Tambah
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo "<div class='col-md-12'><p class='text-center' style='margin-top: 20px;'>Tidak ada produk dalam kategori ini.</p></div>";
                }
                ?>
            </div>
        </div>
    </div>
</div>

<div id="qtyModal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <img src="" alt="Produk" id="modalProductImage" class="modal-product-image">
        <h3 id="modalProductName">Nama Produk</h3>
        <div class="qty-control">
            <button class="qty-btn" id="decreaseQty">-</button>
            <input type="number" id="modalProductQty" value="1" min="1">
            <button class="qty-btn" id="increaseQty">+</button>
        </div>
        <p id="stockInfo" style="color: #777; font-size: 0.9em; margin-top: -15px; margin-bottom: 25px; height: 20px;"></p>
        <div class="product-actions">
            <button class="btn btn-detail" id="cancelBtn">Batal</button>
            <button class="btn btn-cart" id="processBtn">Proses</button>
        </div>
    </div>
</div>

<?php
include 'footer.php';
?>

<script>
    // Variabel ini dibuat di PHP bagian atas, berisi stok semua produk
    const allProductsData = <?= json_encode($products_for_js); ?>;

    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('qtyModal');
        const openModalBtns = document.querySelectorAll('.open-modal-btn');
        const closeBtn = document.querySelector('.close-btn');
        const cancelBtn = document.getElementById('cancelBtn');
        const processBtn = document.getElementById('processBtn');
        const modalImage = document.getElementById('modalProductImage');
        const modalName = document.getElementById('modalProductName');
        const qtyInput = document.getElementById('modalProductQty');
        const decreaseQtyBtn = document.getElementById('decreaseQty');
        const increaseQtyBtn = document.getElementById('increaseQty');
        const stockInfo = document.getElementById('stockInfo');

        let selectedProductCode = null;
        let availableStock = 0;
        const isUserLoggedIn = <?= $kode_cs_js !== 'null' ? 'true' : 'false' ?>;
        const customerCode = <?= $kode_cs_js; ?>;

        function openModal(e) {
            const button = e.currentTarget;
            selectedProductCode = button.dataset.kode;

            const productInfo = allProductsData[selectedProductCode];
            availableStock = productInfo ? parseInt(productInfo.stok) : 0;

            const productName = button.dataset.nama;
            const productImage = button.dataset.image;

            modalImage.src = productImage;
            modalName.textContent = productName;
            qtyInput.value = 1;
            qtyInput.max = availableStock;

            stockInfo.style.color = '#777';
            processBtn.disabled = false;
            qtyInput.disabled = false;
            increaseQtyBtn.disabled = false;
            decreaseQtyBtn.disabled = false;

            if (availableStock < 1) {
                stockInfo.textContent = 'Stok habis!';
                stockInfo.style.color = 'red';
                qtyInput.value = 0;
                qtyInput.disabled = true;
                processBtn.disabled = true;
                increaseQtyBtn.disabled = true;
                decreaseQtyBtn.disabled = true;
            } else {
                stockInfo.textContent = `Stok tersedia: ${availableStock}`;
            }
            modal.style.display = "block";
        }

        function closeModal() {
            modal.style.display = "none";
            selectedProductCode = null;
        }

        function changeQty(amount) {
            let currentValue = parseInt(qtyInput.value);
            let newValue = currentValue + amount;
            if (newValue < 1) return;

            if (newValue > availableStock) {
                stockInfo.textContent = 'Kuantitas melebihi stok!';
                stockInfo.style.color = 'red';
                setTimeout(() => {
                    if (availableStock > 0) {
                        stockInfo.textContent = `Stok tersedia: ${availableStock}`;
                        stockInfo.style.color = '#777';
                    }
                }, 2000);
                return;
            }
            qtyInput.value = newValue;
        }

        function processToCart() {
            const qty = parseInt(qtyInput.value);
            if (qty > availableStock) {
                alert('Maaf, kuantitas melebihi stok yang tersedia.');
                return;
            }
            if (qty < 1) {
                alert('Silakan masukkan kuantitas yang valid.');
                return;
            }
            if (isUserLoggedIn) {
                window.location.href = `proses/add.php?produk=${selectedProductCode}&kd_cs=${customerCode}&hal=2&qty=${qty}`;
            } else {
                alert('Anda harus login terlebih dahulu untuk menambahkan produk ke keranjang.');
                window.location.href = 'login.php';
            }
        }

        openModalBtns.forEach(btn => {
            btn.addEventListener('click', openModal);
        });

        closeBtn.addEventListener('click', closeModal);
        cancelBtn.addEventListener('click', closeModal);

        window.addEventListener('click', function(e) {
            if (e.target == modal) {
                closeModal();
            }
        });

        decreaseQtyBtn.addEventListener('click', () => changeQty(-1));
        increaseQtyBtn.addEventListener('click', () => changeQty(1));

        qtyInput.addEventListener('input', () => {
            let value = parseInt(qtyInput.value);
            if (isNaN(value) || value < 1) {
                qtyInput.value = 1;
            } else if (value > availableStock) {
                qtyInput.value = availableStock;
            }
        });
        processBtn.addEventListener('click', processToCart);
    });
</script>