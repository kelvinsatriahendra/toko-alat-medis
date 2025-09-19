<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Toko Online</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #3f51b5;
            --secondary-color: #f44336;
            --bg-light: #e8eaf6;
            --text-dark: #333;
            --input-bg: #fff;
            --shadow-color: rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg-light);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }

        .login-card {
            background: var(--input-bg);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px var(--shadow-color);
            text-align: center;
            width: 100%;
            max-width: 450px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .login-card h2 {
            font-size: 2.2em;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .login-card p {
            color: #777;
            margin-bottom: 30px;
        }

        .input-group {
            position: relative;
            margin-bottom: 25px;
            text-align: left;
        }
        
        .input-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--primary-color);
            font-weight: 500;
        }
        
        /* Kontainer baru untuk input password */
        .password-container {
            position: relative;
        }

        .password-container input {
            width: 100%;
            padding: 12px 20px;
            padding-right: 45px; /* Memberi ruang untuk ikon mata */
            border: 2px solid #ddd;
            border-radius: 10px;
            font-size: 16px;
            box-sizing: border-box;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        
        .password-container input:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(63, 81, 181, 0.2);
        }
        
        /* Styling untuk input lain */
        .input-group input {
            width: 100%;
            padding: 12px 20px;
            border: 2px solid #ddd;
            border-radius: 10px;
            font-size: 16px;
            box-sizing: border-box;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        /* Styling for show password icon */
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #888;
            font-size: 1.2rem;
            line-height: 1; /* Penting untuk penataan vertikal */
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: #333;
        }

        .btn-container {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 10px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            text-transform: uppercase;
            transition: background-color 0.3s, transform 0.2s;
            flex-grow: 1;
        }
        
        .btn-login {
            background-color: var(--primary-color);
            color: #fff;
        }
        
        .btn-login:hover {
            background-color: #303f9f;
            transform: translateY(-2px);
        }

        .btn-register {
            background-color: #fff;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
        }
        
        .btn-register:hover {
            background-color: var(--primary-color);
            color: #fff;
            transform: translateY(-2px);
        }

        .btn-register a {
            text-decoration: none;
            color: inherit;
        }

        /* Responsive */
        @media (max-width: 500px) {
            .login-card {
                margin: 20px;
                padding: 30px;
            }
            .btn-container {
                flex-direction: column;
            }
            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>Selamat Datang</h2>
        <p>Silakan login untuk masuk ke akun Anda.</p>
        
        <form action="proses/login.php" method="POST" autocomplete="off">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" placeholder="Masukkan Username" name="username" required autocomplete="off">
            </div>
            
            <div class="input-group">
                <label for="password">Password</label>
                <div class="password-container">
                    <input type="password" id="password" placeholder="Masukkan Password" name="pass" required autocomplete="new-password">
                    <span class="password-toggle" onclick="togglePasswordVisibility()">&#x1f441;</span>
                </div>
            </div>
            
            <div class="btn-container">
                <button type="submit" class="btn btn-login">Login</button>
                <button type="button" class="btn btn-register" onclick="window.location.href='register.php'">Daftar</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const usernameInput = document.getElementById('username');
            const passwordInput = document.getElementById('password');
            
            // Clear inputs on page load to prevent browser autofill
            usernameInput.value = '';
            passwordInput.value = '';
        });

        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.querySelector('.password-toggle');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.textContent = '\u{1F440}'; // Mata terbuka
            } else {
                passwordInput.type = 'password';
                toggleIcon.textContent = '\u{1F441}'; // Mata tertutup
            }
        }
    </script>
</body>
</html>
