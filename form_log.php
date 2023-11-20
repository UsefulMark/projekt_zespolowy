<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        body {
            background: #222; /* Dark gray background */
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .container {
            max-width: 400px;
            width: 100%;
        }
        .wrapper {
            background: rgba(0, 0, 0, 0.7);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 30px;
        }
        .wrapper .title {
            color: #fff; /* White text color */
            font-size: 28px;
            font-weight: 600;
            text-align: center;
            margin-bottom: 20px;
        }
        .wrapper form {
            background-color: transparent;
        }
        .wrapper form label {
            font-size: 16px;
            font-weight: 600;
            display: block;
            margin-bottom: 8px;
            text-align: left;
            color: #fff;
        }
        .wrapper form input[type="text"],
        .wrapper form input[type="password"] {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            border-bottom: 2px solid #3498db;
            width: 100%;
            outline: none;
            padding: 10px;
            margin-bottom: 15px;
            color: #fff;
        }
        .wrapper form .button input {
            background-color: #3498db;
            color: #fff;
            font-size: 18px;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            display: block;
            margin: 0 auto;
            transition: background-color 0.3s;
        }
        .wrapper form .button input:hover {
            background: #0b7dda;
        }
        .wrapper form .signup-link {
            text-align: center;
            margin-top: 20px;
            color: #fff;
        }
        .wrapper form .signup-link a {
            color: #3498db;
            text-decoration: none;
            font-weight: 600;
        }
        .wrapper form .signup-link a:hover {
            text-decoration: none;
        }
        .signup-link{
          text-decoration: none;
        }
    </style>
</head>
<body>
<?php
session_start();
if (isset($_SESSION['login'])) {
    // Jeśli użytkownik jest już zalogowany, przekieruj do panel.php
    header("Location: panel.php");
    exit;
} else {
    // Jeśli użytkownik nie jest zalogowany, wyświetl formularz logowania
?>
    <div class="container">
        <div class="wrapper">
            <div class="title">Login</div>
            <form action="sprawdz_logowanie.php" method="post">
                <label for="login">Login:</label>
                <input type="text" name="login" required>

                <label for="haslo">Hasło:</label>
                <input type="password" name="haslo" required>

                <div class="row button">
                    <input type="submit" value="Zaloguj">
                </div>
            </form><br/>

            <div class="password-reset-link">
            Forgot your password? <a href="reset_password.php" style="color:#3498db">Recover password</a>
            </div>
			
            <div class="signup-link">
                Don't have an account? <a href="form_reg.html" style="color:#3498db">Sign up</a>
            </div>
        </div>
    </div>
<?php
}
?>
</body>
</html>
