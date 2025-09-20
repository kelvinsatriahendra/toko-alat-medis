<?php
// Aktifkan error reporting untuk debugging lokal
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Koneksi DB dan composer autoload
include '../koneksi/koneksi.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/env.php';
require_once __DIR__ . '/nota_template.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dompdf\Dompdf;
use Dompdf\Options;

// Validasi parameter invoice
if (!isset($_GET['invoice']) || trim($_GET['invoice']) === '') {
    echo "<script>alert('Parameter invoice tidak ditemukan.'); window.history.back();</script>";
    exit;
}

$invoice = mysqli_real_escape_string($conn, $_GET['invoice']);

// Muat .env dari root project
loadEnv(dirname(__DIR__));

// Ambil data pesanan untuk mendapatkan email penerima dan detail lain
$q = mysqli_query($conn, "SELECT * FROM pesanan WHERE invoice = '$invoice'");
if (!$q || mysqli_num_rows($q) === 0) {
    echo "<script>alert('Data pesanan tidak ditemukan untuk invoice $invoice.'); window.history.back();</script>";
    exit;
}

$firstRow = mysqli_fetch_assoc($q);
$emailCustomer = $firstRow['email_pembeli'];
$namaCustomer = $firstRow['kode_customer']; // Jika ada kolom nama, silakan ganti sesuai skema.

// 1) Generate HTML nota dari template
$html = getInvoiceHTML($invoice, $conn);

// 2) Render PDF dengan Dompdf
$options = new Options();
$options->set('isRemoteEnabled', true); // jika ada asset eksternal
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$pdfOutput = $dompdf->output();
$pdfFilename = "Nota-{$invoice}.pdf";

// 3) Siapkan dan kirim email lewat PHPMailer
$mail = new PHPMailer(true);

try {
    // --- KONFIGURASI SMTP via .env ---
    // MODE: pilih provider melalui MAIL_PROVIDER = 'mailtrap' atau 'gmail'
    $provider   = strtolower(getenv('MAIL_PROVIDER') ?: '');
    $secondary  = strtolower(getenv('MAIL_SECONDARY_PROVIDER') ?: '');
    $sendBoth   = (int)(getenv('MAIL_SEND_BOTH') ?: 0) === 1; // kirim ke keduanya jika 1

    if ($provider === 'gmail') {
        // Gunakan default Gmail, abaikan MAIL_HOST/PORT jika belum diisi
        $smtpHost   = 'smtp.gmail.com';
        $smtpPort   = 587;
        $encryption = 'tls';
        // Baca kredensial khusus Gmail terlebih dahulu, fallback ke generic
        $smtpUser   = getenv('GMAIL_USERNAME') ?: getenv('MAIL_USERNAME') ?: '';
        $smtpPass   = getenv('GMAIL_PASSWORD') ?: getenv('MAIL_PASSWORD') ?: '';
    } elseif ($provider === 'mailtrap' || $provider === '') {
        // Default ke Mailtrap Sandbox
        $smtpHost   = getenv('MAIL_HOST') ?: 'sandbox.smtp.mailtrap.io';
        $smtpPort   = (int)(getenv('MAIL_PORT') ?: 2525);
        $encryption = strtolower(getenv('MAIL_ENCRYPTION') ?: 'tls');
        // Baca kredensial khusus Mailtrap terlebih dahulu, fallback ke generic
        $smtpUser   = getenv('MAILTRAP_USERNAME') ?: getenv('MAIL_USERNAME') ?: '';
        $smtpPass   = getenv('MAILTRAP_PASSWORD') ?: getenv('MAIL_PASSWORD') ?: '';
    } else {
        // Provider lain: baca apa adanya dari .env
        $smtpHost   = getenv('MAIL_HOST') ?: '';
        $smtpPort   = (int)(getenv('MAIL_PORT') ?: 0);
        $encryption = strtolower(getenv('MAIL_ENCRYPTION') ?: 'tls');
        $smtpUser   = getenv('MAIL_USERNAME') ?: '';
        $smtpPass   = getenv('MAIL_PASSWORD') ?: '';
    }

    $smtpSecure = $encryption === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;

    if ($smtpUser === '' || $smtpPass === '') {
        echo "<script>alert('MAIL_USERNAME atau MAIL_PASSWORD belum diisi di .env. Isi sesuai MAIL_PROVIDER yang dipilih (gmail/mailtrap).'); window.history.back();</script>";
        exit;
    }

    $mail->isSMTP();
    // Debug SMTP opsional via .env: MAIL_DEBUG=1..4
    $debugLevel = (int)(getenv('MAIL_DEBUG') ?: 0);
    if ($debugLevel > 0) {
        $mail->SMTPDebug = $debugLevel; // 1-4
        $mail->Debugoutput = 'html';
    }
    $mail->Host       = $smtpHost;
    $mail->SMTPAuth   = true;
    $mail->Username   = $smtpUser;
    $mail->Password   = $smtpPass;
    $mail->SMTPSecure = $smtpSecure;
    $mail->Port       = $smtpPort;

    // From/Reply
    $fromAddress = getenv('MAIL_FROM_ADDRESS') ?: 'no-reply@primamedicalstore.test';
    $fromName    = getenv('MAIL_FROM_NAME') ?: 'Prima Medical Store';
    $replyAddr   = getenv('MAIL_REPLYTO_ADDRESS') ?: $fromAddress;
    $replyName   = getenv('MAIL_REPLYTO_NAME') ?: $fromName;

    // Jika menggunakan Gmail SMTP, From wajib sama dengan akun Gmail (untuk menghindari SPF/DMARC)
    $isGmail = $provider === 'gmail' || stripos($smtpHost, 'smtp.gmail.com') !== false;
    if ($isGmail && !empty($smtpUser)) {
        $fromAddress = $smtpUser;
        if (empty($replyAddr)) {
            $replyAddr = $fromAddress;
        }
    }

    $mail->setFrom($fromAddress, $fromName);
    $mail->addReplyTo($replyAddr, $replyName);

    // Penerima
    $mail->addAddress($emailCustomer, $namaCustomer);

    // Lampiran PDF
    $mail->addStringAttachment($pdfOutput, $pdfFilename, 'base64', 'application/pdf');

    // Konten email
    $mail->isHTML(true);
    $mail->Subject = "Nota Pembelian $invoice - Prima Medical Store";

    // Body versi HTML (tanpa tautan, hanya pemberitahuan lampiran PDF)
    $mail->Body =
        '<div style="font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#333">'
        . '<p>Halo,</p>'
        . '<p>Terima kasih telah melakukan pemesanan di <b>Prima Medical Store</b>.</p>'
        . '<p>Terlampir <b>Nota Pembelian</b> untuk invoice <b>' . htmlspecialchars($invoice) . '</b> dalam bentuk PDF.</p>'
        . '<p>Silakan unduh dan simpan sebagai arsip Anda.</p>'
        . '<br><p>Hormat kami,<br>Prima Medical Store</p>'
        . '</div>';

    // Body versi plain text
    $mail->AltBody = "Terima kasih telah berbelanja di Prima Medical Store. Nota pembelian untuk invoice $invoice terlampir dalam email ini.";

    $mail->send();

    // Jika diminta kirim ke dua provider sekaligus (mis. log di Mailtrap & kirim real via Gmail)
    if ($sendBoth && $secondary && $secondary !== $provider) {
        try {
            $provider2 = $secondary;
            // Konfigurasi mailer kedua
            $mail2 = new PHPMailer(true);

            if ($provider2 === 'gmail') {
                $smtpHost2 = 'smtp.gmail.com';
                $smtpPort2 = 587;
                $encryption2 = 'tls';
                $smtpUser2 = getenv('GMAIL_USERNAME') ?: getenv('MAIL_USERNAME') ?: '';
                $smtpPass2 = getenv('GMAIL_PASSWORD') ?: getenv('MAIL_PASSWORD') ?: '';
            } elseif ($provider2 === 'mailtrap') {
                $smtpHost2 = getenv('MAIL_HOST') ?: 'sandbox.smtp.mailtrap.io';
                $smtpPort2 = (int)(getenv('MAIL_PORT') ?: 2525);
                $encryption2 = strtolower(getenv('MAIL_ENCRYPTION') ?: 'tls');
                $smtpUser2 = getenv('MAILTRAP_USERNAME') ?: getenv('MAIL_USERNAME') ?: '';
                $smtpPass2 = getenv('MAILTRAP_PASSWORD') ?: getenv('MAIL_PASSWORD') ?: '';
            } else {
                // Provider lain tidak diproses di mode dual
                $smtpHost2 = '';
                $smtpPort2 = 0;
                $encryption2 = 'tls';
                $smtpUser2 = '';
                $smtpPass2 = '';
            }

            if ($smtpUser2 && $smtpPass2 && $smtpHost2) {
                $smtpSecure2 = $encryption2 === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
                $mail2->isSMTP();
                $mail2->Host       = $smtpHost2;
                $mail2->SMTPAuth   = true;
                $mail2->Username   = $smtpUser2;
                $mail2->Password   = $smtpPass2;
                $mail2->SMTPSecure = $smtpSecure2;
                $mail2->Port       = $smtpPort2;

                // From/Reply kedua
                $fromAddress2 = getenv('MAIL_FROM_ADDRESS') ?: 'no-reply@primamedicalstore.test';
                $fromName2    = getenv('MAIL_FROM_NAME') ?: 'Prima Medical Store';
                $replyAddr2   = getenv('MAIL_REPLYTO_ADDRESS') ?: $fromAddress2;
                $replyName2   = getenv('MAIL_REPLYTO_NAME') ?: $fromName2;

                if ($provider2 === 'gmail' && !empty($smtpUser2)) {
                    $fromAddress2 = $smtpUser2;
                    if (empty($replyAddr2)) { $replyAddr2 = $fromAddress2; }
                }

                $mail2->setFrom($fromAddress2, $fromName2);
                $mail2->addReplyTo($replyAddr2, $replyName2);
                $mail2->addAddress($emailCustomer, $namaCustomer);
                $mail2->addStringAttachment($pdfOutput, $pdfFilename, 'base64', 'application/pdf');
                $mail2->isHTML(true);
                $mail2->Subject = "[Copy] Nota Pembelian $invoice - Prima Medical Store";
                $mail2->Body = $mail->Body;
                $mail2->AltBody = $mail->AltBody;
                $mail2->send();
            }
        } catch (Exception $e2) {
            // Abaikan kegagalan kirim kedua, karena kirim utama sudah sukses
        }
    }

    echo "<script>alert('Email nota berhasil dikirim ke $emailCustomer'); window.location.href='nota.php?invoice=" . addslashes($invoice) . "';</script>";
    exit;
} catch (Exception $e) {
    // Jika gagal dan ada secondary provider, coba fallback
    $secondary  = strtolower(getenv('MAIL_SECONDARY_PROVIDER') ?: '');
    if ($secondary && $secondary !== $provider) {
        try {
            $provider = $secondary; // gunakan provider fallback
            // (Duplikasi blok konfigurasi singkat)
            if ($provider === 'gmail') {
                $smtpHost   = 'smtp.gmail.com';
                $smtpPort   = 587;
                $encryption = 'tls';
                $smtpUser   = getenv('GMAIL_USERNAME') ?: getenv('MAIL_USERNAME') ?: '';
                $smtpPass   = getenv('GMAIL_PASSWORD') ?: getenv('MAIL_PASSWORD') ?: '';
            } elseif ($provider === 'mailtrap') {
                $smtpHost   = getenv('MAIL_HOST') ?: 'sandbox.smtp.mailtrap.io';
                $smtpPort   = (int)(getenv('MAIL_PORT') ?: 2525);
                $encryption = strtolower(getenv('MAIL_ENCRYPTION') ?: 'tls');
                $smtpUser   = getenv('MAILTRAP_USERNAME') ?: getenv('MAIL_USERNAME') ?: '';
                $smtpPass   = getenv('MAILTRAP_PASSWORD') ?: getenv('MAIL_PASSWORD') ?: '';
            } else {
                throw new Exception('Provider fallback tidak didukung');
            }

            $smtpSecure = $encryption === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = $smtpHost;
            $mail->SMTPAuth   = true;
            $mail->Username   = $smtpUser;
            $mail->Password   = $smtpPass;
            $mail->SMTPSecure = $smtpSecure;
            $mail->Port       = $smtpPort;

            $fromAddress = getenv('MAIL_FROM_ADDRESS') ?: 'no-reply@primamedicalstore.test';
            $fromName    = getenv('MAIL_FROM_NAME') ?: 'Prima Medical Store';
            $replyAddr   = getenv('MAIL_REPLYTO_ADDRESS') ?: $fromAddress;
            $replyName   = getenv('MAIL_REPLYTO_NAME') ?: $fromName;
            if ($provider === 'gmail' && !empty($smtpUser)) { $fromAddress = $smtpUser; if (empty($replyAddr)) $replyAddr = $fromAddress; }
            $mail->setFrom($fromAddress, $fromName);
            $mail->addReplyTo($replyAddr, $replyName);
            $mail->addAddress($emailCustomer, $namaCustomer);
            $mail->addStringAttachment($pdfOutput, $pdfFilename, 'base64', 'application/pdf');
            $mail->isHTML(true);
            $mail->Subject = "Nota Pembelian $invoice - Prima Medical Store";
            $mail->Body = '<div style="font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#333">'
                . '<p>Halo,</p>'
                . '<p>Terima kasih telah melakukan pemesanan di <b>Prima Medical Store</b>.</p>'
                . '<p>Terlampir <b>Nota Pembelian</b> untuk invoice <b>' . htmlspecialchars($invoice) . '</b> dalam bentuk PDF.</p>'
                . '<p>Silakan unduh dan simpan sebagai arsip Anda.</p>'
                . '<br><p>Hormat kami,<br>Prima Medical Store</p>'
                . '</div>';
            $mail->AltBody = "Terima kasih telah berbelanja di Prima Medical Store. Nota pembelian untuk invoice $invoice terlampir dalam email ini.";
            $mail->send();
            echo "<script>alert('Email nota berhasil dikirim (via provider fallback).'); window.location.href='nota.php?invoice=" . addslashes($invoice) . "';</script>";
            exit;
        } catch (Exception $ex2) {
            // Jika fallback juga gagal, laporkan error akhir
        }
    }

    $msg = 'Pengiriman email gagal: ' . (isset($mail) ? $mail->ErrorInfo : $e->getMessage());
    $fallbackFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $pdfFilename;
    @file_put_contents($fallbackFile, $pdfOutput);
    echo "<script>alert('" . addslashes($msg) . "\\nPDF disimpan sementara di: $fallbackFile'); window.history.back();</script>";
    exit;
}

?>

