<?php
include '../../koneksi/koneksi.php';
session_start();

// Keamanan: Pastikan hanya admin yang bisa mengakses
if (!isset($_SESSION['admin'])) {
    header('location:../index.php');
    exit();
}

// Pastikan request datang dari method POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. AMBIL & AMANKAN SEMUA DATA DARI FORM
    // Keamanan: Gunakan mysqli_real_escape_string untuk mencegah SQL Injection
    $kode_produk = mysqli_real_escape_string($conn, $_POST['kode_produk']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $harga = mysqli_real_escape_string($conn, $_POST['harga']);
    $desk = mysqli_real_escape_string($conn, $_POST['desk']);
    
    // [PERBAIKAN] Ambil data jumlah stok dan kategori yang sebelumnya tidak ada
    $jumlah_stok = mysqli_real_escape_string($conn, $_POST['jumlah_stok']);
    $id_kategori = mysqli_real_escape_string($conn, $_POST['id_kategori']);
    
    // Data untuk gambar dan komponen
    $gambar_lama = mysqli_real_escape_string($conn, $_POST['gambar_lama']);
    $nama_gambar_baru = $gambar_lama; // Defaultnya adalah nama gambar lama

    // 2. PROSES UPLOAD GAMBAR BARU (JIKA ADA)
    // Cek apakah ada file baru yang diupload
    if (isset($_FILES['files']) && $_FILES['files']['error'] == 0) {
        // Ambil detail file
        $nama_file = $_FILES['files']['name'];
        $ukuran = $_FILES['files']['size'];
        $file_tmp = $_FILES['files']['tmp_name'];
        $x = explode('.', $nama_file);
        $ekstensi = strtolower(end($x));
        $ekstensi_diperbolehkan = array('png', 'jpg', 'jpeg');

        // Validasi ekstensi dan ukuran
        if (in_array($ekstensi, $ekstensi_diperbolehkan)) {
            if ($ukuran < 2000000) { // Batas 2MB
                // Hapus gambar lama dari server
                if ($gambar_lama && file_exists("../../image/produk/$gambar_lama")) {
                    unlink("../../image/produk/$gambar_lama");
                }
                
                // Buat nama unik untuk gambar baru dan pindahkan ke folder
                $nama_gambar_baru = time() . '_' . $nama_file;
                move_uploaded_file($file_tmp, '../../image/produk/' . $nama_gambar_baru);
            } else {
                echo "<script>alert('UKURAN FILE TERLALU BESAR!'); window.location = '../m_produk.php';</script>";
                exit;
            }
        } else {
            echo "<script>alert('EKSTENSI FILE TIDAK DIIZINKAN!'); window.location = '../m_produk.php';</script>";
            exit;
        }
    }

    // 3. UPDATE DATA UTAMA PRODUK KE DATABASE
    // [PERBAIKAN] Query diupdate untuk menyertakan semua kolom
    $query_update_produk = "UPDATE produk SET 
                                nama = '$nama', 
                                id_kategori = '$id_kategori', 
                                harga = '$harga', 
                                jumlah_stok = '$jumlah_stok', 
                                deskripsi = '$desk', 
                                image = '$nama_gambar_baru' 
                            WHERE kode_produk = '$kode_produk'";
    
    $result = mysqli_query($conn, $query_update_produk);

    
    // 4. [PERBAIKAN LOGIKA] UPDATE KOMPONEN PRODUK (BOM)
    // Logika ini jauh lebih aman dan stabil: Hapus semua komponen lama, lalu masukkan yang baru.
    
    // Pertama, hapus semua komponen lama yang terhubung dengan produk ini
    mysqli_query($conn, "DELETE FROM komponen_produk WHERE kode_produk = '$kode_produk'");
    
    // Kedua, jika ada data komponen baru yang dikirim, masukkan satu per satu
    if (isset($_POST['kd_material']) && is_array($_POST['kd_material'])) {
        $kd_material = $_POST['kd_material'];
        $keb = $_POST['keb']; // Kebutuhan

        foreach ($kd_material as $index => $kode_item) {
            if (!empty($kode_item)) { // Pastikan kode material tidak kosong
                $kebutuhan = mysqli_real_escape_string($conn, $keb[$index]);
                $kode_item_safe = mysqli_real_escape_string($conn, $kode_item);
                
                mysqli_query($conn, "INSERT INTO komponen_produk (kode_produk, kode_item, kebutuhan) VALUES ('$kode_produk', '$kode_item_safe', '$kebutuhan')");
            }
        }
    }


    // 5. BERIKAN FEEDBACK KE PENGGUNA
    if ($result) {
        echo "<script>alert('Produk berhasil diupdate!'); window.location = '../m_produk.php';</script>";
    } else {
        // Tampilkan pesan error jika query gagal untuk mempermudah debugging
        echo "<script>alert('Gagal mengupdate produk: " . mysqli_error($conn) . "'); window.location = '../m_produk.php';</script>";
    }

} else {
    // Jika halaman ini diakses langsung tanpa kirim data, redirect
    header("Location: ../m_produk.php");
    exit;
}
?>

