<?php 
    include 'header.php';

    // BAGIAN BARU: Logika untuk mengambil kategori dan memfilter produk
    $query_kategori = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");

    // Cek apakah ada kategori yang dipilih dari URL (misal: produk.php?kategori=1)
    $id_kategori_terpilih = isset($_GET['kategori']) ? (int)$_GET['kategori'] : 0;

    // Siapkan query dasar untuk mengambil produk
    $query_produk_sql = "SELECT * FROM produk";

    // Jika ada kategori yang dipilih, tambahkan filter WHERE ke query
    if ($id_kategori_terpilih > 0) {
        $query_produk_sql .= " WHERE id_kategori = '$id_kategori_terpilih'";
    }
    
    $result = mysqli_query($conn, $query_produk_sql);
?>

<style>
    .category-sidebar {
        background-color: #f9f9f9;
        padding: 20px;
        border-radius: 5px;
        border: 1px solid #ddd;
    }
    .category-sidebar h4 {
        margin-top: 0;
        padding-bottom: 10px;
        border-bottom: 2px solid #71C0BB;
        font-weight: bold;
    }
    .category-sidebar ul {
        list-style: none;
        padding: 0;
    }
    .category-sidebar ul li a {
        display: block;
        padding: 8px 12px;
        text-decoration: none;
        color: #333;
        border-radius: 4px;
    }
    .category-sidebar ul li a:hover {
        background-color: #eee;
    }
    /* Style untuk kategori yang sedang aktif/dipilih */
    .category-sidebar ul li a.active {
        background-color: #71C0BB;
        color: white;
        font-weight: bold;
    }
</style>


<div class="container" style="margin-top: 20px; margin-bottom: 20px;">
    <h2 style=" width: 100%; border-bottom: 4px solid #71C0BB"><b>Produk Kami</b></h2>

    <div class="row" style="margin-top: 20px;">

        <div class="col-md-3">
            <div class="category-sidebar">
                <h4>Kategori</h4>
                <ul>
                    <li>
                        <a href="produk.php" class="<?= ($id_kategori_terpilih == 0) ? 'active' : ''; ?>">
                            Semua Produk
                        </a>
                    </li>
                    <?php while($kategori = mysqli_fetch_assoc($query_kategori)): ?>
                        <li>
                            <a href="produk.php?kategori=<?= $kategori['id_kategori']; ?>" 
                            class="<?= ($id_kategori_terpilih == $kategori['id_kategori']) ? 'active' : ''; ?>">
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
                if(mysqli_num_rows($result) > 0){
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="col-sm-6 col-md-4">
                    <div class="thumbnail">
                        <img src="image/produk/<?= $row['image']; ?>" >
                        <div class="caption">
                            <h3><?= $row['nama'];  ?></h3>
                            <h4>Rp.<?= number_format($row['harga']); ?></h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <a href="detail_produk.php?produk=<?= $row['kode_produk']; ?>" class="btn btn-warning btn-block">Detail</a> 
                                </div>
                                <?php if(isset($_SESSION['kd_cs'])){ ?>
                                    <div class="col-md-6">
                                        <a href="proses/add.php?produk=<?= $row['kode_produk']; ?>&kd_cs=<?= $kode_cs; ?>&hal=1" class="btn btn-success btn-block" role="button"><i class="glyphicon glyphicon-shopping-cart"></i> Tambah</a>
                                    </div>
                                <?php 
                                } else {
                                ?>
                                    <div class="col-md-6">
                                        <a href="keranjang.php" class="btn btn-success btn-block" role="button"><i class="glyphicon glyphicon-shopping-cart"></i> Tambah</a>
                                    </div>
                                <?php 
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php 
                    }
                } else {
                    // Pesan jika tidak ada produk di kategori yang dipilih
                    echo "<div class='col-md-12'><p class='text-center' style='margin-top: 20px;'>Tidak ada produk dalam kategori ini.</p></div>";
                }
                ?>
            </div>
        </div>

    </div>
</div>

<?php 
    include 'footer.php';
?>