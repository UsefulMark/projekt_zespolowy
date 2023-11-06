<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Zmiana hasła użytkownika</title>
</head>
<body>
    <h1>Zmiana hasła użytkownika</h1>
    <form method="post" action="">
        <label for="login">Podaj login:</label>
        <input type="text" name="login" required>
        <label for="new_password">Nowe hasło:</label>
        <input type="password" name="new_password" required>
        <input type="submit" value="Zmień hasło">
    </form>
    <?php
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
        $new_password = $_POST["new_password"];

        // Zapytanie do bazy danych w celu aktualizacji hasła użytkownika
        $sql = "UPDATE users SET haslo = ? WHERE login = ?";
        $params = array($new_password, $login);
        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        $rows_affected = sqlsrv_rows_affected($stmt);

        if ($rows_affected > 0) {
            echo "Hasło użytkownika zostało zaktualizowane.";
        } else {
            echo "Użytkownik o podanym loginie nie istnieje.";
        }

        // Zamykanie połączenia z bazą danych
        sqlsrv_close($conn);
    }
    ?>
</body>
</html>
