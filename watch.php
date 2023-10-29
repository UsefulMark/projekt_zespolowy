<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Obejrzyj film</title>
    <!-- Dodaj dowolne CSS lub Bootstrap, które chcesz użyć na stronie 'watch.php' -->
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <?php
            session_start();
            if (isset($_GET['title'])) {
                $filmTytul = urldecode($_GET['title']);
                echo "<h1 class='display-4'>Obejrzyj film: $filmTytul</h1>";

                // Sprawdzenie, czy użytkownik jest zalogowany
                if (isset($_SESSION['login'])) {
                    $login = $_SESSION['login'];

                    // Nawiązanie połączenia z bazą danych
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

                    // Wstawienie nazwy filmu do tabeli użytkownika
                    $sqlInsert = "INSERT INTO $login (nazwa, ocena) VALUES (?, 0)";
                    $paramsInsert = array($filmTytul);

                    $stmtInsert = sqlsrv_query($conn, $sqlInsert, $paramsInsert);

                    if ($stmtInsert) {
                        echo "Nazwa filmu została dodana do twojej tabeli użytkownika.";
                    } else {
                        $errors = sqlsrv_errors();
                        $errorMessages = array();
                        foreach ($errors as $error) {
                            $errorMessages[] = $error['message'];
                        }
                        $errorMessage = implode(', ', $errorMessages);
                    
                        echo "<p class='alert alert-danger'>Błąd podczas dodawania nazwy filmu do twojej tabeli użytkownika: $errorMessage</p>";
                    }

                    // Zamknięcie połączenia z bazą danych
                    sqlsrv_close($conn);
                } else {
                    echo "<p class='alert alert-danger'>Użytkownik nie jest zalogowany.</p>";
                }
            } else {
                echo "<p class='alert alert-danger'>Nie wybrano filmu do obejrzenia.</p>";
            }
            ?>
        </div>
    </div>
</div>
</body>
</html>
