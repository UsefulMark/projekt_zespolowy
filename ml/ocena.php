<?php
session_start();

if (isset($_POST['ocena'])) {
    $ocena = floatval($_POST['ocena']); // Konwertuj ocenę na liczbę zmiennoprzecinkową

    if ($ocena < 0 || $ocena > 5) {
        echo "<script>alert('Ocena musi być w zakresie od 0.0 do 5.0.');</script>";
    } else {
        // Zapisz ocenę filmu w bazie danych
        $filmTytul = htmlspecialchars($_POST['filmTytul']); // Pobierz tytuł filmu

        $login = $_SESSION['login'];

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

        // Pobierz numer gatunku na podstawie tytułu z pliku gatunek.csv
        $numerGatunku = pobierzNumerGatunku($filmTytul);

        if ($numerGatunku === false) {
            echo "<script>alert('Nie udało się znaleźć numeru gatunku dla filmu.');</script>";
        } else {
            // Aktualizuj bazę danych z numerem gatunku
            $sqlUpdate = "UPDATE $login SET ocena = ?, gatunek = ? WHERE nazwa = ?";
            $paramsUpdate = array($ocena, $numerGatunku, $filmTytul);

            $stmtUpdate = sqlsrv_query($conn, $sqlUpdate, $paramsUpdate);

            if ($stmtUpdate) {
                echo "<script>alert('Ocena filmu i numer gatunku zostały zapisane.');</script>";
                echo "<script>
                    setTimeout(function() {
                        window.location.href = '../panel.php'; // Przekieruj po naciśnięciu przycisku OK
                    }, 0);
                </script>";
            } else {
                $errors = sqlsrv_errors();
                $errorMessages = array();
                foreach ($errors as $error) {
                    $errorMessages[] = $error['message'];
                }
                $errorMessage = implode(', ', $errorMessages);

                echo "<script>alert('Błąd podczas zapisywania oceny filmu i numeru gatunku: $errorMessage');</script>";
            }
        }

        sqlsrv_close($conn);
    }
} else {
    echo "<script>alert('Nie przesłano oceny filmu.');</script>";
}

function pobierzNumerGatunku($filmTytul) {
    // Otwórz plik "gatunek.csv" i znajdź numer gatunku na podstawie tytułu
    $numerGatunku = false;
    $file = fopen("gatunek.csv", "r");

    if ($file !== false) {
        while ($row = fgetcsv($file)) {
            $tytul = $row[0];
            $numer = $row[1];

            if ($tytul === $filmTytul) {
                $numerGatunku = $numer;
                break;
            }
        }

        fclose($file);
    }

    return $numerGatunku;
}
?>
