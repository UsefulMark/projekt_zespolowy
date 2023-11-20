<?php
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

// Pobieranie danych z formularza i walidacja
$imie = $_POST['imie'];
$nazwisko = $_POST['nazwisko'];
$login = $_POST['login'];
$haslo = $_POST['haslo'];
$numer_gatunku = $_POST['numer_gatunku']; // Dodano pobieranie numeru gatunku
$przypomnij = $_POST['przypomnij']; // Dodano pole przypomnij

if (empty($imie) || empty($nazwisko) || empty($login) || empty($haslo) || empty($numer_gatunku) || empty($przypomnij)) {
    // Wyświetl komunikat o brakujących danych
    echo '<script>alert("Wszystkie pola formularza są obowiązkowe. Proszę wypełnić formularz.");</script>';
    echo '<script>history.back();</script>'; // Powrót do poprzedniej strony

    // Zakończ skrypt
    exit();
}

// Dodatkowa walidacja
if (strlen($imie) < 3 || strlen($nazwisko) < 3) {
    echo '<script>alert("Imię i nazwisko muszą mieć co najmniej 3 znaki.");</script>';
    echo '<script>history.back();</script>';
    exit();
}

if (strlen($login) < 5) {
    echo '<script>alert("Login musi mieć co najmniej 5 znaków.");</script>';
    echo '<script>history.back();</script>';
    exit();
}

if (strlen($haslo) < 10 || !preg_match('/[A-Z]/', $haslo) || !preg_match('/\d/', $haslo) || !preg_match('/[^A-Za-z0-9]/', $haslo)) {
    echo '<script>alert("Hasło musi mieć co najmniej 10 znaków, zawierać co najmniej jedną dużą literę, jedną cyfrę i jeden znak specjalny.");</script>';
    echo '<script>history.back();</script>';
    exit();
}

if (empty($numer_gatunku)) {
    echo '<script>alert("Proszę wybrać numer gatunku.");</script>';
    echo '<script>history.back();</script>';
    exit();
}

// Sprawdzenie, czy użytkownik o podanym loginie już istnieje
$sql_check = "SELECT COUNT(*) as count FROM users WHERE login = ?";
$params_check = array($login);
$stmt_check = sqlsrv_query($conn, $sql_check, $params_check);

if ($stmt_check === false) {
    die(print_r(sqlsrv_errors(), true));
}

$row = sqlsrv_fetch_array($stmt_check, SQLSRV_FETCH_ASSOC);

if ($row['count'] > 0) {
    // Użytkownik o podanym loginie już istnieje, wyświetl komunikat w przeglądarce
    echo '<script>alert("Użytkownik o loginie ' . $login . ' już istnieje. Proszę wybrać inny login.");</script>';
    echo '<script>history.back();</script>'; // Powrót do poprzedniej strony

    // Zakończ skrypt
    exit();
}

// Użytkownik o podanym loginie nie istnieje, można dokonać rejestracji

// Wprowadzenie danych do bazy danych
$hash_password= password_hash($haslo, PASSWORD_DEFAULT); // Dodano hashowanie hasła
$sql = "INSERT INTO users (imie, nazwisko, login, haslo, numer_gatunku, przypomnij) VALUES (?, ?, ?, ?, ?, ?)"; // Dodano "przypomnij" do zapytania
$params = array($imie, $nazwisko, $login, $hash_password, $numer_gatunku, $przypomnij); // Dodano $przypomnij do tablicy $params
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Utwórz tabelę dla użytkownika
$sql_create_table = "CREATE TABLE $login (film_id INT IDENTITY(1,1) PRIMARY KEY, nazwa VARCHAR(255), ocena INT, gatunek INT)";
$stmt_create_table = sqlsrv_query($conn, $sql_create_table);

if ($stmt_create_table === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Zarejestrowano użytkownika, wyświetl komunikat w przeglądarce
echo '<script>alert("Zarejestrowano użytkownika: ' . $login . '");</script>';

// Zamykanie połączenia z bazą danych
sqlsrv_close($conn);

// Przekierowanie na stronę "index.html" po 3 sekundach
echo '<script>window.setTimeout(function() { window.location = "index.html"; }, 100);</script>'; // Poprawiono czas opóźnienia
?>
