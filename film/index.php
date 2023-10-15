<?php
// Wczytaj dane filmów z pliku CSV
$lines = file('gatunek.csv'); // Zastąp 'twoj_plik.csv' ścieżką do twojego pliku CSV
$filmy = [];

foreach ($lines as $line) {
    $data = str_getcsv($line);
    $tytul = $data[0];
    $gatunek = $data[1];
    $filmy[$gatunek][] = $tytul;
}

// Sprawdź, czy formularz został wysłany
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $numerGatunku = $_POST["numer_gatunku"];

    // Sprawdź, czy wybrany numer gatunku istnieje
    if (isset($filmy[$numerGatunku])) {
        $rekomendacje = array_rand(array_slice($filmy[$numerGatunku], 1), 3);
    } else {
        $rekomendacje = array(); // Jeśli numer gatunku jest niepoprawny, nie wyświetlaj rekomendacji
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Rekomendacji Filmów</title>
</head>
<body>
    <h1>Witaj w Systemie Rekomendacji Filmów</h1>
    <p>Wybierz numer gatunku filmowego, a my pokażemy Ci trzy losowe rekomendacje filmów z tego gatunku.</p>

    <form method="post" action="">
        <label for="numer_gatunku">Wybierz numer gatunku filmowego:</label>
        <select name="numer_gatunku">
            <option value="1">Horror</option>
            <option value="2">Romans</option>
            <option value="3">Akcja</option>
            <option value="4">Komedie</option>
            <option value="5">Tajemnica</option>
            <option value="7">Science Fiction</option>
        </select>
        <input type="submit" value="Pokaż rekomendacje">
    </form>

    <?php if (!empty($rekomendacje)): ?>
        <h3>Top 3 filmy w wybranym gatunku:</h3>
        <ul>
            <?php foreach ($rekomendacje as $filmIndex): ?>
                <li><?php echo $filmy[$numerGatunku][$filmIndex + 1]; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
        <p>Niepoprawny numer gatunku. Wybierz gatunek z listy.</p>
    <?php endif; ?>
</body>
</html>
