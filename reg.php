<?php
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST["FirstName"];
    $lastName = $_POST["LastName"];
    $email = $_POST["Email"];
    $password = $_POST["Password"];
    $confirmPassword = $_POST["ConfirmPassword"];

    // Dodaj odpowiednie walidacje pól formularza

    if ($password !== $confirmPassword) {
        echo "Hasła nie pasują do siebie.";
    } else {
        // Haszowanie hasła przed zapisaniem do bazy danych (zalecane)
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Wstawianie danych do tabeli "Users"
        $sql = "INSERT INTO Users (name, surname, login, password) VALUES (?, ?, ?, ?)";
        $params = array($firstName, $lastName, $email, $hashedPassword);
        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt === false) {
            die("Błąd podczas dodawania użytkownika: " . sqlsrv_errors());
        } else {
            header("Location: sukces.html"); // Przekierowanie na stronę sukcesu
            exit();
        }
    }

    // Zamknięcie połączenia z bazą danych
    sqlsrv_close($conn);
}
?>
