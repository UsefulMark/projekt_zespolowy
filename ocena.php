<?php
session_start();

if (isset($_POST['ocena'])) {
    $ocena = floatval($_POST['ocena']); // Konwertuj ocenę na liczbę zmiennoprzecinkową

    if ($ocena < 0 || $ocena > 5) {
        echo "<p class='alert alert-danger'>Ocena musi być w zakresie od 0.0 do 5.0.</p>";
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

        $sqlUpdate = "UPDATE $login SET ocena = ? WHERE nazwa = ?";
        $paramsUpdate = array($ocena, $filmTytul);

        $stmtUpdate = sqlsrv_query($conn, $sqlUpdate, $paramsUpdate);

        if ($stmtUpdate) {
            echo "<p class='alert alert-success'>Ocena filmu została zapisana.</p>";
        } else {
            $errors = sqlsrv_errors();
            $errorMessages = array();
            foreach ($errors as $error) {
                $errorMessages[] = $error['message'];
            }
            $errorMessage = implode(', ', $errorMessages);

            echo "<p class='alert alert-danger'>Błąd podczas zapisywania oceny filmu: $errorMessage</p>";
        }

        sqlsrv_close($conn);
    }
} else {
    echo "<p class='alert alert-danger'>Nie przesłano oceny filmu.</p>";
}
?>
