<?php
// include 'header_admin.php';
// include 'koneksi/koneksi.php';

// --- Ambil semua data pesanan yang perlu dikirim ---
// JOIN dengan tabel customer untuk mendapatkan nama dan alamat
$query = mysqli_query($conn, "SELECT pesanan.*, customer.nama as nama_customer, shipping.* FROM pesanan 
                             JOIN customer ON pesanan.kode_customer = customer.kode_customer
                             LEFT JOIN shipping ON pesanan.id_pesanan = shipping.id_pesanan
                             ORDER BY pesanan.tanggal DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Pengiriman Pesanan</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style> body { padding: 20px; } </style>
</head>
<body>

<div class="container">
    <h2>Kelola Pengiriman Pesanan</h2>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID Pesanan</th>
                <th>Nama Pelanggan</th>
                <th>Total</th>
                <th>Status Pengiriman</th>
                <th>Kurir & Resi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($query)): ?>
            <tr>
                <td><?= htmlspecialchars($row['id_pesanan']); ?></td>
                <td><?= htmlspecialchars($row['nama_customer']); ?></td>
                <td>Rp.<?= number_format($row['total_harga']); ?></td>
                <td>
                    <span class="label 
                        <?php 
                            switch($row['status_pengiriman']){
                                case 'Terkirim': echo 'label-success'; break;
                                case 'Sedang Diproses': echo 'label-warning'; break;
                                default: echo 'label-info';
                            }
                        ?>">
                        <?= htmlspecialchars($row['status_pengiriman']); ?>
                    </span>
                </td>
                <td>
                    <?= htmlspecialchars($row['kurir']); ?><br>
                    <strong><?= htmlspecialchars($row['nomor_resi']); ?></strong>
                </td>
                <td>
                    <form action="proses/update_shipping.php" method="POST" class="form-inline">
                        <input type="hidden" name="id_pesanan" value="<?= $row['id_pesanan']; ?>">
                        
                        <div class="form-group">
                           <input type="text" name="kurir" class="form-control" placeholder="Nama Kurir" value="<?= htmlspecialchars($row['kurir']); ?>">
                        </div>
                        <div class="form-group">
                           <input type="text" name="nomor_resi" class="form-control" placeholder="Nomor Resi" value="<?= htmlspecialchars($row['nomor_resi']); ?>">
                        </div>
                        <div class="form-group">
                           <select name="status" class="form-control">
                               <option value="Sedang Diproses" <?= ($row['status_pengiriman'] == 'Sedang Diproses') ? 'selected' : ''; ?>>Sedang Diproses</option>
                               <option value="Terkirim" <?= ($row['status_pengiriman'] == 'Terkirim') ? 'selected' : ''; ?>>Terkirim</option>
                               <option value="Selesai" <?= ($row['status_pengiriman'] == 'Selesai') ? 'selected' : ''; ?>>Selesai</option>
                           </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">Update</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>