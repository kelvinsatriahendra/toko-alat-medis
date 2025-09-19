<?php
include 'header.php';
include '../koneksi/koneksi.php';

// Query ini mengelompokkan barang berdasarkan nomor invoice dari tabel pesanan Anda.
$query = "
    SELECT
        invoice,
        tanggal,
        status,
        kode_customer,
        SUM(harga * qty) AS total_harga
    FROM
        pesanan -- <<< NAMA TABEL SUDAH DISESUAIKAN
    GROUP BY
        invoice
    ORDER BY
        tanggal DESC
";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query Gagal: " . mysqli_error($conn));
}
?>

<div class="container">
    <h2 style="width: 100%; border-bottom: 4px solid gray"><b>Manajemen Pesanan</b></h2>
    <br>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Invoice</th>
                <th>Tanggal</th>
                <th>Kode Customer</th>
                <th>Total Harga</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($pesanan = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?= htmlspecialchars($pesanan['invoice']); ?></td>
                <td><?= htmlspecialchars($pesanan['tanggal']); ?></td>
                <td><?= htmlspecialchars($pesanan['kode_customer']); ?></td>
                <td>Rp <?= number_format($pesanan['total_harga']); ?></td>
                <td><span class="label label-warning"><?= htmlspecialchars($pesanan['status']); ?></span></td>
                <td>
                    <a href="detail_pesanan.php?invoice=<?= htmlspecialchars($pesanan['invoice']); ?>" class="btn btn-primary btn-sm">
                        <i class="glyphicon glyphicon-eye-open"></i> Detail Pesanan
                    </a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>