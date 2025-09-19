<?php
session_start();
if (isset($_SESSION['admin'])) {
  header('location:halaman_utama.php');
  exit();
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Login Admin | Prima Medical</title>
  <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="../css/style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary-color: #1e3a5f;
      --secondary-color: #27ae60;
      --light-bg: #f5f7fa;
      --text-dark: #333;
      --shadow-light: rgba(0, 0, 0, 0.1);
      --error-color: #e74c3c;
    }

    body {
      font-family: 'Montserrat', sans-serif;
      background-color: var(--light-bg);
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      margin: 0;
      padding: 20px;
    }

    .login-card {
      background-color: #fff;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 10px 30px var(--shadow-light);
      width: 100%;
      max-width: 400px;
      text-align: center;
    }

    .login-card h2 {
      font-size: 2.2em;
      font-weight: 700;
      color: var(--primary-color);
      margin-bottom: 25px;
      border-bottom: 3px solid var(--primary-color);
      padding-bottom: 10px;
      display: inline-block;
    }

    .form-group {
      margin-bottom: 20px;
      text-align: left;
    }

    .form-group label {
      font-weight: 600;
      color: var(--primary-color);
      margin-bottom: 8px;
      display: block;
    }

    .form-control {
      width: 100%;
      padding: 12px;
      border: 1px solid #ddd;
      border-radius: 8px;
      font-size: 1em;
    }

    .btn-login {
      background-color: var(--secondary-color);
      color: #fff;
      width: 100%;
      padding: 12px;
      border: none;
      border-radius: 50px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1px;
      cursor: pointer;
      margin-top: 15px;
    }

    .alert-error {
      background-color: #fbeae5;
      color: var(--error-color);
      border-left: 4px solid var(--error-color);
      padding: 12px 15px;
      border-radius: 4px;
      margin-bottom: 20px;
      text-align: left;
    }
  </style>
</head>

<body>
  <div class="login-card">
    <h2>Login Admin</h2>

    <!-- [PERBAIKAN FINAL] autocomplete="new-password" digunakan untuk memaksa browser tidak auto-isi -->
    <form action="proses/login.php" method="POST" autocomplete="new-password">

      <?php
      if (isset($_GET['error']) && $_GET['error'] == 1) {
        echo '<div class="alert-error">Username atau Password salah!</div>';
      }
      ?>

      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" class="form-control" id="username" placeholder="Masukkan Username" name="user" autofocus required autocomplete="new-password">
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control" id="password" placeholder="Masukkan Password" name="pass" required autocomplete="new-password">
      </div>

      <button type="submit" class="btn-login">Login</button>
    </form>
  </div>
</body>

</html>