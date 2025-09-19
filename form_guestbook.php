<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Tamu - Prima Medical</title>
    
    <!-- Bootstrap CSS (masih kita gunakan untuk grid system) -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    
    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        /* CSS Kustom untuk Tampilan Profesional */
        :root {
            --primary-color: #1e3a8a; /* Biru korporat yang lebih gelap */
            --secondary-color: #3b82f6; /* Biru yang lebih cerah untuk aksen */
            --background-color: #f8fafc; /* Latar belakang abu-abu sangat muda */
            --form-bg-color: #ffffff;
            --text-dark: #1e293b;
            --text-light: #64748b;
            --border-color: #e2e8f0;
            --success-color: #16a34a;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--background-color);
            color: var(--text-dark);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 30px 15px;
        }

        .guestbook-wrapper {
            max-width: 650px;
            width: 100%;
            background-color: var(--form-bg-color);
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .guestbook-header {
            background-color: var(--primary-color);
            color: #fff;
            padding: 40px;
            text-align: center;
        }
        
        .guestbook-header .logo {
            font-size: 2em;
            margin-bottom: 10px;
            color: #fff;
        }

        .guestbook-header h2 {
            margin: 0;
            font-weight: 700;
            font-size: 28px;
            letter-spacing: 0.5px;
        }

        .guestbook-header p {
            margin: 10px 0 0;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 400;
        }

        .guestbook-form {
            padding: 40px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 8px;
            color: var(--text-dark);
        }

        .form-control {
            height: 50px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            box-shadow: none;
            padding-left: 45px; /* Ruang untuk ikon */
            font-size: 15px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }
        
        textarea.form-control {
            height: auto;
            padding-top: 15px;
        }

        .form-group .form-icon {
            position: absolute;
            left: 15px;
            top: 42px;
            color: var(--text-light);
        }

        .btn-submit {
            background-color: var(--secondary-color);
            color: #fff;
            font-weight: 600;
            font-size: 16px;
            padding: 15px;
            border-radius: 8px;
            width: 100%;
            border: none;
            transition: background-color 0.3s, transform 0.2s;
        }

        .btn-submit:hover {
            background-color: var(--primary-color);
            color: #fff;
            transform: translateY(-2px);
        }
        
        .btn-submit .btn-icon {
            margin-right: 10px;
        }

        .guestbook-footer {
            background-color: var(--background-color);
            text-align: center;
            padding: 20px;
            font-size: 13px;
            color: var(--text-light);
        }
    </style>
</head>
<body>

<div class="guestbook-wrapper">
    <div class="guestbook-header">
        <div class="logo"><i class="fas fa-hospital-symbol"></i></div>
        <h2>Buku Tamu</h2>
        <p>Kami menghargai setiap masukan Anda untuk menjadi lebih baik.</p>
    </div>

    <div class="guestbook-form">
        <form action="proses/proses_guestbook.php" method="POST">
            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <i class="fas fa-user form-icon"></i>
                <input type="text" name="nama" id="nama" class="form-control" placeholder="Contoh: Budi Santoso" required>
            </div>
            
            <div class="form-group">
                <label for="email">Alamat Email</label>
                <i class="fas fa-envelope form-icon"></i>
                <input type="email" name="email" id="email" class="form-control" placeholder="Contoh: budi@email.com" required>
            </div>
            
            <div class="form-group">
                <label for="pesan">Pesan, Kritik, atau Saran</label>
                <i class="fas fa-pen-alt form-icon" style="top: 15px;"></i>
                <textarea name="pesan" id="pesan" class="form-control" rows="5" placeholder="Tuliskan pesan Anda di sini..." required></textarea>
            </div>
            
            <button type="submit" class="btn btn-submit">
                <i class="fas fa-paper-plane btn-icon"></i> Kirim Pesan
            </button>
        </form>
    </div>
    <div class="guestbook-footer">
        &copy; <?= date('Y'); ?> Prima Medical. Hak Cipta Dilindungi.
    </div>
</div>

</body>
</html>

