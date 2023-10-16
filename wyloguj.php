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

// Pobierz login użytkownika z sesji
$login = $_SESSION['login'];

// Aktualizuj pole "logged" na FALSE
$sql_update_logged = "UPDATE users SET logged = 'false' WHERE login = ?";
$params_update_logged = array($login);
$stmt_update_logged = sqlsrv_query($conn, $sql_update_logged, $params_update_logged);

if ($stmt_update_logged === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Zakończ sesję
session_destroy();

// Przekieruj użytkownika na stronę logowania lub inną stronę
header("Location: index.html"); // Zastąp "strona_logowania.php" odpowiednią ścieżką
exit();
?>
