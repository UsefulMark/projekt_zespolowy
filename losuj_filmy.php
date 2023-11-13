<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Losuj Filmy</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: #222;
            color: white;
            padding: 50px;
        }
        h1 {
            color: white;
        }
        .list-group-item {
            background: #333;
            color: white;
            margin-top: 10px;
            position: relative;
        }
        .btn-group {
            display: flex;
        }
        .search-btn {
            margin-left: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <h1>Losuj Filmy</h1>
                <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <label for="gatunek">Wybierz gatunek:</label>
                    <select id="gatunek" name="gatunek">
                        <option value="999" hidden></option>
                        <option value="1">Horror</option>
                        <option value="2">Romans</option>
                        <option value="3">Akcja</option>
                        <option value="4">Komedia</option>
                        <option value="5">Dreszczowiec</option>
                        <option value="7">Sci-Fi</option>
                        <!-- Dodaj tu pozostałe opcje z innymi gatunkami -->
                    </select>
                    <input type="submit" class="btn btn-primary" value="Losuj filmy">
                </form>
                <?php
                    if(isset($_GET['gatunek'])) {
                        $gatunek = $_GET['gatunek'];
                        $lines = file('gatunek.csv');
                        $filmy = [];

                        foreach ($lines as $line) {
                            $data = str_getcsv($line);
                            $tytul = $data[0];
                            $gatunekFilmu = $data[1];
                            if ($gatunekFilmu == $gatunek) {
                                $filmy[] = $tytul;
                            }
                        }

                        if (!empty($filmy)) {
                            $losowaneFilmy = array_rand($filmy, min(5, count($filmy)));
                            echo "<ul class='list-group'>";
                            
                            foreach ($losowaneFilmy as $index) {
                                $filmTytul = $filmy[$index];
                                echo "<li class='list-group-item'>$filmTytul";
                                echo "<div class='btn-group'>";
                                echo " <button class='btn btn-secondary btn-sm' onclick='watchFilm(\"$filmTytul\")'>Obejrzyj</button>";
                                echo " <button class='btn btn-info btn-sm search-btn' onclick='searchOnGoogle(\"$filmTytul\")'>Wyszukaj</button>";
                                echo "</div></li>";
                            }

                            echo "</ul>";
                            
                            // Display selected genre
                            echo "<p class='text-white mt-3'>Filmy z gatunku: ";
                            switch ($gatunek) {
                                case 1:
                                    echo "Horror";
                                    break;
                                case 2:
                                    echo "Romans";
                                    break;
                                case 3:
                                    echo "Akcja";
                                    break;
                                case 4:
                                    echo "Komedia";
                                    break;
                                case 5:
                                    echo "Dreszczowiec";
                                    break;
                                case 7:
                                    echo "Sci-Fi";
                                    break;
                                // Add cases for other genres if needed
                                default:
                                    echo "Inny";
                            }
                            echo "</p>";
                        } else {
                            echo "<p>Brak filmów w wybranym gatunku.</p>";
                        }
                    } else {
                        echo "<p>Nie wybrano gatunku filmu.</p>";
                    }
                ?>

                <h2 class="mt-4">Koło Fortuny</h2>
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <input type="submit" class="btn btn-warning mt-2" value="Losuj film z Koła Fortuny" name="losujKoloFortuny">
                </form>

                <?php
                    if (isset($_POST['losujKoloFortuny'])) {
                        $linesKoloFortuny = file('gatunek.csv');
                        $filmyKoloFortuny = [];

                        foreach ($linesKoloFortuny as $line) {
                            $dataKoloFortuny = str_getcsv($line);
                            $tytulKoloFortuny = $dataKoloFortuny[0];
                            $filmyKoloFortuny[] = $tytulKoloFortuny;
                        }

                        if (!empty($filmyKoloFortuny)) {
                            $losowanyFilmKoloFortuny = $filmyKoloFortuny[array_rand($filmyKoloFortuny)];
                            echo "<p class='text-white mt-2'>Wylosowany film z Koła Fortuny: $losowanyFilmKoloFortuny</p>";
                            echo "<div class='btn-group'>";
                            echo "<a href='watch.php?title=" . urlencode($losowanyFilmKoloFortuny) . "' class='btn btn-secondary btn-sm'>Obejrzyj</a>";
                            echo " <button class='btn btn-info btn-sm search-btn' onclick='searchOnGoogle(\"$losowanyFilmKoloFortuny\")'>Wyszukaj</button>";
                            echo "</div>";
                        } else {
                            echo "<p class='text-white mt-2'>Brak filmów na Kole Fortuny.</p>";
                        }
                    }
                ?>
            </div>
        </div>
    </div>
    
    <script>
        function searchOnGoogle(title) {
            const searchUrl = `https://www.google.com/search?q=${encodeURIComponent(title)}`;
            window.open(searchUrl, '_blank');
        }

        function watchFilm(title) {
            window.location.href = `watch.php?title=${encodeURIComponent(title)}`;
        }
    </script>
</body>
</html>
