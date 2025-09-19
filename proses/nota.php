<?php
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

</html>