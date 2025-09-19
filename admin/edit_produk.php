<?php
include 'header.php';

// Validasi dan ambil kode produk dari URL
if (isset($_GET['kode'])) {
	$kode_produk = mysqli_real_escape_string($conn, $_GET['kode']);

	// Ambil data produk spesifik
	$result_produk = mysqli_query($conn, "SELECT * FROM produk WHERE kode_produk = '$kode_produk'");
	$data = mysqli_fetch_assoc($result_produk);

	// Jika produk tidak ada, beri pesan
	if (!$data) {
		echo "<div class='container'><h2>Produk tidak ditemukan!</h2></div>";
		include 'footer.php';
		exit;
	}

	// Ambil semua kategori untuk dropdown
	$result_kategori = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
} else {
	// Jika tidak ada kode di URL, beri pesan
	echo "<div class='container'><h2>Kode produk tidak valid.</h2></div>";
	include 'footer.php';
	exit;
}
?>

<div class="container">
	<h2 style="width: 100%; border-bottom: 4px solid gray"><b>Edit Produk</b></h2>

	<form action="proses/edit_produk.php" method="POST" enctype="multipart/form-data">

		<!-- Input hidden untuk kode produk & gambar lama -->
		<input type="hidden" name="kode_produk" value="<?= htmlspecialchars($data['kode_produk']); ?>">
		<input type="hidden" name="gambar_lama" value="<?= htmlspecialchars($data['image']); ?>">

		<div class="form-group">
			<label>Gambar Saat Ini:</label><br>
			<img src="../image/produk/<?= htmlspecialchars($data['image']); ?>" width="100" class="img-thumbnail">
		</div>

		<div class="form-group">
			<label for="exampleInputFile">Ubah Gambar (Opsional)</label>
			<input type="file" id="exampleInputFile" name="files">
			<p class="help-block">Kosongkan jika tidak ingin mengubah gambar.</p>
		</div>

		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label>Kode Produk</label>
					<input type="text" class="form-control" disabled value="<?= htmlspecialchars($data['kode_produk']); ?>">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>Nama Produk</label>
					<input type="text" class="form-control" name="nama" placeholder="Masukkan Nama Produk" value="<?= htmlspecialchars($data['nama']); ?>" required>
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
							// Cek untuk menandai kategori yang aktif
							$selected = ($kategori['id_kategori'] == $data['id_kategori']) ? 'selected' : '';
							echo '<option value="' . htmlspecialchars($kategori['id_kategori']) . '" ' . $selected . '>' . htmlspecialchars($kategori['nama_kategori']) . '</option>';
						}
						?>
					</select>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label>Harga</label>
					<input type="number" class="form-control" name="harga" placeholder="Masukkan Harga" value="<?= htmlspecialchars($data['harga']); ?>" required>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label>Jumlah Stok</label>
					<input type="number" class="form-control" name="jumlah_stok" placeholder="Masukkan Jumlah Stok" value="<?= htmlspecialchars($data['jumlah_stok']); ?>" required min="0">
				</div>
			</div>
		</div>

		<div class="form-group">
			<label>Deskripsi</label>
			<textarea name="desk" class="form-control" rows="4"><?= htmlspecialchars($data['deskripsi']); ?></textarea>
		</div>
		<hr>

		<div class="row">
			<div class="col-md-6">
				<button type="submit" class="btn btn-success btn-block"><i class="glyphicon glyphicon-floppy-disk"></i> Simpan Perubahan</button>
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