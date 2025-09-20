<?php
// Fungsi ini akan menghasilkan HTML untuk nota
function getInvoiceHTML($invoice_id, $conn)
{
    // Query untuk mendapatkan semua item pesanan berdasarkan invoice
    $query = mysqli_query($conn, "SELECT * FROM pesanan WHERE invoice = '$invoice_id'");
    if (mysqli_num_rows($query) == 0) {
        return "<h2>Nota Tidak Ditemukan</h2>";
    }

    $pesanan_items = [];
    $data_pengiriman = null;
    while ($row = mysqli_fetch_assoc($query)) {
        $pesanan_items[] = $row;
        if (!$data_pengiriman) {
            $data_pengiriman = $row; // Ambil detail pengiriman dari item pertama
        }
    }

    // Mulai menangkap output HTML ke dalam variabel
    ob_start();
?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="UTF-8">
        <title>Nota Pembelian - <?= htmlspecialchars($invoice_id); ?></title>
        <style>
            /* PASTE SEMUA CSS DARI NOTA.PHP LAMA ANDA DI SINI */
            /* Pastikan font 'Poppins' bisa diakses atau ganti dengan font dasar seperti 'Helvetica', 'Arial' */
            body {
                font-family: 'Helvetica', sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f8f9fa;
                color: #343a40;
                line-height: 1.6;
            }

            .container {
                max-width: 800px;
                margin: 50px auto;
                padding: 40px;
                background-color: #fff;
                border: 1px solid #dee2e6;
                border-radius: 12px;
            }

            .header {
                text-align: center;
                margin-bottom: 30px;
                border-bottom: 2px solid #1e3a5f;
                padding-bottom: 15px;
            }

            .header h2 {
                margin: 0;
                color: #1e3a5f;
                font-weight: 700;
                font-size: 2em;
            }

            .invoice-details table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 25px;
            }

            .invoice-details table td,
            .invoice-details table th {
                padding: 12px 15px;
                border: 1px solid #dee2e6;
                text-align: left;
            }

            .invoice-details table th {
                background-color: #f2f2f2;
                font-weight: 600;
            }

            .invoice-details tfoot th {
                font-size: 1.1em;
                font-weight: 700;
                color: #1e3a5f;
            }
        </style>
    </head>

    <body>
        <div class="container">
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
        </div>
    </body>

    </html>
<?php
    // Kembalikan HTML yang sudah ditangkap
    return ob_get_clean();
}
?>