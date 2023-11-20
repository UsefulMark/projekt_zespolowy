<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Zmiana hasła użytkownika</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #222; /* Dark gray background */
            font-family: 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            max-width: 400px;
            width: 100%;
            margin-top: -100px;
        }
        .wrapper {
            background: rgba(0, 0, 0, 0.8);
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .wrapper h1 {
            color: #fff;
            text-align: center;
            margin-bottom: 20px;
        }
        .wrapper form {
            background-color: transparent;
        }
        .wrapper form label {
            font-size: 16px;
            color: #fff;
            margin-bottom: 5px;
            display: block;
        }
        .wrapper form input[type="text"],
        .wrapper form input[type="password"],
        .wrapper form input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #3498db;
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }
        .wrapper form input[type="submit"] {
            background-color: #3498db;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .wrapper form input[type="submit"]:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="wrapper">
            <h1>Zmiana hasła użytkownika</h1>
            <form method="post" action="">
                <div class="mb-3">
                    <label for="login" class="form-label">Podaj login:</label>
                    <input type="text" class="form-control" name="login" required>
                </div>
                <div class="mb-3">
                    <label for="przypomnij" class="form-label">Twoje przezwisko (do odzyskania hasła):</label>
                    <input type="text" class="form-control" name="przypomnij" required>
                </div>
                <div class="mb-3">
                    <label for="new_password" class="form-label">Nowe hasło:</label>
                    <input type="password" class="form-control" name="new_password" required>
                </div>
                <div class="d-grid gap-2">
                    <input type="submit" class="btn btn-primary" value="Zmień hasło">
                </div>
            </form>
        </div>
    </div>
    <?php
    session_start(); // Rozpocznij sesję

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Połączenie z bazą danych MSSQL
        $serverName = "WIN-8PODA49PE73\\PANDORABASE";
        $connectionOptions = array(
            "Database" => "Projekt",
            "Uid" => "sa",
            "PWD" => "zaq1@WSX"
        );

        $conn = sqlsrv_connect($serverName, $connectionOptions);

        if (!$conn) {
            die("Błąd połączenia z bazą danych: " . sqlsrv_errors());
        }

        $login = $_POST["login"];
        $przypomnij = $_POST["przypomnij"];
        $haslo = $_POST["new_password"];

        // Walidacja hasła
        if (strlen($haslo) < 5 || !preg_match('/[A-Z]/', $haslo) || !preg_match('/\d/', $haslo) || !preg_match('/[^A-Za-z0-9]/', $haslo)) {
            echo '<script>alert("Hasło musi mieć co najmniej 5 znaków, zawierać co najmniej jedną dużą literę, jedną cyfrę i jeden znak specjalny.");</script>';
            echo '<script>history.back();</script>';
            sqlsrv_close($conn);
            exit();
        }

        // Sprawdzenie, czy podane dane (login i przypomnij) są poprawne
        $sql_check = "SELECT COUNT(*) as count FROM users WHERE login = ? AND przypomnij = ?";
        $params_check = array($login, $przypomnij);
        $stmt_check = sqlsrv_query($conn, $sql_check, $params_check);

        if ($stmt_check === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        $row = sqlsrv_fetch_array($stmt_check, SQLSRV_FETCH_ASSOC);

        if ($row['count'] > 0) {
            // Zapytanie do bazy danych w celu aktualizacji hasła użytkownika
            $hash_password = password_hash($haslo, PASSWORD_DEFAULT);
            $sql_update = "UPDATE users SET haslo = ? WHERE login = ?";
            $params_update = array($hash_password, $login);
            $stmt_update = sqlsrv_query($conn, $sql_update, $params_update);

            if ($stmt_update === false) {
                die(print_r(sqlsrv_errors(), true));
            }

            $rows_affected = sqlsrv_rows_affected($stmt_update);

            if ($rows_affected > 0) {
                echo '<script>
                    var confirmChange = confirm("Hasło użytkownika zostało zaktualizowane. Kliknij OK, aby przejść do form_log.php.");
                    if (confirmChange) {
                        window.location.href = "form_log.php"; // Przekierowanie na stronę form_log.php po udanej zmianie hasła
                    }
                </script>';
                exit();
            } else {
                echo "Użytkownik o podanym loginie nie istnieje.";
            }
        } else {
            echo "Podane dane są nieprawidłowe. Sprawdź login i przypomnienie hasła.";
        }

        // Zamykanie połączenia z bazą danych
        sqlsrv_close($conn);
    }
    ?>
</body>
</html>
