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
        <label for="przypomnij">Twoje przezwisko (do odzyskania hasła):</label>
        <input type="text" name="przypomnij" required>
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
    $przypomnij = $_POST["przypomnij"];
    $haslo = $_POST["new_password"];

    //Walidacja hasla
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
        $sql_update = "UPDATE users SET haslo = ? WHERE login = ?";
        $params_update = array($haslo, $login); // Poprawione $new_password na $haslo
        $stmt_update = sqlsrv_query($conn, $sql_update, $params_update);

        if ($stmt_update === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        $rows_affected = sqlsrv_rows_affected($stmt_update);

        if ($rows_affected > 0) {
            echo "Hasło użytkownika zostało zaktualizowane.";
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