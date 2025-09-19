<?php 
include 'header.php';

// [PERBAIKAN] Logika DELETE hanya dijalankan jika semua parameter ada dan valid
if (isset($_GET['page']) && $_GET['page'] == 'del' && isset($_GET['kode'])) {
    
    $kode_customer = $_GET['kode'];

    // [FIX KEAMANAN] Gunakan Prepared Statement untuk mencegah SQL Injection
    $stmt = mysqli_prepare($conn, "DELETE FROM customer WHERE kode_customer = ?");
    mysqli_stmt_bind_param($stmt, "s", $kode_customer);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "
        <script>
            alert('DATA BERHASIL DIHAPUS');
            window.location = 'm_customer.php';
        </script>
        ";
    } else {
        echo "
        <script>
            alert('DATA GAGAL DIHAPUS');
            window.location = 'm_customer.php';
        </script>
        ";
    }
    // Hentikan eksekusi skrip setelah proses delete agar halaman tidak lanjut me-render tabel
    exit();
}
?>

<div class="container">
    <h2 style="width: 100%; border-bottom: 4px solid gray"><b>Data Customer</b></h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Kode Customer</th>
                <th scope="col">Nama</th>
                <th scope="col">Email</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $result = mysqli_query($conn, "SELECT * FROM customer ORDER BY nama ASC");
            $no = 1;
            while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <tr>
                    <th scope="row"><?= $no; ?></th>
                    <td><?= htmlspecialchars($row['kode_customer']); ?></td>
                    <td><?= htmlspecialchars($row['nama']); ?></td>
                    <td><?= htmlspecialchars($row['email']); ?></td>
                    <td>
                        <a href="m_customer.php?kode=<?= urlencode($row['kode_customer']); ?>&page=del" class="btn btn-danger" onclick="return confirm('Yakin Ingin Menghapus Data Ini?')">
                            <i class="glyphicon glyphicon-trash"></i> Hapus
                        </a>
                    </td>
                </tr>
            <?php 
                $no++;
            }
            ?>
        </tbody>
    </table>
</div>

<br><br><br><br><br><br><br><br><br><br><br><br>

<?php 
include 'footer.php';
?>