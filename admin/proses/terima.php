<?php 
include '../../koneksi/koneksi.php';
$inv = $_GET['inv'];

$result = mysqli_query($conn, "SELECT * FROM pesanan WHERE invoice = '$inv'");

while ($row = mysqli_fetch_assoc($result)) {
    $kodep = $row['kode_produk'];
    $qtyorder = $row['qty'];

    // Cek apakah produk punya komponen di BOM
    $t_bom = mysqli_query($conn, "SELECT * FROM komponen_produk WHERE kode_produk = '$kodep'");
    
    if (mysqli_num_rows($t_bom) > 0) {
        // Produk memiliki komponen
        while ($row1 = mysqli_fetch_assoc($t_bom)) {
            $kodebk = $row1['kode_item'];
            $kebutuhan = $row1['kebutuhan'];

            // Ambil stok dari inventory
            $inventory = mysqli_query($conn, "SELECT * FROM inventory WHERE kode_item = '$kodebk'");
            $r_inv = mysqli_fetch_assoc($inventory);

            if ($r_inv) {
                $inven = $r_inv['qty'];
                $bom = ($kebutuhan * $qtyorder);
                $hasil = $inven - $bom;

                // Update stok inventory
                mysqli_query($conn, "UPDATE inventory SET qty = '$hasil' WHERE kode_item = '$kodebk'");
            }
        }

    } else {
        // Produk tidak memiliki komponen, ambil dari tabel produk
        $getProduk = mysqli_query($conn, "SELECT * FROM produk WHERE kode_produk = '$kodep'");
        $produk = mysqli_fetch_assoc($getProduk);

        if ($produk) {
            $namaProduk = $produk['nama'];

            // Cari inventory berdasarkan nama
            $inventory = mysqli_query($conn, "SELECT * FROM inventory WHERE nama = '$namaProduk'");
            $r_inv = mysqli_fetch_assoc($inventory);

            if ($r_inv) {
                $inven = $r_inv['qty'];
                $hasil = $inven - $qtyorder;

                // Update stok inventory berdasarkan nama
                mysqli_query($conn, "UPDATE inventory SET qty = '$hasil' WHERE nama = '$namaProduk'");
            }
        }
    }

    // Update status pesanan
    mysqli_query($conn, "UPDATE pesanan SET terima = '1', status = '0' WHERE invoice = '$inv'");
}

// Beri notifikasi dan redirect
echo "
<script>
alert('PESANAN BERHASIL DITERIMA, ITEM TELAH DIKURANGKAN');
window.location = '../pesanan.php';
</script>
";
?>
