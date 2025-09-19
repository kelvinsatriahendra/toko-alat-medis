<?php
include 'header.php';
// generate kode produk otomatis
$kode = mysqli_query($conn, "SELECT kode_produk from produk order by kode_produk desc");
$data = mysqli_fetch_assoc($kode);
$num = substr($data['kode_produk'], 1, 4);
$add = (int) $num + 1;
if (strlen($add) == 1) {
    $format = "P000" . $add;
} else if (strlen($add) == 2) {
    $format = "P00" . $add;
} else if (strlen($add) == 3) {
    $format = "P0" . $add;
} else {
    $format = "P" . $add;
}

// Mengambil semua data kategori untuk ditampilkan di dropdown
$result_kategori = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");

?>

<div class="container">
    <h2 style="width: 100%; border-bottom: 4px solid gray"><b>Tambah Produk</b></h2>

    <form action="proses/tm_produk.php" method="POST" enctype="multipart/form-data">

        <div class="form-group">
            <label for="exampleInputFile">Pilih Gambar</label>
            <input type="file" id="exampleInputFile" name="files" required>
            <p class="help-block">Pilih Gambar untuk Produk (Format: jpg, jpeg, png)</p>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Kode Produk</label>
                    <input type="text" class="form-control" disabled value="<?= $format; ?>">
                    <input type="hidden" name="kode" value="<?= $format; ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Nama Produk</label>
                    <input type="text" class="form-control" placeholder="Masukkan Nama Produk" name="nama" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Kategori Produk</label>
                    <select name="id_kategori" class="form-control" required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php
                        while ($kategori = mysqli_fetch_assoc($result_kategori)) {
                        ?>
                            <option value="<?= htmlspecialchars($kategori['id_kategori']); ?>">
                                <?= htmlspecialchars($kategori['nama_kategori']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Harga</label>
                    <input type="number" class="form-control" placeholder="Contoh: 12000" name="harga" required>
                    <p class="help-block">Isi Harga tanpa Titik(.) atau Koma (,)</p>
                </div>
            </div>
            <!-- [PENAMBAHAN] Input untuk Jumlah Stok -->
            <div class="col-md-4">
                <div class="form-group">
                    <label>Jumlah Stok</label>
                    <input type="number" class="form-control" placeholder="Masukkan Stok Awal" name="jumlah_stok" required min="0">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="desk" class="form-control" rows="4"></textarea>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <button type="submit" class="btn btn-success btn-block"><i class="glyphicon glyphicon-plus-sign"></i> Tambah</button>
            </div>
            <div class="col-md-6">
                <a href="m_produk.php" class="btn btn-danger btn-block">Cancel</a>
            </div>
        </div>
    </form>
</div>
<br><br>

<?php
include 'footer.php';
?>