<?php
// Hubungkan ke database
include '../koneksi/koneksi.php'; // Sesuaikan path jika perlu

// Query untuk mengambil semua data dari guestbook, diurutkan dari yang terbaru
$query = "SELECT * FROM guestbook ORDER BY tanggal_submit DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>

<head>
    <title>View Guestbook Entries</title>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
</head>

<body>
    <div class="container" style="padding-top: 30px; padding-bottom: 30px;">
        <h2 style="width: 100%; border-bottom: 4px solid gray"><b>Daftar Entri Buku Tamu</b></h2>
        <br>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 20%;">Nama</th>
                    <th style="width: 20%;">Email</th>
                    <th>Pesan</th>
                    <th style="width: 15%;">Tanggal</th>
                    <th style="width: 10%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $nomor = 1;
                while ($data = mysqli_fetch_assoc($result)) {
                ?>
                    <tr>
                        <td><?= $nomor++; ?></td>
                        <td><?= htmlspecialchars($data['nama']); ?></td>
                        <td><?= htmlspecialchars($data['email']); ?></td>
                        <td><?= htmlspecialchars($data['pesan']); ?></td>
                        <td><?= htmlspecialchars($data['tanggal_submit']); ?></td>
                        <td>
                            <a href="delete_guestbook.php?id=<?= $data['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Anda yakin ingin menghapus entri ini?');">
                                <i class="glyphicon glyphicon-trash"></i> Hapus
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>

</html>