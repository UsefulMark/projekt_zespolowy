<<<<<<< HEAD
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

// Pobieranie danych z formularza
$imie = $_POST['imie'];
$nazwisko = $_POST['nazwisko'];
$login = $_POST['login'];
$haslo = $_POST['haslo'];
$numer_gatunku = $_POST['numer_gatunku']; // Dodano pobieranie numeru gatunku

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
$sql = "INSERT INTO users (imie, nazwisko, login, haslo, numer_gatunku) VALUES (?, ?, ?, ?, ?)"; // Dodano "numer_gatunku" do zapytania
$params = array($imie, $nazwisko, $login, $haslo, $numer_gatunku); // Dodano $numer_gatunku do tablicy $params
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Utwórz tabelę dla użytkownika
$sql_create_table = "CREATE TABLE $login (film_id INT IDENTITY(1,1) PRIMARY KEY, nazwa VARCHAR(255), ocena INT)
";
$stmt_create_table = sqlsrv_query($conn, $sql_create_table);


if ($stmt_create_table === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Zarejestrowano użytkownika, wyświetl komunikat w przeglądarce
echo '<script>alert("Zarejestrowano użytkownika: ' . $login . '");</script>';

// Zamykanie połączenia z bazą danych
sqlsrv_close($conn);

// Przekierowanie na stronę "index.html" po 3 sekundach
echo '<script>window.setTimeout(function() { window.location = "index.html"; }, 3000);</script>'; // Poprawiono czas opóźnienia
?>
=======
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

// Pobieranie danych z formularza
$imie = $_POST['imie'];
$nazwisko = $_POST['nazwisko'];
$login = $_POST['login'];
$haslo = $_POST['haslo'];
$numer_gatunku = $_POST['numer_gatunku']; // Dodano pobieranie numeru gatunku

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
$sql = "INSERT INTO users (imie, nazwisko, login, haslo, numer_gatunku) VALUES (?, ?, ?, ?, ?)"; // Dodano "numer_gatunku" do zapytania
$params = array($imie, $nazwisko, $login, $haslo, $numer_gatunku); // Dodano $numer_gatunku do tablicy $params
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Utwórz tabelę dla użytkownika
$sql_create_table = "CREATE TABLE $login (ocena INT, nazwa VARCHAR(255))";
$stmt_create_table = sqlsrv_query($conn, $sql_create_table);

if ($stmt_create_table === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Zarejestrowano użytkownika, wyświetl komunikat w przeglądarce
echo '<script>alert("Zarejestrowano użytkownika: ' . $login . '");</script>';

// Zamykanie połączenia z bazą danych
sqlsrv_close($conn);

// Przekierowanie na stronę "index.html" po 3 sekundach
echo '<script>window.setTimeout(function() { window.location = "index.html"; }, 3000);</script>'; // Poprawiono czas opóźnienia
?>
>>>>>>> 49975b138d89528fff44cbac0f18bfcf6584d405
