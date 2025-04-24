<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bass Restoran - Login</title>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <link rel='stylesheet' href='https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap'>
    <style>
        * {
            font-family: "Poppins", sans-serif;
            box-sizing: border-box;
        }

        body {
            background: url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 0;
        }

        .screen-1 {
            background: rgba(255, 255, 255, 0.98);
            padding: 2.5em;
            display: flex;
            flex-direction: column;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            gap: 1.8em;
            width: 90%;
            max-width: 420px;
            position: relative;
            z-index: 1;
            transform: translateY(0);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .screen-1:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        .header {
            text-align: center;
            margin-bottom: 1em;
        }

        .header .logo-text {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 1em;
        }

        .header i {
            font-size: 32px;
            color: rgb(100, 53, 255);
            transition: transform 0.3s ease;
        }

        .header i:hover {
            transform: scale(1.1);
        }

        .header h2 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
            color: #333;
            letter-spacing: 0.5px;
        }

        .header p {
            color: #666;
            margin: 0.5em 0 0;
            font-size: 0.95em;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 1.2em; /* Increased gap between form fields */
        }

        .username,
        .password {
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 1.2em;
            display: flex;
            flex-direction: column;
            gap: 0.5em;
            border-radius: 12px;
            color: #4d4d4d;
            border: 1px solid #eee;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .username:focus-within,
        .password:focus-within {
            border-color: rgb(27, 17, 211);
            box-shadow: 0 0 0 2px rgba(255, 107, 53, 0.2);
        }

        .username label,
        .password label {
            font-size: 0.9em;
            font-weight: 500;
            color: #555;
            margin-bottom: 0.3em;
        }

        .sec-2 {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .username input,
        .password input {
            outline: none;
            border: none;
            font-size: 1em;
            width: 100%;
            background: transparent;
            color: #333;
        }

        .username input::placeholder,
        .password input::placeholder {
            color: #aaa;
            font-weight: 300;
        }

        .username ion-icon,
        .password ion-icon {
            color: #777;
            font-size: 1.2em;
        }

        .password .show-hide {
            margin-left: auto;
            cursor: pointer;
            color: #777;
            transition: color 0.2s ease;
        }

        .password .show-hide:hover {
            color: rgb(67, 53, 255);
        }

        .login {
            padding: 1em;
            background: rgb(80, 53, 255);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1em;
            cursor: pointer;
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-top: 0.5em;
            width: 100%;
        }

        .login:hover {
            background-color: rgb(115, 43, 230);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
        }

        .footer {
            text-align: center;
            font-size: 0.85em;
            color: #777;
            margin-top: 1em;
        }

        .footer a {
            color: rgb(87, 53, 255);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .footer a:hover {
            color: rgb(90, 43, 230);
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .screen-1 {
                padding: 2em 1.5em;
                border-radius: 12px;
            }

            .header h2 {
                font-size: 22px;
            }
        }
    </style>
</head>

<body>
    <div class="screen-1">
        <form action="loginproses.php" method="POST">
            <div class="header">
                <div class="logo-text">
                    <i class="fa-solid fa-drumstick-bite"></i>
                    <h2>Bass Restoran</h2>
                </div>
                <p>Sign in to your account</p>
            </div>

            <div class="form-group">
                <div class="username">
                    <label for="username">Username</label>
                    <div class="sec-2">
                        <ion-icon name="person-outline"></ion-icon>
                        <input type="text" name="username" placeholder="Masukkan username" />
                    </div>
                </div>

                <div class="password">
                    <label for="password">Password</label>
                    <div class="sec-2">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input class="pas" type="password" name="password" placeholder="············" />
                        <ion-icon class="show-hide" name="eye-outline"></ion-icon>
                    </div>
                </div>
            </div>

            <button class="login" type="submit">Login</button>
        </form>
    </div>

    <script>
        // Toggle password visibility
        const showHide = document.querySelector('.show-hide');
        const passwordInput = document.querySelector('.pas');

        showHide.addEventListener('click', () => {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                showHide.name = 'eye-off-outline';
            } else {
                passwordInput.type = 'password';
                showHide.name = 'eye-outline';
            }
        });
    </script>
</body>

</html>