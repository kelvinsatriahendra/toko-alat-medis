<?php 
include 'header.php';
// Gunakan parameter GET yang lebih aman untuk mengambil kode pelanggan
if (isset($_GET['kode_cs'])) {
    $kd = mysqli_real_escape_string($conn, $_GET['kode_cs']);
    $cs = mysqli_query($conn, "SELECT * FROM customer WHERE kode_customer = '$kd'");
    $rows = mysqli_fetch_assoc($cs);
} else {
    // Redirect atau tampilkan error jika kode pelanggan tidak ada
    echo "<script>alert('Kode pelanggan tidak ditemukan. Silakan login kembali.'); window.location.href='keranjang.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome untuk ikon kartu -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" xintegrity="sha512-iecdLpQ3d/QzT7cT7r9T9yFwW5bNq9QpT8Lp+V5o3t4C6Gf7A7S8L8G7e3QzP6D5C..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* -------------------------------------
        CSS Kustom - Desain UI Checkout Profesional
        ------------------------------------- */

        :root {
            --primary-color: #1e3a5f;
            --secondary-color: #27ae60;
            --accent-color: #f39c12;
            --light-bg: #f5f7fa;
            --text-dark: #333;
            --text-light: #fefefe;
            --shadow-light: rgba(0, 0, 0, 0.08);
        }
        
        body {
            font-family: 'Montserrat', sans-serif;
            color: var(--text-dark);
            background-color: var(--light-bg);
        }

        .main-container {
            padding-top: 50px;
            padding-bottom: 100px;
        }

        .section-title {
            font-size: 2.5em;
            font-weight: 700;
            color: var(--primary-color);
            border-bottom: 3px solid var(--accent-color);
            padding-bottom: 15px;
            margin-bottom: 40px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        /* --- Ringkasan Pesanan --- */
        .summary-card {
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 20px var(--shadow-light);
            margin-bottom: 30px;
        }

        .summary-card h4 {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 20px;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }

        .summary-card table {
            width: 100%;
            border-collapse: collapse;
        }
        .summary-card th, .summary-card td {
            padding: 12px 0;
            border-bottom: 1px solid #eee;
            text-align: left;
        }
        .summary-card th {
            color: #95a5a6;
            font-weight: 500;
        }
        .summary-card tr:last-child td {
            border-bottom: none;
        }

        .grand-total-row td {
            font-weight: 700;
            font-size: 1.2em;
            color: var(--secondary-color);
        }

        /* --- Formulir Checkout --- */
        .checkout-form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 20px var(--shadow-light);
        }

        .form-group label {
            font-weight: 600;
            color: var(--primary-color);
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #ddd;
            padding: 10px;
            box-shadow: none;
            transition: border-color 0.3s;
        }
        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(39, 174, 96, 0.25);
        }

        /* Custom Dropdown Styling */
        .custom-select-container {
            position: relative;
        }
        .custom-select-display {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            transition: border-color 0.3s;
        }
        .custom-select-display:hover {
            border-color: var(--secondary-color);
        }
        .custom-select-display .arrow {
            border-left: 6px solid transparent;
            border-right: 6px solid transparent;
            border-top: 6px solid #aaa;
        }
        .custom-select-options {
            display: none;
            position: absolute;
            width: 100%;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            z-index: 10;
            margin-top: 5px;
            padding: 0;
            list-style: none;
        }
        .custom-select-options .option {
            padding: 12px;
            cursor: pointer;
            transition: background-color 0.2s;
            border-bottom: 1px solid #eee;
        }
        .custom-select-options .option:hover {
            background-color: var(--light-bg);
        }
        .custom-select-options .option:last-child {
            border-bottom: none;
        }
        
        /* --- Tampilan Detail Pembayaran --- */
        .payment-details-container {
            border-top: 2px solid #f0f0f0;
            padding-top: 20px;
            margin-top: 20px;
        }
        
        /* Card Input Group - Kontainer baru untuk input dan ikon */
        .card-input-group {
            position: relative;
            margin-bottom: 15px;
        }

        /* Input di dalam kontainer */
        .card-input-group input {
            width: 100%;
            padding: 10px 15px;
            padding-right: 45px; /* Tambahkan padding agar ikon tidak menutupi teks */
            border-radius: 8px;
            border: 1px solid #ddd;
            box-shadow: none;
            transition: border-color 0.3s;
        }
        .card-input-group input:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(39, 174, 96, 0.25);
        }
        
        /* Ikon di dalam kontainer */
        .card-input-group .card-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%); /* Menyesuaikan posisi vertikal */
            color: #ccc;
            font-size: 1.2em;
            transition: color 0.3s;
        }
        
        /* Highlight ikon saat input fokus */
        .card-input-group input:focus + .card-icon {
            color: var(--primary-color);
        }
        
        .card-fields-container {
            display: flex;
            gap: 15px;
        }
        
        .card-fields-container .form-group {
            flex: 1;
        }

        .payment-fields {
            margin-bottom: 20px;
        }

        /* --- Tombol Aksi --- */
        .btn-submit {
            background-color: var(--secondary-color);
            color: var(--text-light);
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            margin-top: 20px;
            transition: background-color 0.3s;
        }
        .btn-submit:hover {
            background-color: #2ecc71;
        }

        .btn-cancel {
            background-color: var(--accent-color);
            color: var(--text-light);
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            margin-top: 20px;
            transition: background-color 0.3s;
        }
        .btn-cancel:hover {
            background-color: #e67e22;
        }

        @media (max-width: 767px) {
            .checkout-form-container {
                margin-top: 30px;
            }
            .card-fields-container {
                flex-direction: column;
                gap: 0;
            }
        }
    </style>
</head>
<body>
<div class="container main-container">
    <h2 class="section-title text-center">Checkout</h2>
    <div class="row">
        <div class="col-md-6">
            <div class="summary-card">
                <h4>Ringkasan Pesanan</h4>
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th>Sub Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $result = mysqli_query($conn, "SELECT * FROM cart WHERE kode_customer = '$kd'");
                        $no = 1;
                        $hasil = 0;
                        while($row = mysqli_fetch_assoc($result)){
                            $subtotal = $row['harga'] * $row['qty'];
                            $hasil += $subtotal;
                        ?>
                            <tr>
                                <td><?= $no; ?></td>
                                <td><?= $row['nama_produk']; ?></td>
                                <td>Rp.<?= number_format($row['harga']); ?></td>
                                <td><?= $row['qty']; ?></td>
                                <td>Rp.<?= number_format($subtotal); ?></td>
                            </tr>
                        <?php 
                            $no++;
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr class="grand-total-row">
                            <td colspan="4">Total Pembayaran</td>
                            <td>Rp.<?= number_format($hasil); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="col-md-6">
            <div class="checkout-form-container">
                <h4>Informasi Pengiriman & Pembayaran</h4>
                <form action="proses/order.php" method="POST">
                    <input type="hidden" name="kode_cs" value="<?= $kd; ?>">
                    <input type="hidden" name="total_harga" value="<?= $hasil; ?>">
                    
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="provinsi">Provinsi</label>
                            <input type="text" class="form-control" id="provinsi" placeholder="Provinsi" name="prov" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="kota">Kota</label>
                            <input type="text" class="form-control" id="kota" placeholder="Kota" name="kota" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="alamat">Alamat Lengkap</label>
                            <input type="text" class="form-control" id="alamat" placeholder="Alamat" name="almt" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="kode_pos">Kode Pos</label>
                            <input type="text" class="form-control" id="kode_pos" placeholder="Kode Pos" name="kopos" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" placeholder="Email" name="email" required>
                    </div>

                    <div class="payment-details-container">
                        <div class="form-group">
                            <label for="payment_method">Metode Pembayaran</label>
                            <div class="custom-select-container">
                                <div class="custom-select-display" id="payment-select-display">
                                    <span id="selected-option-text">Pilih Metode Pembayaran</span>
                                    <input type="hidden" id="payment_method_input" name="payment_method" required>
                                    <div class="arrow"></div>
                                </div>
                                <ul class="custom-select-options">
                                    <li class="option" data-value="credit_card">Kartu Kredit</li>
                                    <li class="option" data-value="debit_card">Kartu Debit</li>
                                    <li class="option" data-value="cash_on_delivery">Bayar di Tempat (COD)</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div id="payment-fields-container">
                            <div class="payment-fields" id="credit_card_details" style="display: none;">
                                <div class="form-group">
                                    <label for="card_number">Nomor Kartu Kredit</label>
                                    <div class="card-input-group">
                                        <input type="text" class="form-control" id="card_number" placeholder="xxxx xxxx xxxx xxxx" name="card_number" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9\s]/g, ''); formatCardNumber(this);" maxlength="19">
                                        <i class="fa-solid fa-credit-card card-icon"></i>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="card_expiry">Masa Berlaku</label>
                                        <input type="text" class="form-control" id="card_expiry" placeholder="MM/YY" name="card_expiry" oninput="formatExpiry(this)" maxlength="5">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="card_cvc">CVC</label>
                                        <input type="text" class="form-control" id="card_cvc" placeholder="CVC" name="card_cvc" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '');" maxlength="4">
                                    </div>
                                </div>
                            </div>

                            <div class="payment-fields" id="debit_card_details" style="display: none;">
                                <div class="form-group">
                                    <label for="debit_card_number">Nomor Kartu Debit</label>
                                    <div class="card-input-group">
                                        <input type="text" class="form-control" id="debit_card_number" placeholder="xxxx xxxx xxxx xxxx" name="debit_card_number" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9\s]/g, ''); formatCardNumber(this);" maxlength="19">
                                        <i class="fa-solid fa-credit-card card-icon"></i>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="debit_card_expiry">Masa Berlaku</label>
                                        <input type="text" class="form-control" id="debit_card_expiry" placeholder="MM/YY" name="debit_card_expiry" oninput="formatExpiry(this)" maxlength="5">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="debit_card_pin">PIN</label>
                                        <input type="password" class="form-control" id="debit_card_pin" placeholder="PIN" name="debit_card_pin" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '');" maxlength="6">
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6">
                            <button type="submit" class="btn btn-submit btn-block">Order Sekarang</button>
                        </div>
                        <div class="col-xs-6">
                            <a href="keranjang.php" class="btn btn-cancel btn-block">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const customSelect = document.querySelector('.custom-select-container');
    const selectDisplay = customSelect.querySelector('.custom-select-display');
    const optionsList = customSelect.querySelector('.custom-select-options');
    const selectedOptionText = customSelect.querySelector('#selected-option-text');
    const hiddenInput = customSelect.querySelector('#payment_method_input');
    const allPaymentFields = document.querySelectorAll('.payment-fields');

    // Toggle dropdown
    selectDisplay.addEventListener('click', () => {
        optionsList.style.display = optionsList.style.display === 'block' ? 'none' : 'block';
    });

    // Handle option selection
    optionsList.addEventListener('click', (e) => {
        if (e.target.tagName === 'LI') {
            const selectedValue = e.target.getAttribute('data-value');
            const selectedText = e.target.textContent;

            selectedOptionText.textContent = selectedText;
            hiddenInput.value = selectedValue;
            optionsList.style.display = 'none';

            // Hide all payment fields
            allPaymentFields.forEach(field => {
                field.style.display = 'none';
                field.querySelectorAll('input').forEach(input => input.required = false);
            });

            // Show selected payment fields and set required
            const selectedFields = document.getElementById(selectedValue + '_details');
            if (selectedFields) {
                selectedFields.style.display = 'block';
                selectedFields.querySelectorAll('input').forEach(input => input.required = true);
            }
        }
    });

    // Close dropdown if clicked outside
    document.addEventListener('click', (e) => {
        if (!customSelect.contains(e.target)) {
            optionsList.style.display = 'none';
        }
    });

    // --- Fungsionalitas Tambahan untuk Input Kartu ---

    // Fungsi untuk memformat nomor kartu
    window.formatCardNumber = function(input) {
        let value = input.value.replace(/\s/g, ''); // Hapus spasi yang ada
        let formattedValue = '';
        for (let i = 0; i < value.length; i++) {
            if (i > 0 && i % 4 === 0) {
                formattedValue += ' ';
            }
            formattedValue += value[i];
        }
        input.value = formattedValue;
    };

    // Fungsi untuk memformat masa berlaku
    window.formatExpiry = function(input) {
        let value = input.value.replace(/\D/g, ''); // Hapus non-digit
        let formattedValue = '';
        if (value.length > 2) {
            formattedValue = value.substring(0, 2) + '/' + value.substring(2, 4);
        } else {
            formattedValue = value;
        }
        input.value = formattedValue;
    };
});
</script>

<?php 
include 'footer.php';
?>
