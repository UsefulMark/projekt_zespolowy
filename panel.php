<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel użytkownika</title>
</head>
<body>
    <h1>Panel użytkownika</h1>
    <?php
    session_start(); // Rozpocznij sesję (jeśli jeszcze nie rozpoczęta)

    // Połącz się z bazą danych MSSQL
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
    
    // Sprawdź, czy użytkownik jest zalogowany (sprawdzamy, czy istnieje zmienna sesyjna "login" i czy pole "logged" jest ustawione na "true")
    if (isset($_SESSION['login'])) {
        $login = $_SESSION['login'];
    
        // Sprawdź, czy pole "logged" jest ustawione na "true" w bazie danych
        $sql_check_logged = "SELECT logged FROM users WHERE login = ?";
        $params_check_logged = array($login);
        $stmt_check_logged = sqlsrv_query($conn, $sql_check_logged, $params_check_logged);
    
        if ($stmt_check_logged === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    
        $row_check_logged = sqlsrv_fetch_array($stmt_check_logged, SQLSRV_FETCH_ASSOC);
    
        if ($row_check_logged['logged'] === 'true') {
            // Użytkownik jest zalogowany, kontynuuj wyświetlanie panelu
            // ...
        } else {
            // Jeżeli pole "logged" jest ustawione na "false" lub nie istnieje, przekieruj na stronę wylogowania
            echo "Nie jesteś zalogowany. Proszę zaloguj się ponownie.";
            header("Location: wyloguj.php"); // Przekieruj na stronę wylogowania
            exit();
        }
    }
    
    // Wczytaj dane filmów z pliku CSV
    $lines = file('gatunek.csv'); // Zastąp 'gatunek.csv' ścieżką do twojego pliku CSV
    $filmy = [];
    
    foreach ($lines as $line) {
        $data = str_getcsv($line);
        $tytul = $data[0];
        $gatunek = $data[1];
        $filmy[$gatunek][] = $tytul;
    }
    // Sprawdź, czy użytkownik jest zalogowany (sprawdzamy, czy istnieje zmienna sesyjna "login")
    if (isset($_SESSION['login'])) {
        // Połącz się z bazą danych MSSQL
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

        // Pobierz numer gatunku z bazy danych na podstawie zalogowanego użytkownika
        $login = $_SESSION['login'];

        $sql_get_gatunku = "SELECT numer_gatunku FROM users WHERE login = ?";
        $params_get_gatunku = array($login);
        $stmt_get_gatunku = sqlsrv_query($conn, $sql_get_gatunku, $params_get_gatunku);

        if ($stmt_get_gatunku === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        $row_get_gatunku = sqlsrv_fetch_array($stmt_get_gatunku, SQLSRV_FETCH_ASSOC);

        $numerGatunku = $row_get_gatunku['numer_gatunku'];

        // Wyświetl numer gatunku
        echo "Numer gatunku: " . $numerGatunku;

        echo "Login w sesji: " . $login;

        // Pobierz filmy na podstawie numeru gatunku
        if (isset($filmy[$numerGatunku])) {
            $rekomendacje = array_rand(array_slice($filmy[$numerGatunku], 1), 3);
        } else {
            $rekomendacje = array(); // Jeśli numer gatunku jest niepoprawny, nie wyświetlaj rekomendacji
        }

        if (!empty($rekomendacje)) {
            echo "<h3>Top 3 filmy w wybranym gatunku:</h3>";
            echo "<ul>";
            foreach ($rekomendacje as $filmIndex) {
                echo "<li>" . $filmy[$numerGatunku][$filmIndex + 1] . "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>Niepoprawny numer gatunku.</p>";
        }

        // Przycisk wylogowania
        echo "<form method='post' action='wyloguj.php'>
              <input type='submit' value='Wyloguj'>
              </form>";

        // Zamykanie połączenia z bazą danych
        sqlsrv_close($conn);
    } else {
        // Jeśli użytkownik nie jest zalogowany, przekieruj go na stronę logowania lub wyświetl odpowiedni komunikat
        echo "Nie jesteś zalogowany. Proszę zaloguj się.";
        // Możesz dodać przekierowanie na stronę logowania tutaj
    }
    ?>
</body>
</html>
