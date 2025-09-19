<?php
include 'header.php';
// Ambil kode customer jika sudah login, untuk digunakan nanti di JavaScript
$kode_cs_js = isset($_SESSION['kd_cs']) ? json_encode($_SESSION['kd_cs']) : 'null';
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Playfair+Display:ital,wght@1,400;1,700&display=swap" rel="stylesheet">

<style>
    /* -------------------------------------
        CSS Kustom - Desain UI Super Profesional
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

    /* --- Efek Transisi Global --- */
    .btn,
    .product-card {
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
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
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
        cursor: pointer;
        /* Tambahan agar terlihat bisa diklik */
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

    /* ================================================= */
    /* == CSS BARU UNTUK MODAL KUANTITAS == */
    /* ================================================= */
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
        animation: fadeIn 0.5s;
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
        transition: all 0.2s;
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

    /* Sembunyikan panah default dari input number */
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
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
            // Kumpulkan data kode produk dan stoknya ke dalam array
            $products_for_js[$row['kode_produk']] = [
                'stok' => $row['jumlah_stok']
            ];
        ?>
            <div class="col-sm-6 col-md-4">
                <div class="product-card">
                    <div class="product-image-container">
                        <img src="image/produk/<?= $row['image']; ?>" alt="<?= $row['nama']; ?>" class="product-image">
                    </div>
                    <div class="card-body">
                        <h3 class="product-name"><?= htmlspecialchars($row['nama']); ?></h3>
                        <p class="product-price">Rp.<?= number_format($row['harga']); ?></p>
                        <div class="product-actions">
                            <a href="detail_produk.php?produk=<?= $row['kode_produk']; ?>" class="btn btn-detail">Detail</a>

                            <button class="btn btn-cart open-modal-btn"
                                data-kode="<?= $row['kode_produk']; ?>"
                                data-nama="<?= htmlspecialchars($row['nama']); ?>"
                                data-image="image/produk/<?= $row['image']; ?>">
                                <!-- data-stok="<?= $row['jumlah_stok']; ?>">  -->
                                <i class="glyphicon glyphicon-shopping-cart"></i> Tambah
                            </button>




                        </div>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>
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
            <p id="stockInfo" style="color: #777; font-size: 0.9em; margin-top: -10px; height: 20px;"></p>
        </div>
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
    document.addEventListener('DOMContentLoaded', function() {

        // --- Variabel Global ---
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
        const stockInfo = document.getElementById('stockInfo'); // Elemen baru untuk info stok
        const allProductsData = <?= json_encode($products_for_js); ?>;


        // Variabel untuk menyimpan data produk yang sedang dipilih
        let selectedProductCode = null;
        let availableStock = 0; // Variabel baru untuk menyimpan stok
        // Ambil status login dari PHP
        const isUserLoggedIn = <?= $kode_cs_js !== 'null' ? 'true' : 'false' ?>;
        const customerCode = <?= $kode_cs_js; ?>;

        // --- Fungsi-fungsi ---
        // function openModal(e) {
        //     const button = e.currentTarget;
        //     // Ambil data dari atribut data-*
        //     selectedProductCode = button.dataset.kode;

        //     availableStock = parseInt(button.dataset.stok) || 0; // Ambil stok dari atribut data-stok
        //     stockInfo.textContent = `Stok tersedia: ${availableStock}`; // Tampilkan info stok
        //     const productName = button.dataset.nama;
        //     const productImage = button.dataset.image;

        //     // Masukkan data ke dalam modal
        //     modalImage.src = productImage;
        //     modalName.textContent = productName;
        //     qtyInput.value = 1; // Reset kuantitas ke 1 setiap kali modal dibuka
        //     qtyInput.max = availableStock; // Set nilai maksimum sesuai stok

        //     // Reset tampilan & fungsionalitas tombol
        //     stockInfo.style.color = '#777';
        //     processBtn.disabled = false;
        //     qtyInput.disabled = false;
        //     increaseQtyBtn.disabled = false;

        //     // Logika jika produk habis stok
        //     if (availableStock < 1) {
        //         stockInfo.textContent = 'Stok habis!';
        //         stockInfo.style.color = 'red';
        //         qtyInput.value = 0;
        //         qtyInput.disabled = true;
        //         processBtn.disabled = true; // Nonaktifkan tombol proses jika stok habis
        //         increaseQtyBtn.disabled = true;
        //     } else {
        //         stockInfo.textContent = `Stok tersedia: ${availableStock}`;
        //     }

        //     // Tampilkan modal
        //     modal.style.display = "block";
        // }
        function openModal(e) {
            const button = e.currentTarget;
            selectedProductCode = button.dataset.kode;

            // ============================================================
            // == PERUBAHAN UTAMA: Ambil stok dari "kamus" JavaScript ==
            // ============================================================
            const productInfo = allProductsData[selectedProductCode];
            availableStock = productInfo ? parseInt(productInfo.stok) : 0;

            const productName = button.dataset.nama;
            const productImage = button.dataset.image;

            // Masukkan data ke dalam modal
            modalImage.src = productImage;
            modalName.textContent = productName;
            qtyInput.value = 1;
            qtyInput.max = availableStock;

            // Reset tampilan & fungsionalitas tombol
            stockInfo.style.color = '#777';
            processBtn.disabled = false;
            qtyInput.disabled = false;
            increaseQtyBtn.disabled = false;
            decreaseQtyBtn.disabled = false;

            // Logika jika produk habis stok
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

        function closeModal() {
            modal.style.display = "none";
            selectedProductCode = null; // Hapus data produk terpilih
        }

        function changeQty(amount) {
            let currentValue = parseInt(qtyInput.value);

            let newValue = currentValue + amount;
            // if (currentValue + amount >= 1) {
            //     qtyInput.value = currentValue + amount;
            // }

            if (newValue < 1) return; // Batasi minimal 1

            // Batasi maksimal sesuai stok yang tersedia
            if (newValue > availableStock) {
                stockInfo.textContent = 'Kuantitas melebihi stok!';
                stockInfo.style.color = 'red';
                // Kembalikan pesan setelah 2 detik
                setTimeout(() => {
                    stockInfo.textContent = `Stok tersedia: ${availableStock}`;
                    stockInfo.style.color = '#777';
                }, 2000);
                return;
            }

            qtyInput.value = newValue;
        }

        function processToCart() {
            const qty = qtyInput.value;

            // Pengaman terakhir sebelum proses
            if (qty > availableStock) {
                alert('Maaf, kuantitas melebihi stok yang tersedia.');
                return;
            }
            if (qty < 1) {
                alert('Silakan masukkan kuantitas yang valid.');
                return;
            }

            if (isUserLoggedIn) {
                // Jika user login, arahkan ke proses add.php dengan kuantitas
                // PENTING: Pastikan file proses/add.php Anda bisa menerima parameter 'qty'
                window.location.href = `proses/add.php?produk=${selectedProductCode}&kd_cs=${customerCode}&hal=1&qty=${qty}`;
            } else {
                // Jika user belum login, arahkan ke halaman login
                // Anda bisa mengganti 'login.php' dengan halaman login Anda
                alert('Anda harus login terlebih dahulu untuk menambahkan produk ke keranjang.');
                window.location.href = 'login.php';
            }
        }

        // --- Event Listeners ---
        openModalBtns.forEach(btn => {
            btn.addEventListener('click', openModal);
        });

        closeBtn.addEventListener('click', closeModal);
        cancelBtn.addEventListener('click', closeModal);

        // Menutup modal jika user klik di luar area modal
        window.addEventListener('click', function(e) {
            if (e.target == modal) {
                closeModal();
            }
        });

        decreaseQtyBtn.addEventListener('click', () => changeQty(-1));
        increaseQtyBtn.addEventListener('click', () => changeQty(1));

        // Tambahan: Validasi saat user mengetik manual
        qtyInput.addEventListener('input', () => {
            let value = parseInt(qtyInput.value);
            if (value > availableStock) {
                qtyInput.value = availableStock; // Langsung set ke nilai maksimal
            } else if (value < 1) {
                qtyInput.value = 1;
            }
        });
        processBtn.addEventListener('click', processToCart);
    });
</script>