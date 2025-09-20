<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Dompdf\Dompdf;

// Memuat autoloader dari Composer
require '../vendor/autoload.php';

// Buat instance PHPMailer
$mail = new PHPMailer(true);

// Pastikan formulir dikirimkan dengan metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Ambil data dari formulir
    $invoice = $_POST['invoice'] ?? '';
    $kode_customer = $_POST['kode_customer'] ?? '';
    $email_pembeli = $_POST['email_pembeli'] ?? '';
    $kode_produk = $_POST['kode_produk'] ?? '';
    $nama_produk = $_POST['nama_produk'] ?? '';
    $qty = $_POST['qty'] ?? 0;
    $harga = $_POST['harga'] ?? 0;
    $status = $_POST['status'] ?? '';
    $tanggal_pembelian = $_POST['tanggal_pembelian'] ?? '';
    $provinsi = $_POST['provinsi'] ?? '';
    $kota = $_POST['kota'] ?? '';
    $alamat = $_POST['alamat'] ?? '';
    $kode_pos = $_POST['kode_pos'] ?? '';

    // --- BLOK VALIDASI EMAIL ---
    if (empty($email_pembeli) || !filter_var($email_pembeli, FILTER_VALIDATE_EMAIL)) {
        echo "Message could not be sent. Error: Alamat email penerima kosong atau tidak valid.";
        exit;
    }
    // --- AKHIR BLOK VALIDASI ---

    // Buat HTML untuk lampiran PDF
    $html_pdf = '
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px 12px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
    <h2>Detail Invoice</h2>
    <table>
        <tr><th>Invoice</th><td>' . htmlspecialchars($invoice) . '</td></tr>
        <tr><th>Kode Customer</th><td>' . htmlspecialchars($kode_customer) . '</td></tr>
        <tr><th>Email Pembeli</th><td>' . htmlspecialchars($email_pembeli) . '</td></tr>
        <tr><th>Kode Produk</th><td>' . htmlspecialchars($kode_produk) . '</td></tr>
        <tr><th>Nama Produk</th><td>' . htmlspecialchars($nama_produk) . '</td></tr>
        <tr><th>Qty</th><td>' . htmlspecialchars($qty) . '</td></tr>
        <tr><th>Harga</th><td>Rp. ' . number_format($harga) . '</td></tr>
        <tr><th>Status</th><td>' . htmlspecialchars($status) . '</td></tr>
        <tr><th>Tanggal Pembelian</th><td>' . htmlspecialchars($tanggal_pembelian) . '</td></tr>
        <tr><th>Provinsi</th><td>' . htmlspecialchars($provinsi) . '</td></tr>
        <tr><th>Kota</th><td>' . htmlspecialchars($kota) . '</td></tr>
        <tr><th>Alamat</th><td>' . htmlspecialchars($alamat) . '</td></tr>
        <tr><th>Kode Pos</th><td>' . htmlspecialchars($kode_pos) . '</td></tr>
    </table>';

    // Buat PDF
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html_pdf);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $output = $dompdf->output();
    $filepath = '../pdf/generated_nota.pdf';
    file_put_contents($filepath, $output);

    // Buat konten email (HTML)
    $message = '
        <html>
        <head>
        <title>Nota Pembelian Anda</title>
        </head>
        <body>
        <h2>Nota Pembelian</h2>
        <p>Terima kasih atas pembelian Anda. Berikut adalah rincian pesanan Anda:</p>
        <hr>
        <h3>Rincian Pembelian</h3>
        <ul>
            <li><strong>Invoice:</strong> ' . htmlspecialchars($invoice) . '</li>
            <li><strong>Nama Produk:</strong> ' . htmlspecialchars($nama_produk) . '</li>
            <li><strong>Kuantitas:</strong> ' . htmlspecialchars($qty) . '</li>
            <li><strong>Harga:</strong> Rp. ' . number_format($harga) . '</li>
            <li><strong>Tanggal Pembelian:</strong> ' . htmlspecialchars($tanggal_pembelian) . '</li>
        </ul>
        <p>Dokumen lengkap (PDF) terlampir pada email ini.</p>
        </body>
        </html>
    ';

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'dafi.moyes@gmail.com';
        $mail->Password = 'wrbtqqvyvabqwbff'; // Ganti dengan sandi aplikasi yang sudah Anda buat
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        //Recipients
        $mail->setFrom('dafi.moyes@gmail.com', 'Toko Alat Medis');
        $mail->addAddress($email_pembeli, 'Customer');
        $mail->addReplyTo('info@example.com', 'Information');

        //Content
        $mail->isHTML(true);
        $mail->Subject = "Nota Pembelian Anda - Invoice #" . $invoice;
        $mail->Body = $message;
        $mail->AltBody = 'Berikut adalah nota pembelian Anda. Dokumen PDF terlampir.';
        $mail->addAttachment($filepath, "Nota-" . $invoice . ".pdf");

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
