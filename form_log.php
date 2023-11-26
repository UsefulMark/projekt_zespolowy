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
            background: url('form1.png') no-repeat center center fixed;
            background-size: cover;
            overflow: hidden;
        }
        .container {
            max-width: 475px;
            padding: 0 19px;
            margin: 95px auto;
        }
        .wrapper {
            width: 100%;
            background: rgba(20, 20, 20, 0.85);
            border-radius: 5px;
            box-shadow: 0px 4px 10px 1px rgba(0, 0, 0, 0.1);
            transform: scale(1);
        }
        .wrapper .title {
            height: 50px;
            background: #333;
            border-radius: 5px 5px 0 0;
            color: #d2b48c;
            font-size: 36px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .wrapper form {
            padding: 30px 35px 35px 35px;
            background-color: rgba(0, 0, 0, 0.85);
            color: #d2b48c;
            font-weight: bold;
        }
        .wrapper form input[type="text"],
        .wrapper form input[type="password"] {
            background-color: rgba(255, 255, 255, 0.1);
            color: #d2b48c;
            border: 1px solid #d2b48c;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 20px;
            width: calc(100% - 20px);
            transition: all 0.3s ease;
            font-weight: bold;
        }
        .wrapper form input:focus {
            border-color: #d2b48c;
            box-shadow: none;
        }
        .wrapper form input::placeholder {
            color: #d2b48c;
        }
        .wrapper form .button input {
            background-color: #d2b48c;
            color: #333;
            font-size: 24px;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            margin-top: 5%;
            display: block;
            transition: all 0.3s ease;
        }
        .wrapper form .button input:hover {
            background: #a67c00;
            color: #fff;
        }
        .wrapper .password-reset-link,
        .wrapper .signup-link {
            color: #d2b48c;
            text-align: center;
            display: block;
            margin-top: 20px;
        }
        .wrapper .password-reset-link a,
        .wrapper .signup-link a {
            color: #d2b48c;
            text-decoration: none;
            font-weight: bold;
        }
        .wrapper .password-reset-link a:hover,
        .wrapper .signup-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<?php
session_start();
if (isset($_SESSION['login'])) {
    // If the user is already logged in, redirect to panel.php
    header("Location: panel.php");
    exit;
} else {
    // If the user is not logged in, display the login form
?>
    <div class="container">
        <div class="wrapper">
            <div class="title">Login</div>
            <form action="sprawdz_logowanie.php" method="post">
                <label for="login">Login:</label>
                <input type="text" name="login" required>

                <label for="haslo">Password:</label>
                <input type="password" name="haslo" required>

                <div class="row button"><center>
                    <input type="submit" value="Login">
                </div></center>
            </form><br/>

            <div class="password-reset-link">
            Forgot your neurons? <a href="reset_password.php">Recover password</a>
            </div>
			
            <div class="signup-link">
                Don't have an account? <a href="form_reg.html">Sign up</a>
            </div>
        </div>
    </div>
<?php
}
?>
</body>
</html>
