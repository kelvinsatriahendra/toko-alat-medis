<!-- <?php
        include '../koneksi/koneksi.php';

        // Query untuk mendapatkan informasi pembelian terakhir dari tabel pesanan
        $query = mysqli_query($conn, "SELECT * FROM pesanan ORDER BY invoice DESC LIMIT 1");

        // Pastikan ada hasil dari query
        $nota = null;
        if (mysqli_num_rows($query) > 0) {
            // Ambil data nota
            $nota = mysqli_fetch_assoc($query);
        }
        ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Pembelian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #71C0BB;
            padding-bottom: 10px;
        }

        .header h2 {
            margin: 0;
            color: #71C0BB;
        }

        .invoice-details {
            margin-bottom: 20px;
        }

        .invoice-details table {
            width: 100%;
            border-collapse: collapse;
        }

        .invoice-details table td,
        .invoice-details table th {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }

        .invoice-details table th {
            background-color: #e9e9e9;
        }
        
        .invoice-details table td {
            font-weight: normal;
        }

        .actions {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }

        .btn {
            padding: 10px 20px;
            background-color: #71C0BB;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #5a9b97;
        }

        @media print {
            body {
                background-color: #fff;
            }
            .container {
                box-shadow: none;
                border: none;
            }
            .actions {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <?php if ($nota) : ?>
            <div class="header">
                <h2>Nota Pembelian</h2>
            </div>
            <div class="invoice-details">
                <table>
                    <tr>
                        <th>Invoice</th>
                        <td><?= $nota['invoice']; ?></td>
                    </tr>
                    <tr>
                        <th>Kode Customer</th>
                        <td><?= $nota['kode_customer']; ?></td>
                    </tr>
                    <tr>
                        <th>Kode Produk</th>
                        <td><?= $nota['kode_produk']; ?></td>
                    </tr>
                    <tr>
                        <th>Nama Produk</th>
                        <td><?= $nota['nama_produk']; ?></td>
                    </tr>
                    <tr>
                        <th>Qty</th>
                        <td><?= $nota['qty']; ?></td>
                    </tr>
                    <tr>
                        <th>Harga</th>
                        <td>Rp.<?= number_format($nota['harga']); ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td><?= $nota['status']; ?></td>
                    </tr>
                    <tr>
                        <th>Tanggal Pembelian</th>
                        <td><?= $nota['tanggal']; ?></td>
                    </tr>
                    <tr>
                        <th>Provinsi</th>
                        <td><?= $nota['provinsi']; ?></td>
                    </tr>
                    <tr>
                        <th>Kota</th>
                        <td><?= $nota['kota']; ?></td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td><?= $nota['alamat']; ?></td>
                    </tr>
                    <tr>
                        <th>Kode Pos</th>
                        <td><?= $nota['kode_pos']; ?></td>
                    </tr>
                </table>
            </div>
            <div class="actions">
                <a href="../Selesai.php" class="btn">Selesai</a>
                <button onclick="window.print()" class="btn">Print Nota</button>
            </div>
        <?php else : ?>
            <div class="header">
                <h2>Tidak Ada Nota</h2>
            </div>
            <p style="text-align: center;">Tidak ada nota yang ditemukan.</p>
        <?php endif; ?>
    </div>
</body>

</html> -->


<!-- KODE LAWAS 👆 -->

<?php
include '../koneksi/koneksi.php';

// Ambil nomor invoice dari URL
if (!isset($_GET['invoice'])) {
    die("Error: Nomor invoice tidak ditemukan.");
}
$invoice_id = mysqli_real_escape_string($conn, $_GET['invoice']);

// Query untuk mendapatkan semua item pesanan berdasarkan invoice
$query = mysqli_query($conn, "SELECT * FROM pesanan WHERE invoice = '$invoice_id'");
$pesanan_items = [];
$data_pengiriman = null;
while ($row = mysqli_fetch_assoc($query)) {
    $pesanan_items[] = $row;
    if (!$data_pengiriman) {
        $data_pengiriman = $row; // Ambil detail pengiriman dari item pertama
    }
}
?>
<!DOCTYPE html>
<html>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nota Pembelian - <?= htmlspecialchars($invoice_id); ?></title>

<style>
    /* Menggunakan font modern dari Google Fonts */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

    /* Variabel Warna untuk konsistensi */
    :root {
        --primary-color: #1e3a5f;
        /* Deep Navy Blue */
        --secondary-color: #27ae60;
        /* Emerald Green */
        --light-bg: #f8f9fa;
        /* Off-White */
        --text-dark: #343a40;
        --text-light: #6c757d;
        --border-color: #dee2e6;
        --shadow-color: rgba(0, 0, 0, 0.08);
    }

    /* Pengaturan dasar untuk body */
    body {
        font-family: 'Poppins', sans-serif;
        margin: 0;
        padding: 0;
        background-color: var(--light-bg);
        color: var(--text-dark);
        line-height: 1.6;
    }

    /* Container utama nota */
    .container {
        max-width: 800px;
        margin: 50px auto;
        padding: 40px;
        background-color: #fff;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        box-shadow: 0 10px 30px var(--shadow-color);
        transition: all 0.3s ease;
    }

    /* Header Nota */
    .header {
        text-align: center;
        margin-bottom: 30px;
        border-bottom: 2px solid var(--primary-color);
        padding-bottom: 15px;
    }

    .header h2 {
        margin: 0;
        color: var(--primary-color);
        font-weight: 700;
        font-size: 2em;
        letter-spacing: 1px;
    }

    /* Styling tabel agar lebih rapi */
    .invoice-details table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 25px;
    }

    .invoice-details table td,
    .invoice-details table th {
        padding: 12px 15px;
        border: 1px solid var(--border-color);
        text-align: left;
    }

    .invoice-details table th {
        background-color: #f2f2f2;
        font-weight: 600;
        color: var(--text-dark);
    }

    .invoice-details table td {
        font-weight: 400;
        color: var(--text-light);
    }

    .invoice-details table tr th:first-child {
        width: 35%;
        background-color: var(--light-bg);
    }

    .invoice-details table tr td:last-child {
        font-weight: 500;
        color: var(--text-dark);
    }

    .invoice-details tfoot th {
        font-size: 1.1em;
        font-weight: 700;
        color: var(--primary-color);
    }

    /* Bagian tombol Aksi */
    .actions {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px dashed var(--border-color);
    }

    .btn {
        padding: 12px 25px;
        border: 2px solid transparent;
        color: white;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        border-radius: 50px;
        text-decoration: none;
        transition: all 0.3s ease-in-out;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn:first-child {
        background-color: var(--primary-color);
    }

    .btn:first-child:hover {
        background-color: #fff;
        color: var(--primary-color);
        border-color: var(--primary-color);
        transform: translateY(-2px);
    }

    .btn:last-child {
        background-color: var(--secondary-color);
    }

    .btn:last-child:hover {
        background-color: #fff;
        color: var(--secondary-color);
        border-color: var(--secondary-color);
        transform: translateY(-2px);
    }

    /* Pengaturan untuk mode cetak/print */
    @media print {
        body {
            background-color: #fff;
        }

        .container {
            box-shadow: none;
            border: none;
            margin: 0;
            max-width: 100%;
            padding: 5px;
        }

        .actions {
            display: none;
        }
    }
</style>
</head>

<body>
    <div class="container">
        <?php if (!empty($pesanan_items)) : ?>
            <div class="header">
                <h2>Nota Pembelian</h2>
            </div>
            <div class="invoice-details">
                <table>
                    <tr>
                        <th>Invoice</th>
                        <td><?= $data_pengiriman['invoice']; ?></td>
                    </tr>
                    <tr>
                        <th>Kode Customer</th>
                        <td><?= $data_pengiriman['kode_customer']; ?></td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td><?= $data_pengiriman['tanggal']; ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td><?= $data_pengiriman['status']; ?></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="background-color:#e9e9e9; text-align:center;"><b>Detail Produk</b></td>
                    </tr>
                </table>
                <br>
                <table>
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th>Qty</th>
                            <th>Harga Satuan</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $grand_total = 0;
                        foreach ($pesanan_items as $item) {
                            $subtotal = $item['harga'] * $item['qty'];
                            $grand_total += $subtotal;
                            echo "<tr>";
                            echo "<td>" . $item['nama_produk'] . "</td>";
                            echo "<td>" . $item['qty'] . "</td>";
                            echo "<td>Rp." . number_format($item['harga']) . "</td>";
                            echo "<td>Rp." . number_format($subtotal) . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" style="text-align:right;">Grand Total</th>
                            <th>Rp.<?= number_format($grand_total); ?></th>
                        </tr>
                    </tfoot>
                </table>
                <br>
                <table>
                    <tr>
                        <td colspan="2" style="background-color:#e9e9e9; text-align:center;"><b>Alamat Pengiriman</b></td>
                    </tr>
                    <tr>
                        <th>Provinsi</th>
                        <td><?= $data_pengiriman['provinsi']; ?></td>
                    </tr>
                    <tr>
                        <th>Kota</th>
                        <td><?= $data_pengiriman['kota']; ?></td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td><?= $data_pengiriman['alamat']; ?></td>
                    </tr>
                    <tr>
                        <th>Kode Pos</th>
                        <td><?= $data_pengiriman['kode_pos']; ?></td>
                    </tr>
                </table>
            </div>
            <div class="actions">
                <a href="../selesai.php" class="btn">Selesai</a>
                <button onclick="window.print()" class="btn">Print Nota</button>
            </div>
        <?php else : ?>
            <div class="header">
                <h2>Nota Tidak Ditemukan</h2>
            </div>
            <p style="text-align: center;">Nota dengan nomor <?= htmlspecialchars($invoice_id); ?> tidak ditemukan.</p>
        <?php endif; ?>
    </div>
</body>

</html>