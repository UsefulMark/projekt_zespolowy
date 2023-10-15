<?php
// Rozpocznij sesję
session_start();

// Połączenie z bazą danych MSSQL
$serverName = "WIN-8PODA49PE73\\PANDORABASE"; // Adres serwera MSSQL
$connectionOptions = array(
    "Database" => "Projekt", // Nazwa bazy danych
    "Uid" => "sa", // Login użytkownika MSSQL
    "PWD" => "zaq1@WSX" // Hasło użytkownika MSSQL
);

$conn = sqlsrv_connect($serverName, $connectionOptions);

if (!$conn) {
    die("Błąd połączenia z bazą danych: " . sqlsrv_errors());
}

// Pobieranie danych z formularza
$login = $_POST['login'];
$haslo = $_POST['haslo'];

// Sprawdzenie, czy użytkownik o podanym loginie istnieje i czy hasło jest poprawne
$sql = "SELECT * FROM users WHERE login = ? AND haslo = ?";
$params = array($login, $haslo);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

if (sqlsrv_has_rows($stmt)) {
    // Użytkownik istnieje i dane logowania są poprawne
    // Zapisz login w sesji
    $_SESSION['login'] = $login;

    // Aktualizuj pole "logged" na TRUE
    $sql_update_logged = "UPDATE users SET logged = 'true' WHERE login = ?";
    $params_update_logged = array($login);
    $stmt_update_logged = sqlsrv_query($conn, $sql_update_logged, $params_update_logged);

    if ($stmt_update_logged === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    // Zalogowany użytkownik, przekieruj na stronę "panel.php"
    header("Location: panel.php");
} else {
    // Użytkownik niezalogowany, możesz przekierować na stronę błędu lub wyświetlić komunikat
    echo "Błąd logowania. Spróbuj ponownie.";
}

// Zamykanie połączenia z bazą danych
sqlsrv_close($conn);
?>
