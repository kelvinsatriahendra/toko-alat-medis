<?php
include 'header.php';
$date = date('Y-m-d');

$date1 = isset($_POST['date1']) ? $_POST['date1'] : $date;
$date2 = isset($_POST['date2']) ? $_POST['date2'] : $date;
?>
<style>
    :root {
        --primary-color: #1e3a5f;
        --secondary-color: #27ae60;
        --accent-color: #f39c12;
        --light-bg: #f5f7fa;
        --text-dark: #333;
        --shadow-light: rgba(0, 0, 0, 0.1);
    }

    body {
        font-family: 'Montserrat', sans-serif;
        color: var(--text-dark);
        background-color: var(--light-bg);
    }

    .report-container {
        padding-top: 30px;
        padding-bottom: 50px;
    }

    .report-title {
        font-size: 2.2em;
        font-weight: 700;
        color: var(--primary-color);
        border-bottom: 3px solid var(--accent-color);
        padding-bottom: 10px;
        margin-bottom: 30px;
    }

    .filter-section {
        background-color: #fff;
        padding: 25px;
        border-radius: 8px;
        box-shadow: 0 4px 15px var(--shadow-light);
        margin-bottom: 30px;
    }

    .form-control {
        border-radius: 8px;
        border: 1px solid #ddd;
        padding: 10px;
        transition: border-color 0.3s;
    }

    .btn {
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .btn-success {
        background-color: var(--secondary-color);
        border-color: var(--secondary-color);
    }

    .btn-default {
        background-color: #f0f0f0;
        border-color: #ccc;
    }

    .table-responsive {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 15px var(--shadow-light);
    }

    .table-striped>tbody>tr:nth-of-type(odd) {
        background-color: #f9f9f9;
    }

    .table-striped>tbody>tr>td,
    .table-striped>thead>tr>th {
        padding: 12px;
    }

    .text-right {
        text-align: right;
    }

    @media print {
        .print-area {
            display: none;
        }
    }
</style>

<div class="container report-container">
    <h2 class="report-title">Laporan Penjualan</h2>

    <div class="row print-area">
        <div class="col-md-12">
            <div class="filter-section">
                <div class="row">
                    <div class="col-md-6">
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                            <div class="d-flex align-items-center">
                                <label for="date1" class="me-2">Dari:</label>
                                <input type="date" name="date1" class="form-control me-2" value="<?= $date1; ?>">
                                <label for="date2" class="me-2">Sampai:</label>
                                <input type="date" name="date2" class="form-control me-2" value="<?= $date2; ?>">
                                <button type="submit" name="submit" class="btn btn-primary">Tampilkan</button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6 text-right">
                        <!-- Hanya tombol Cetak yang tersisa -->
                        <a href="#" onclick="window.print()" class="btn btn-default"><i class="glyphicon glyphicon-print"></i> Cetak</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (isset($_POST['submit']) && !empty($date1) && !empty($date2)) { ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Produk</th>
                        <th>Tanggal</th>
                        <th>Qty</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = mysqli_query($conn, "SELECT * FROM pesanan WHERE terima = 1 AND tanggal BETWEEN '$date1' AND '$date2'");
                    $no = 1;
                    $total_qty = 0;
                    while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                        <tr>
                            <td><?= $no; ?></td>
                            <td><?= $row['nama_produk']; ?></td>
                            <td><?= $row['tanggal']; ?></td>
                            <td><?= $row['qty']; ?></td>
                        </tr>
                    <?php
                        $total_qty += $row['qty'];
                        $no++;
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-right"><b>Total Jumlah terjual:</b></td>
                        <td><b><?= $total_qty; ?></b></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    <?php } else { ?>
        <div class="text-center p-5">
            <p class="text-muted">Silakan pilih tanggal untuk menampilkan laporan.</p>
        </div>
    <?php } ?>
</div>

<?php
include 'footer.php';
?>
