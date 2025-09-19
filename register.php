<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Akun Baru</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome untuk ikon mata -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" xintegrity="sha512-iecdLpQ3d/QzT7cT7r9T9yFwW5bNq9QpT8Lp+V5o3t4C6Gf7A7S8L8G7e3QzP6D5C..." crossorigin="anonymous" referrerpolicy="no-referrer" />
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
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }

        .register-card {
            background: var(--input-bg);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px var(--shadow-color);
            text-align: center;
            width: 100%;
            max-width: 600px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .register-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .register-card h2 {
            font-size: 2.2em;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .register-card p {
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
        
        .form-row {
            display: flex;
            gap: 20px;
            width: 100%;
        }

        .form-row .input-group {
            flex: 1;
        }
        
        .input-group input,
        .input-group textarea,
        .input-group select {
            width: 100%;
            padding: 12px 20px;
            border: 2px solid #ddd;
            border-radius: 10px;
            font-size: 16px;
            box-sizing: border-box;
            transition: border-color 0.3s, box-shadow 0.3s;
            font-family: 'Poppins', sans-serif;
        }

        .input-group input:focus,
        .input-group textarea:focus,
        .input-group select:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(63, 81, 181, 0.2);
        }
        
        .gender-options {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .gender-options label {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-bottom: 0;
            font-weight: 400;
            color: var(--text-dark);
        }
        .gender-options input[type="radio"] {
            width: auto;
        }

        .button-group {
            display: flex;
            justify-content: center;
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
        
        .btn-submit {
            background-color: var(--primary-color);
            color: #fff;
        }
        
        .btn-submit:hover {
            background-color: #303f9f;
            transform: translateY(-2px);
        }

        .btn-clear {
            background-color: #fff;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
        }
        
        .btn-clear:hover {
            background-color: var(--primary-color);
            color: #fff;
            transform: translateY(-2px);
        }
        
        /* Kontainer untuk input dan ikon mata */
        .password-container {
            position: relative;
        }
        .password-container input {
            padding-right: 45px;
        }
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #888;
            font-size: 1.2rem;
            line-height: 1;
            transition: color 0.3s ease;
        }
        .password-toggle:hover {
            color: #333;
        }

        /* Responsive */
        @media (max-width: 600px) {
            .form-row {
                flex-direction: column;
                gap: 0;
            }
        }
    </style>
</head>
<body>
    <div class="register-card">
        <h2>Registrasi Akun Baru</h2>
        <p>Silakan isi formulir di bawah ini.</p>
        
        <form action="proses/register.php" method="POST" autocomplete="off">
            <div class="form-row">
                <div class="input-group">
                    <label for="nama">Nama :</label>
                    <input type="text" id="nama" name="nama" required>
                </div>
                <div class="input-group">
                    <label for="email">E-mail :</label>
                    <input type="email" id="email" name="email" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="input-group">
                    <label for="username">Username :</label>
                    <input type="text" id="username" name="username" required autocomplete="off">
                </div>
                <div class="input-group">
                    <label for="telp">Contect no :</label>
                    <input type="tel" id="telp" name="telp" required pattern="\d*" oninput="this.value = this.value.replace(/[^0-9]/g, '');" autocomplete="off">
                </div>
            </div>
            
            <div class="form-row">
                <div class="input-group">
                    <label for="password">Password :</label>
                    <div class="password-container">
                        <input type="password" id="password" name="password" required autocomplete="new-password">
                        <span class="password-toggle" onclick="togglePasswordVisibility('password')"><i class="far fa-eye"></i></span>
                    </div>
                </div>
                <div class="input-group">
                    <label for="konfirmasi">Retype-Password :</label>
                    <div class="password-container">
                        <input type="password" id="konfirmasi" name="konfirmasi" required autocomplete="new-password">
                        <span class="password-toggle" onclick="togglePasswordVisibility('konfirmasi')"><i class="far fa-eye"></i></span>
                    </div>
                </div>
            </div>
            
            <!-- Bidang-bidang lain dari desain lama -->
            <div class="form-row">
                <div class="input-group">
                    <label for="dob">Date of birth :</label>
                    <input type="date" id="dob" name="dob">
                </div>
                <div class="input-group">
                    <label>Gender :</label>
                    <div class="gender-options">
                        <input type="radio" id="male" name="gender" value="Male" checked>
                        <label for="male">Male</label>
                        <input type="radio" id="female" name="gender" value="Female">
                        <label for="female">Female</label>
                    </div>
                </div>
            </div>
            <div class="input-group">
                <label for="address">Address :</label>
                <textarea id="address" name="address" rows="3"></textarea>
            </div>
            <div class="form-row">
                <div class="input-group">
                    <label for="city">City :</label>
                    <select id="city" name="city">
                        <option value="Jakarta">Jakarta</option>
                        <option value="Surabaya">Surabaya</option>
                    </select>
                </div>
                <div class="input-group">
                    <label for="paypal_id">Pay-pal id :</label>
                    <input type="email" id="paypal_id" name="paypal_id" placeholder="Pay-pal id">
                </div>
            </div>

            <div class="button-group">
                <button type="submit" class="btn btn-submit">Submit</button>
                <button type="button" class="btn btn-clear" onclick="this.form.reset();">Clear</button>
            </div>
        </form>
    </div>
    
    <script>
        function togglePasswordVisibility(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const toggleIcon = passwordInput.parentNode.querySelector('.password-toggle i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
