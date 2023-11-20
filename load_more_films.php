<?php
// Rozpoczęcie sesji i wczytanie danych logowania użytkownika
session_start();
$login = $_SESSION['login'];

// Konfiguracja połączenia z bazą danych
$serverName = "WIN-8PODA49PE73\\PANDORABASE";
$connectionOptions = array(
    "Database" => "Projekt",
    "Uid" => "sa",
    "PWD" => "zaq1@WSX"
);

// Połączenie z bazą danych
$conn = sqlsrv_connect($serverName, $connectionOptions);
if (!$conn) {
    die("Błąd połączenia z bazą danych: " . print_r(sqlsrv_errors(), true));
}

// Ustawienie ilości filmów na stronę i obliczenie offsetu
$filmsPerPage = 4;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $filmsPerPage;

// Zapytanie SQL do pobrania filmów
$sql = "SELECT nazwa FROM $login ORDER BY nazwa OFFSET $offset ROWS FETCH NEXT $filmsPerPage ROWS ONLY";
$query = sqlsrv_query($conn, $sql);

// Generowanie HTML dla filmów
if ($query) {
    while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
        $filmNazwa = $row["nazwa"];
        echo "<div class='col-md-3 mb-3'>
                <div class='card text-white bg-primary film-card' style='width: 200px; height: 250px;' data-filmnazwa='" . urlencode($filmNazwa) . "'>
                    <div class='card-body d-flex flex-column justify-content-between'>
                        <h5 class='card-title'>$filmNazwa</h5>
                        <div class='d-flex justify-content-around mt-auto'>
                            <a href='watch.php?title=" . urlencode($filmNazwa) . "' class='btn btn-success btn-sm'>Zmień ocenę</a>
                        </div>
                    </div>
                </div>
            </div>";
    }
}

// Zamknięcie połączenia z bazą danych
sqlsrv_close($conn);
?>
