<?php 
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
        --card-shadow: 0 4px 8px rgba(0,0,0,0.05);
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
    }
</style>

<div class="container" style="margin-top: 20px;">
    
    <div class="card-custom">
        <h2 class="page-title"><b>Manajemen Stok Barang</b></h2>
        
        <div class="table-responsive">
            <table class="table table-hover table-custom">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Kode Produk</th>
                        <th scope="col">Nama Produk</th>
                        <th scope="col" class="text-center">Stok Saat Ini</th>
                        <th scope="col" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $result = mysqli_query($conn, "SELECT * FROM produk ORDER BY nama ASC");
                    $no = 1;

                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                            <tr>
                                <td><?= $no; ?></td>
                                <td><b><?= htmlspecialchars($row['kode_produk']); ?></b></td>
                                <td><?= htmlspecialchars($row['nama']); ?></td>
                                <td class="text-center">
                                    <span class="badge" style="background-color: #5cb85c; font-size: 1.1em;">
                                        <?= htmlspecialchars($row['jumlah_stok']); ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="edit_stok.php?kode=<?= $row['kode_produk']; ?>" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Update Stok</a>
                                </td>
                            </tr>
                    <?php
                            $no++; 
                        }
                    } else {
                        echo '<tr><td colspan="5" class="text-center" style="padding: 20px;">Belum ada data produk.</td></tr>';
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
