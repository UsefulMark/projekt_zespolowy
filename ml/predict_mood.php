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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <style>
        /* Dodaj style CSS tutaj */
        body {
            background-color: #2A3047;
            color: #FFF9CF;
        }
        .result-container {
            margin-top: 20px;
            padding: 20px;
            background-color: #FFF9CF;
            color: #2A3047;
            border-radius: 5px;
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
                        <h2>Przewidziany mood:</h2>
                        <p>Przewidziany nastrój: <?php echo $predicted; ?></p>
                        <a href="../subpages/userpanel.php"><button class="btn btn-success">OK</button></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
