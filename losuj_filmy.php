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
                                echo " <a href='watch.php?title=" . urlencode($filmTytul) . "' class='btn btn-secondary btn-sm'>Obejrzyj</a></li>";
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
            </div>
        </div>
    </div>
</body>
</html>
