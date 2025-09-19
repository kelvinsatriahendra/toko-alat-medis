<?php
include 'header.php';
// Anda dapat menambahkan generator kode barang otomatis di sini jika perlu
?>

<div class="container" style="padding-bottom: 250px;">
    <h2 style="width: 100%; border-bottom: 4px solid gray"><b>Tambah Stok Barang</b></h2>
    <form action="proses/tambah_stok_proses.php" method="POST">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="kode_barang">Kode Barang</label>
                    <input type="text" class="form-control" id="kode_barang" placeholder="Masukkan Kode Barang" name="kode_barang" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="nama_barang">Nama Barang</label>
                    <input type="text" class="form-control" id="nama_barang" placeholder="Masukkan Nama Barang" name="nama_barang" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="jumlah_stok">Jumlah Stok</label>
                    <input type="number" class="form-control" id="jumlah_stok" placeholder="Jumlah Stok" name="jumlah_stok" required>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-success">Tambah Stok</button>
        <a href="stok_barang.php" class="btn btn-danger">Batal</a>
    </form>
</div>

<?php
include 'footer.php';
?>