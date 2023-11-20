<?php
require "vendor/autoload.php";

use Phpml\ModelManager;

// Sprawdź, czy formularz został wysłany
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ilość_snu = $_POST["ilość_snu"];
    $ilość_aktywności = $_POST["ilość_aktywności"];
    $poziom_stresu = $_POST["poziom_stresu"];
    $samopoczucie = $_POST["samopoczucie"];

    // Wczytaj zapisany model
    $modelManager = new ModelManager();
    $classyfier = $modelManager->restoreFromFile('./models/Naive Bayes');

    // Przewiduj nastroju na podstawie danych wejściowych
    $predicted = $classyfier->predict([$ilość_snu, $ilość_aktywności, $poziom_stresu, $samopoczucie]);
}
/*
    // Dodaj warunek, który wyświetli trzy losowe filmy z gatunkiem 1 w przypadku "smutny" nastroju
    if ($predicted === 'smutny') {
        // Wczytaj filmy z gatunkiem 1 z pliku CSV
        $csv = array_map('str_getcsv', file('gatunek.csv'));
        $smutne_filmy = array();

        // Znajdź filmy z gatunkiem 1
        foreach ($csv as $row) {
            if ($row[1] === '1') {
                $smutne_filmy[] = $row[0];
            }
        }

        // Wybierz trzy losowe filmy z gatunkiem 1
        $losowe_filmy = array_rand($smutne_filmy, 3);

        // Wyświetl wybrane filmy
        echo "Trzy losowe filmy z gatunkiem 1:<br>";
        foreach ($losowe_filmy as $index) {
            echo $smutne_filmy[$index] . "<br>";
        }
    }



    if ($predicted === 'szczęśliwy') {
        // Wczytaj filmy z gatunkiem 2 z pliku CSV
        $csv = array_map('str_getcsv', file('gatunek.csv'));
        $smutne_filmy = array();

        // Znajdź filmy z gatunkiem 1
        foreach ($csv as $row) {
            if ($row[1] === '2') {
                $smutne_filmy[] = $row[0];
            }
        }

        // Wybierz trzy losowe filmy z gatunkiem 2
        $losowe_filmy = array_rand($smutne_filmy, 3);

        // Wyświetl wybrane filmy
        echo "Trzy losowe filmy z gatunkiem 2:<br>";
        foreach ($losowe_filmy as $index) {
            echo $smutne_filmy[$index] . "<br>";
        }
    }



    
    if ($predicted === 'neutralny') {
        // Wczytaj filmy z gatunkiem 3 z pliku CSV
        $csv = array_map('str_getcsv', file('gatunek.csv'));
        $smutne_filmy = array();

        // Znajdź filmy z gatunkiem 3
        foreach ($csv as $row) {
            if ($row[1] === '3') {
                $smutne_filmy[] = $row[0];
            }
        }

        // Wybierz trzy losowe filmy z gatunkiem 3
        $losowe_filmy = array_rand($smutne_filmy, 3);

        // Wyświetl wybrane filmy
        echo "Trzy losowe filmy z gatunkiem 3:<br>";
        foreach ($losowe_filmy as $index) {
            echo $smutne_filmy[$index] . "<br>";
        }
    }


*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');
        body {
            background: #222;
            color: #fff;
            font-family: 'Poppins', sans-serif;
            text-align: center; /* Center the content */
        }
        .container {
            background-color: black;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 4px 10px 1px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        h1, h2 {
            color: white;
        }
        .btn-primary, .btn-danger, .btn-success {
            border: none;
            border-radius: 10px;
            padding: 10px 25px;
            font-size: 18px;
            margin: 5px;
        }
        .btn-primary:hover {
            background: #FFA933;
        }
        .result-container {
            display: inline-block; /* Make the container inline-block */
            padding: 10px;
            background-color: #fff;
            color: #222;
            border-radius: 5px;
            margin: 10px 0; /* Add margin above and below the container */
            width: 80%;
        }
        .movie-link {
            display: block; /* Make each movie link a block element */
            margin: 5px 0; /* Add margin to movie links */
        }
    </style>
    <title>Wynik przewidywania nastroju</title>
</head>
<body>
    <div class="container text-center">
        <div class="row">
            <div class="col-12">
                <h1>Wynik przewidywania nastroju</h1>
                <?php if (isset($predicted)): ?>                    
                    <div class="result-container">
                        <h2>Przewidziany nastrój: <?php echo $predicted; ?></h2>
                        <?php
if ($predicted === 'smutny') {
    // Wyświetl trzy losowe filmy z gatunkiem 1 jako linki do wyników wyszukiwania w Google
    echo "Trzy losowe filmy z gatunkiem 1:<br>";
    $csv = array_map('str_getcsv', file('gatunek.csv'));
    $smutne_filmy = array();
    foreach ($csv as $row) {
        ini_set('display_errors', 'Off');
        if ($row[1] === '1') {
            $smutne_filmy[] = $row[0];
        }
    }
    $losowe_filmy = array_rand($smutne_filmy, 3);
    foreach ($losowe_filmy as $index) {
        $film = $smutne_filmy[$index];
        $searchQuery = urlencode($film);
        
        // // Display link to Google search
        // echo "<a href='https://www.google.com/search?q=$searchQuery' target='_blank'>$film</a>";
    
        // // Display button to watch.php
        // echo "<a href='watch.php?title=" . urlencode($film) . "' target='_blank'><button style='margin-left: 30px; margin-top:10px;'>Obejrzyj</button></a><br>";

        // echo "<table class='table table-bordered'>";
        // echo "<tr>";
        // echo "<td class='d-flex justify-content-between'>";
        // echo "<a href='https://www.google.com/search?q=$searchQuery' target='_blank'>$film</a>";
        // echo "<a href='watch.php?title=" . urlencode($film) . "' target='_blank' class='btn btn-primary'>Obejrzyj</a>";
        // echo "</td>";
        // echo "</tr>";
        // echo "</table>";
        echo "<table class='table table-bordered'>";
        echo "<tr>";
        echo "<td>";
        echo "<div class='d-flex justify-content-between'>";
        echo "<div class='text-center flex-grow-1'><a href='https://www.google.com/search?q=$searchQuery' target='_blank'>$film</a></a></div>";
        echo "<a href='watch.php?title=" . urlencode($film) . "' target='_blank' class='btn btn-primary'>Obejrzyj</a>";
        echo "</div>";
        echo "</td>";
        echo "</tr>";
    echo "</table>";
    
        

    }
    
} elseif ($predicted === 'szczęśliwy') {
    // Wyświetl trzy losowe filmy z gatunkiem 2 jako linki do wyników wyszukiwania w Google
    echo "Trzy losowe filmy z gatunkiem 2:<br>";
    $csv = array_map('str_getcsv', file('gatunek.csv'));
    $szczęśliwe_filmy = array();
    foreach ($csv as $row) {
        if ($row[1] === '2') {
            ini_set('display_errors', 'Off');
            $szczęśliwe_filmy[] = $row[0];
        }
    }
    $losowe_filmy = array_rand($szczęśliwe_filmy, 3);
    foreach ($losowe_filmy as $index) {
        $film = $szczęśliwe_filmy[$index];
        $searchQuery = urlencode($film);
        
        // // Display link to Google search
        // echo "<a href='https://www.google.com/search?q=$searchQuery' target='_blank'>$film</a>";
    
        // // Display button to watch.php
        // echo "<a href='watch.php?title=" . urlencode($film) . "' target='_blank'><button>Obejrzyj</button></a><br>";    
        echo "<table class='table table-bordered'>";
        echo "<tr>";
        echo "<td>";
        echo "<div class='d-flex justify-content-between'>";
        echo "<div class='text-center flex-grow-1'><a href='https://www.google.com/search?q=$searchQuery' target='_blank'>$film</a></a></div>";
        echo "<a href='watch.php?title=" . urlencode($film) . "' target='_blank' class='btn btn-primary'>Obejrzyj</a>";
        echo "</div>";
        echo "</td>";
        echo "</tr>";
    echo "</table>";
    
    }
    
} elseif ($predicted === 'neutralny') {
    // Wyświetl trzy losowe filmy z gatunkiem 3 jako linki do wyników wyszukiwania w Google
    echo "Trzy losowe filmy z gatunkiem 3:<br>";
    $csv = array_map('str_getcsv', file('gatunek.csv'));
    $neutralne_filmy = array();
    foreach ($csv as $row) {
        if ($row[1] === '3') {
            ini_set('display_errors', 'Off');
            $neutralne_filmy[] = $row[0];
        }
    }
    $losowe_filmy = array_rand($neutralne_filmy, 3);
    foreach ($losowe_filmy as $index) {
        $film = $neutralne_filmy[$index];
        $searchQuery = urlencode($film);
        
        // // Display link to Google search
        // echo "<a href='https://www.google.com/search?q=$searchQuery' target='_blank'>$film</a>";
    
        // // Display button to watch.php
        // echo "<a href='watch.php?title=" . urlencode($film) . "' target='_blank'><button>Obejrzyj</button></a><br>";   
    
        echo "<table class='table table-bordered'>";
        echo "<tr>";
        echo "<td>";
        echo "<div class='d-flex justify-content-between'>";
        echo "<div class='text-center flex-grow-1'><a href='https://www.google.com/search?q=$searchQuery' target='_blank'>$film</a></a></div>";
        echo "<a href='watch.php?title=" . urlencode($film) . "' target='_blank' class='btn btn-primary'>Obejrzyj</a>";
        echo "</div>";
        echo "</td>";
        echo "</tr>";
    echo "</table>";
    
    }
}
?>

                        <a href="../panel.php"><button class="btn btn-success">OK</button></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>