<?php
session_start();
ini_set('display_errors', 'Off');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Your Movie Ratings</title>
    <!-- Bootstrap CSS link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            padding: 20px;
        }
    </style>
</head>
<body>

<?php
$login = $_SESSION['login'];

$serverName = "WIN-8PODA49PE73\\PANDORABASE";
$connectionOptions = array(
    "Database" => "Projekt",
    "Uid" => "sa",
    "PWD" => "zaq1@WSX"
);

$conn = sqlsrv_connect($serverName, $connectionOptions);

if (!$conn) {
    die("Błąd połączenia z bazą danych: " . print_r(sqlsrv_errors(), true));
}

$mapowanieGatunkow = array(
    1 => 'Horror',
    2 => 'Romans',
    3 => 'Akcja',
    4 => 'Komedia',
    5 => 'Dreszczowiec',
    6 => 'Science Fiction'
);

$sql = "SELECT film_id, nazwa, ocena, gatunek FROM $login";
$query = sqlsrv_query($conn, $sql);

$ocenyGatunkow = array();
$oglądaneFilmy = array();

if ($query) {
    while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
        $nazwa = $row['nazwa'];
        $ocena = $row['ocena'];
        $gatunekId = $row['gatunek'];

        $gatunek = isset($mapowanieGatunkow[$gatunekId]) ? $mapowanieGatunkow[$gatunekId] : 'Niezdefiniowany';

        if (!isset($ocenyGatunkow[$gatunek])) {
            $ocenyGatunkow[$gatunek] = array('oceny' => array(), 'licznik' => 0, 'sumaOcen' => 0);
        }

        $ocenyGatunkow[$gatunek]['oceny'][] = $ocena;
        $ocenyGatunkow[$gatunek]['licznik']++;
        $ocenyGatunkow[$gatunek]['sumaOcen'] += $ocena;

        $oglądaneFilmy[] = $nazwa;
    }

    echo "<div class='container'>";
    echo "<h1 class='mt-4 mb-4'>Oceny dla poszczególnych gatunków:</h1>";

    $najwyzszaSrednia = 0;
    $najlepszyGatunek = '';

    foreach ($ocenyGatunkow as $gatunek => $data) {
        $sredniaOcena = $data['licznik'] > 0 ? $data['sumaOcen'] / $data['licznik'] : 0;

        if ($sredniaOcena > $najwyzszaSrednia || ($sredniaOcena == $najwyzszaSrednia && $data['licznik'] > $ocenyGatunkow[$najlepszyGatunek]['licznik'])) {
            // Dodaj warunek, aby gatunek został uznany tylko, gdy ma co najmniej 5 ocen
            if ($data['licznik'] >= 5) {
                $najwyzszaSrednia = $sredniaOcena;
                $najlepszyGatunek = $gatunek;
            }
        }

        echo "<div class='card mb-3'>";
        echo "<div class='card-body'>";
        echo "<h5 class='card-title'>Gatunek: $gatunek</h5>";
        echo "<p class='card-text'>Średnia ocena: $sredniaOcena</p>";
        echo "<p class='card-text'>Liczba ocen: {$data['licznik']}</p>";
        echo "<p class='card-text'>Suma ocen: {$data['sumaOcen']}</p>";
        echo "</div>";
        echo "</div>";
    }

    echo "<h2 class='mt-4 mb-4'>Najlepszy gatunek:</h2>";
    echo "<div class='card'>";
    echo "<div class='card-body'>";
    echo "<h5 class='card-title'>Gatunek: $najlepszyGatunek</h5>";
    echo "<p class='card-text'>Najwyższa średnia ocen: $najwyzszaSrednia</p>";
    echo "</div>";
    echo "</div>";

    $wybranyGatunekId = array_search($najlepszyGatunek, $mapowanieGatunkow);
    $plikCSV = 'gatunek.csv';

    if (($handle = fopen($plikCSV, 'r')) !== FALSE) {
        $losoweFilmy = array();

        while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
            $tytul = $data[0];
            $gatunekCSV = (int)$data[1];

            if ($gatunekCSV === $wybranyGatunekId && !in_array($tytul, $oglądaneFilmy)) {
                $losoweFilmy[] = $tytul;
            }
        }

        fclose($handle);

        // Wyświetl 5 losowych filmów danego gatunku, sprawdzając, czy użytkownik ich jeszcze nie oglądał
        echo "<h2 class='mt-4 mb-4'>5 losowych filmów gatunku '$najlepszyGatunek', które użytkownik jeszcze nie oglądał:</h2>";
        echo "<ul class='list-group'>";
        if (count($losoweFilmy) > 0) {
            $losoweFilmy = array_rand(array_flip($losoweFilmy), min(5, count($losoweFilmy)));
            foreach ($losoweFilmy as $losowyFilm) {
                echo "<li class='list-group-item'>$losowyFilm";
                echo "<a href='https://www.google.com/search?q=$losowyFilm' target='_blank' class='btn btn-primary btn-sm ml-2'>Wyszukaj</a>";
                echo "<a href='watch.php?title=" . urlencode($losowyFilm) . "' class='btn btn-success btn-sm ml-2'>Oceń</a>";
                echo "</li>";
            }
        } else {
            echo "<li class='list-group-item'>Brak dostępnych filmów w tym gatunku</li>";
        }
        echo "</ul>";
    } else {
        echo "<p class='mt-4 mb-4'>Błąd odczytu pliku CSV.</p>";
    }

    echo "</div>"; // Closing container div

} else {
    echo "<p class='mt-4 mb-4'>Błąd zapytania do bazy danych.</p>";
}


// Calculate top 3 genres based on average ratings and their counts
uasort($ocenyGatunkow, function($a, $b) {
    // Sort by average rating descending
    $avgComparison = ($b['licznik'] > 0 ? $b['sumaOcen'] / $b['licznik'] : 0) - ($a['licznik'] > 0 ? $a['sumaOcen'] / $a['licznik'] : 0);
    
    // If average ratings are equal, sort by count of ratings ascending
    return ($avgComparison == 0) ? ($a['licznik'] - $b['licznik']) : $avgComparison;
});

$topGatunki = array_slice($ocenyGatunkow, 0, 4, true);

echo "<h2 class='mt-4 mb-4'>Top gatunki:</h2>";

foreach ($topGatunki as $gatunek => $data) {
    // Skip the "Niezdefiniowany" genre
    if ($gatunek == 'Niezdefiniowany' || $gatunek == $najlepszyGatunek) {
        continue;
    }

    $sredniaOcena = $data['licznik'] > 0 ? $data['sumaOcen'] / $data['licznik'] : 0;

    echo "<div class='card mb-3'>";
    echo "<div class='card-body'>";
    echo "<h5 class='card-title'>Gatunek: $gatunek</h5>";
    echo "<p class='card-text'>Średnia ocena: $sredniaOcena</p>";
    echo "<p class='card-text'>Liczba ocen: {$data['licznik']}</p>";
    echo "<p class='card-text'>Suma ocen: {$data['sumaOcen']}</p>";

    // Display two movies from each of the top genres that the user has not watched yet
    $gatunekId = array_search($gatunek, $mapowanieGatunkow);
    $moviesToDisplay = array();

    if (($handle = fopen($plikCSV, 'r')) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
            $tytul = $data[0];
            $gatunekCSV = (int)$data[1];

            if ($gatunekCSV === $gatunekId && !in_array($tytul, $oglądaneFilmy)) {
                $moviesToDisplay[] = $tytul;
            }
        }

        fclose($handle);

        // Display movies only if there are any available
        if (!empty($moviesToDisplay)) {
            echo "<h6 class='mt-3'>Dwa filmy z tego gatunku, które użytkownik jeszcze nie oglądał:</h6>";
            echo "<ul class='list-group'>";
            $moviesToDisplay = array_rand(array_flip($moviesToDisplay), min(2, count($moviesToDisplay)));
            foreach ($moviesToDisplay as $movie) {
                echo "<li class='list-group-item'>$movie";
                echo "<a href='https://www.google.com/search?q=$movie' target='_blank' class='btn btn-primary btn-sm ml-2'>Wyszukaj</a>";
                echo "<a href='watch.php?title=" . urlencode($movie) . "' class='btn btn-success btn-sm ml-2'>Oceń</a>";
                echo "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p class='mt-4 mb-4'>Brak dostępnych filmów w tym gatunku.</p>";
        }
    } else {
        echo "<p class='mt-4 mb-4'>Błąd odczytu pliku CSV.</p>";
    }

    echo "</div>";
    echo "</div>";
}

sqlsrv_close($conn);

?>

<!-- Bootstrap JS and Popper.js scripts (required for Bootstrap) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
