<?php
// Aktifkan error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../koneksi/koneksi.php';
// require '../vendor/autoload.php'; //SEMENTARA INI COMMAND JANGAN DIHAPUS

// 1. Ambil data dari form (gunakan mysqli_real_escape_string untuk keamanan)
// $kd_cs = $_POST['kode_cs'];
// $prov = $_POST['prov'];
// $kota = $_POST['kota'];
// $alamat = $_POST['almt'];
// $kopos = $_POST['kopos'];
// $tanggal = date('Y-m-d');
// $payment_method = $_POST['payment_method'];
// $email_pembeli = $_POST['email'];

// SAMPE SINII


$kd_cs = mysqli_real_escape_string($conn, $_POST['kode_cs']);
$prov = mysqli_real_escape_string($conn, $_POST['prov']);
$kota = mysqli_real_escape_string($conn, $_POST['kota']);
$alamat = mysqli_real_escape_string($conn, $_POST['almt']);
$kopos = mysqli_real_escape_string($conn, $_POST['kopos']);
$email_pembeli = mysqli_real_escape_string($conn, $_POST['email']);
$payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
$tanggal = date('Y-m-d');

// ==================================================================
// == MULAI TRANSAKSI DATABASE ==
// ==================================================================
mysqli_begin_transaction($conn);

try {
    // 2. Generate nomor invoice baru
    $result_inv = mysqli_query($conn, "SELECT invoice FROM pesanan ORDER BY invoice DESC LIMIT 1");
    $data_inv = mysqli_fetch_assoc($result_inv);
    $num = $data_inv ? substr($data_inv['invoice'], 3, 4) : '0000';
    $add = (int) $num + 1;
    $format_invoice = 'INV' . str_pad($add, 4, '0', STR_PAD_LEFT);

    // 3. Ambil semua data dari keranjang
    $keranjang = mysqli_query($conn, "SELECT * FROM cart WHERE kode_customer = '$kd_cs'");

    if (mysqli_num_rows($keranjang) == 0) {
        throw new Exception("Keranjang belanja kosong.");
    }

    // 4. Loop setiap item di keranjang untuk diproses
    while ($row = mysqli_fetch_assoc($keranjang)) {
        $kd_produk   = $row['kode_produk'];
        $nama_produk = $row['nama_produk'];
        $qty         = (int)$row['qty'];
        $harga       = $row['harga'];
        $status      = "Pesanan Baru";

        // 4a. Cek stok lagi sebelum mengurangi (pengaman terakhir)
        $stok_cek_res = mysqli_query($conn, "SELECT jumlah_stok FROM produk WHERE kode_produk = '$kd_produk'");
        $stok_data = mysqli_fetch_assoc($stok_cek_res);
        if ($stok_data['jumlah_stok'] < $qty) {
            // Jika stok tidak cukup, batalkan seluruh transaksi
            throw new Exception("Stok untuk produk '$nama_produk' tidak mencukupi.");
        }

        // 4b. Insert ke tabel pesanan
        $query_order = "INSERT INTO pesanan (invoice, email_pembeli, kode_customer, kode_produk, nama_produk, qty, harga, status, tanggal, provinsi, kota, alamat, kode_pos, terima, tolak, cek) 
                        VALUES ('$format_invoice', '$email_pembeli', '$kd_cs', '$kd_produk', '$nama_produk', '$qty', '$harga', '$status', '$tanggal', '$prov', '$kota', '$alamat', '$kopos', '0', '0', '0')";

        if (!mysqli_query($conn, $query_order)) {
            throw new Exception("Gagal menyimpan data pesanan: " . mysqli_error($conn));
        }

        // 4c. Kurangi stok di tabel 'produk'
        $query_update_stok = "UPDATE produk SET jumlah_stok = jumlah_stok - $qty WHERE kode_produk = '$kd_produk'";

        if (!mysqli_query($conn, $query_update_stok)) {
            throw new Exception("Gagal memperbarui stok produk: " . mysqli_error($conn));
        }
    }

    // 5. Hapus semua item dari keranjang customer
    if (!mysqli_query($conn, "DELETE FROM cart WHERE kode_customer = '$kd_cs'")) {
        throw new Exception("Gagal membersihkan keranjang: " . mysqli_error($conn));
    }

    // ==================================================================
    // == Jika semua berhasil, simpan perubahan secara permanen ==
    // ==================================================================
    mysqli_commit($conn);

    // 6. Redirect ke halaman nota
    header("Location: nota.php?invoice=" . $format_invoice);
    exit();
} catch (Exception $e) {
    // ==================================================================
    // == Jika ada satu saja kesalahan, batalkan semua perubahan ==
    // ==================================================================
    mysqli_rollback($conn);

    // Tampilkan pesan error dan redirect kembali ke keranjang
    echo "<script>
        alert('Terjadi kesalahan: " . addslashes($e->getMessage()) . "');
        window.location = '../keranjang.php';
    </script>";
    exit;
}
?>


<!-- // --- PERBATASAN --- -->


<!-- // Generate nomor invoice baru
$kode = mysqli_query($conn, "SELECT invoice FROM pesanan ORDER BY invoice DESC LIMIT 1");
$data = mysqli_fetch_assoc($kode);
$num = $data ? substr($data['invoice'], 3, 4) : '0000';
$add = (int) $num + 1;
$format = 'INV' . str_pad($add, 4, '0', STR_PAD_LEFT);

// Ambil data keranjang sesuai customer
$keranjang = mysqli_query($conn, "SELECT * FROM cart WHERE kode_customer = '$kd_cs'");

$order_success = false;

while ($row = mysqli_fetch_assoc($keranjang)) {
    $kd_produk   = $row['kode_produk'];
    $nama_produk = $row['nama_produk'];
    $qty         = (int)$row['qty'];   // <-- ambil qty dari keranjang
    $harga       = $row['harga'];
    $status      = "Pesanan Baru";

    // INSERT dengan sebutkan nama kolom agar qty masuk ke kolom yang benar
    $order = mysqli_query($conn, "INSERT INTO pesanan 
        (invoice, email_pembeli, kode_customer, kode_produk, nama_produk, qty, harga, status, tanggal, provinsi, kota, alamat, kode_pos, terima, tolak, cek)
        VALUES (
            '$format', '$email_pembeli', '$kd_cs', '$kd_produk', '$nama_produk',
            '$qty', '$harga', '$status', '$tanggal',
            '$prov', '$kota', '$alamat', '$kopos', '0', '0', '0'
        )");

    if (!$order) {
        echo "Error insert pesanan: " . mysqli_error($conn);
        exit;
    }

    // Kurangi stok produk sesuai qty
    // $update_stok = mysqli_query($conn, "UPDATE stok_barang 
    //     SET jumlah_stok = jumlah_stok - $qty 
    //     WHERE kode_barang = '$kd_produk'");

    $update_stok = mysqli_query($conn, "UPDATE produk 
    SET jumlah_stok = jumlah_stok - $qty 
    WHERE kode_produk = '$kd_produk'");

    if (!$update_stok) {
        echo "Error update stok: " . mysqli_error($conn);
        exit;
    }

    $order_success = true;
}

// Hapus keranjang hanya jika pesanan berhasil dimasukkan
if ($order_success) {
    $del_keranjang = mysqli_query($conn, "DELETE FROM cart WHERE kode_customer = '$kd_cs'");
    if (!$del_keranjang) {
        echo "Error hapus keranjang: " . mysqli_error($conn);
        exit;
    }

    // Redirect ke halaman nota
    header("Location: nota.php?kode_cs=$kd_cs&prov=$prov&kota=$kota&almt=$alamat&kopos=$kopos&payment_method=$payment_method&email=$email_pembeli");
    exit();
} else {
    echo "Tidak ada item dalam keranjang atau gagal memproses pesanan.";
} -->