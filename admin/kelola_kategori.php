<?php 
// Menggunakan include_once agar file tidak dimuat berulang kali
include_once '../koneksi/koneksi.php'; 
include_once 'header.php'; // Memanggil header yang berisi menu navigasi

// Pengecekan sesi admin
if(!isset($_SESSION['admin'])){
    header('location:index.php');
    exit();
}

// Menangani notifikasi dari file proses
$alert_message = '';
if(isset($_GET['status'])){
    if($_GET['status'] == 'sukses'){
        $alert_message = '<div class="alert alert-success"><strong>Sukses!</strong> Data kategori berhasil diproses.</div>';
    } else if ($_GET['status'] == 'gagal'){
        $alert_message = '<div class="alert alert-danger"><strong>Gagal!</strong> Terjadi kesalahan saat memproses data.</div>';
    }
}

// Menggunakan PREPARED STATEMENT untuk keamanan saat mengambil data
$query_kategori = mysqli_prepare($conn, "SELECT id_kategori, nama_kategori FROM kategori ORDER BY nama_kategori ASC");
mysqli_stmt_execute($query_kategori);
$result = mysqli_stmt_get_result($query_kategori);
?>

<style>
    /* --- Konfigurasi Dasar & Palet Warna --- */
    :root {
        --primary-color: #1e3a5f;      /* Deep Navy Blue */
        --secondary-color: #27ae60;    /* Emerald Green */
        --accent-color: #3498db;       /* Bright Blue */
        --danger-color: #e74c3c;       /* Red */
        --warning-color: #f39c12;      /* Orange Gold */
        --light-bg: #f5f7fa;           /* Off-White */
        --text-dark: #333;
        --text-light: #fefefe;
        --shadow-soft: rgba(0, 0, 0, 0.08);
        --shadow-medium: rgba(0, 0, 0, 0.15);
    }

    /* --- Animasi saat Halaman Dimuat --- */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-on-load {
        animation: fadeInUp 0.8s ease-in-out;
    }

    /* --- Tampilan Profesional untuk Panel & Tabel --- */
    .panel-default {
        border: none;
        border-radius: 12px;
        box-shadow: 0 8px 25px var(--shadow-soft);
        transition: all 0.3s ease-in-out;
    }

    .panel-default:hover {
        box-shadow: 0 12px 35px var(--shadow-medium);
        transform: translateY(-3px);
    }

    .panel-heading {
        background-color: var(--primary-color) !important;
        color: var(--text-light) !important;
        font-weight: 600;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        padding: 15px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table > thead > tr > th {
        background-color: var(--light-bg);
        font-weight: 700;
        color: var(--primary-color);
    }

    .table-striped > tbody > tr:nth-of-type(odd) {
        background-color: #fff;
    }

    .table-striped > tbody > tr {
        transition: background-color 0.2s ease-in-out;
    }

    .table-striped > tbody > tr:hover {
        background-color: #e9f5ff; /* Light blue hover */
    }

    /* --- Transisi Profesional untuk Form & Tombol --- */
    .form-control {
        border-radius: 8px;
        border: 1px solid #ddd;
        padding: 10px 15px;
        height: 40px;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .form-control:focus {
        border-color: var(--accent-color);
        box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
    }

    .btn {
        border-radius: 50px;
        font-weight: 600;
        padding: 8px 20px;
        border: none;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background-color: var(--accent-color);
        color: white;
    }
    
    .btn-primary:hover {
        color: white;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .btn-danger { background-color: var(--danger-color); }
    .btn-warning { background-color: var(--warning-color); color: white; }
    .btn-warning:hover { color: white; }

</style>


<div class="container animate-on-load" style="padding-bottom: 200px;">
    <h2 style=" width: 100%; border-bottom: 4px solid #1e3a5f; padding-bottom:5px;"><b>Kelola Kategori Produk</b></h2>
    
    <?= $alert_message; ?>

    <div class="panel panel-default">
        <div class="panel-heading">Tambah Kategori Baru</div>
        <div class="panel-body">
            <form action="proses/proses_kategori.php" method="POST" class="form-inline">
                <input type="hidden" name="action" value="add">
                <div class="form-group">
                    <input type="text" name="nama_kategori" class="form-control" placeholder="Nama Kategori Baru" style="width: 250px;" required>
                </div>
                <button type="submit" class="btn btn-primary">Tambah</button>
            </form>
        </div>
    </div>

    <br>

    <div class="panel panel-default">
        <div class="panel-heading">Daftar Kategori</div>
        <div class="panel-body">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th width="5%">ID</th>
                        <th>Nama Kategori</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if(mysqli_num_rows($result) > 0){
                        while($kategori = mysqli_fetch_assoc($result)): 
                    ?>
                    <tr>
                        <td><?= $kategori['id_kategori']; ?></td>
                        <td><?= htmlspecialchars($kategori['nama_kategori']); ?></td>
                        <td>
                            <a href="edit_kategori.php?id=<?= $kategori['id_kategori']; ?>" class="btn btn-warning btn-xs">Edit</a>
                            <a href="proses/proses_kategori.php?action=delete&id=<?= $kategori['id_kategori']; ?>" class="btn btn-danger btn-xs" onclick="return confirm('Yakin ingin menghapus kategori ini?');">Hapus</a>
                        </td>
                    </tr>
                    <?php 
                        endwhile;
                    } else {
                    ?>
                    <tr>
                        <td colspan="3" class="text-center">Belum ada kategori yang ditambahkan.</td>
                    </tr>
                    <?php
                    }
                    mysqli_stmt_close($query_kategori);
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php 
include_once 'footer.php'; 
?>