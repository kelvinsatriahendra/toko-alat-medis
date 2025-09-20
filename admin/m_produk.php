<?php
// Pastikan file 'header.php' ini memanggil 'koneksi.php' dengan benar
include 'header.php';
?>

<!-- [PENAMBAHAN] Google Fonts & Font Awesome untuk ikon -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<!-- [PENAMBAHAN] Style khusus untuk halaman ini -->
<style>
    :root {
        --primary-color: #0d6efd;
        --light-bg: #f8f9fa;
        --card-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        --success-light: #d1e7dd;
        --warning-light: #fff3cd;
        --danger-light: #f8d7da;
        --success-dark: #0f5132;
        --warning-dark: #664d03;
        --danger-dark: #842029;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: var(--light-bg);
    }

    .page-title {
        color: #333;
        border-bottom: 3px solid var(--primary-color);
        padding-bottom: 10px;
        margin-bottom: 20px;
        font-weight: 600;
    }

    .card-custom {
        background-color: #fff;
        border: none;
        border-radius: 10px;
        box-shadow: var(--card-shadow);
        padding: 25px;
    }

    .table-custom thead {
        background-color: #343a40;
        color: #fff;
    }

    .table-custom tbody tr:hover {
        background-color: #f1f1f1;
        cursor: pointer;
    }

    .table-custom img {
        border-radius: 5px;
        object-fit: cover;
    }

    .btn-action {
        margin: 0 2px;
    }

    .badge-stok {
        padding: 6px 10px;
        font-size: 0.85em;
        border-radius: 50px;
        font-weight: 500;
        color: #fff;
    }

    .badge-aman {
        background-color: #198754;
    }

    .badge-menipis {
        background-color: #ffc107;
        color: #000 !important;
    }

    .badge-habis {
        background-color: #dc3545;
    }
</style>


<div class="container" style="margin-top: 20px;">

    <div class="card-custom">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="page-title"><b>Master Produk</b></h2>
            <a href="tm_produk.php" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Produk</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-custom">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Kode</th>
                        <th scope="col">Nama Produk</th>
                        <th scope="col">Image</th>
                        <th scope="col">Harga</th>
                        <th scope="col" class="text-center">Stok</th>
                        <th scope="col" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = mysqli_query($conn, "SELECT * FROM produk ORDER BY kode_produk ASC, nama ASC");
                    $no = 1;

                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {

                            // Logika untuk menentukan warna badge stok
                            $stok = $row['jumlah_stok'];
                            $badge_class = '';
                            $badge_text = $stok;

                            if ($stok > 10) {
                                $badge_class = 'badge-aman';
                            } elseif ($stok > 0 && $stok <= 10) {
                                $badge_class = 'badge-menipis';
                            } else {
                                $badge_class = 'badge-habis';
                                $badge_text = 'Habis';
                            }
                    ?>
                            <tr>
                                <td><?= $no; ?></td>
                                <td><b><?= htmlspecialchars($row['kode_produk']); ?></b></td>
                                <td><?= htmlspecialchars($row['nama']); ?></td>
                                <td><img src="../image/produk/<?= htmlspecialchars($row['image']); ?>" width="80" height="80"></td>
                                <td>Rp.<?= number_format($row['harga']); ?></td>
                                <td class="text-center">
                                    <span class="badge-stok <?= $badge_class; ?>"><?= $badge_text; ?></span>
                                </td>
                                <td class="text-center">
                                    <a href="edit_produk.php?kode=<?= $row['kode_produk']; ?>" class="btn btn-warning btn-sm btn-action" title="Edit"><i class="fas fa-edit"></i></a>
                                    <a href="proses/del_produk.php?kode=<?= $row['kode_produk']; ?>" class="btn btn-danger btn-sm btn-action" title="Hapus" onclick="return confirm('Yakin Ingin Menghapus Data Ini?')"><i class="fas fa-trash-alt"></i></a>
                                </td>
                            </tr>
                    <?php
                            $no++;
                        }
                    } else {
                        echo '<tr><td colspan="7" class="text-center" style="padding: 20px;">Belum ada data produk untuk ditampilkan.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<br><br>

<?php
include 'footer.php';
?>