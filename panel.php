<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Panel</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');
        body {
            background: url('back3.png') no-repeat center center fixed; /* Use the generated background image */
            background-size: cover;
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            color: #d2b48c; /* Gold text color */
        }
        .container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
        }
        .card {
            background-color: rgba(255, 255, 255, 0.85); /* Semi-transparent white */
            border-radius: 10px;
        }
        .card-header, .card-footer {
            background-color: rgba(0, 0, 0, 0.85); /* Dark background for headers and footers */
            color: #d2b48c;
        }
        .btn {
            background-color: #d2b48c; /* Gold background for buttons */
            border: none;
            color: #333;
            margin: 5px;
        }
        .btn:hover {
            background-color: #a67c00; /* Darker gold on hover */
            color: #fff;
        }
        .list-group-item {
            background-color: rgba(0, 0, 0, 0.85); /* Dark background for list items */
            color: #d2b48c;
            border: none;
        }
        .list-group-item:hover {
            background-color: rgba(68, 68, 68, 0.85); /* Lighten on hover */
        }
        .title {
            color: #d2b48c;
            text-align: center;
            padding-bottom: 10px;
        }
        .panel{
          background-color: rgba(255, 255, 255, 0.5); /* rgba(255, 255, 255, opacity) - Tutaj można dostosować kolor tła i poziom przezroczystości */
          width:50%;
       
            
        }
        .historia{
          background-color: rgba(255, 255, 255, 0.5); /* rgba(255, 255, 255, opacity) - Tutaj można dostosować kolor tła i poziom przezroczystości */
          width: 50%;
            
        }

        a, .btn {
    transition: color 0.3s ease, background-color 0.3s ease; /* Dodaje płynne przejście dla linków i przycisków */
}

a:hover, .btn:hover {
    color: #fff; /* Biały tekst na hover */
    background-color: #a67c00; /* Ciemniejsze złoto na hover */
    text-decoration: none; /* Usuwa podkreślenie z linków na hover */
}

.list-group-item:hover {
    background-color: rgba(68, 68, 68, 0.85); /* Jasniejszy kolor na hover dla elementów listy */
    color: #fff; /* Biały tekst na hover */
}

.btn {
    color: #fff; /* Biały tekst na przyciskach */
    /* ... istniejące style ... */
}

/* Zwiększenie nieprzezroczystości paneli dla lepszej czytelności tekstu */
.panel, .historia {
    background-color: rgba(255, 255, 255, 0.9);
    /* ... istniejące style ... */
}
    </style>
</head>

<?php session_start()?>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 panel">
                <div class="text-center">
                    <h1 class="display-4" >Panel użytkownika</h1>
                </div>
                <?php
                        ini_set('display_errors', 'Off');
                // Start a PHP session
                
                // Your PHP code here

                // Check if the user is logged in
                if (isset($_SESSION['login'])) {
                    $login = $_SESSION['login'];

                    // Connect to the MSSQL database
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

                    // Check if the user is logged in from the database
                    $sql_check_logged = "SELECT logged FROM users WHERE login = ?";
                    $params_check_logged = array($login);
                    $stmt_check_logged = sqlsrv_query($conn, $sql_check_logged, $params_check_logged);

                    if ($stmt_check_logged === false) {
                        die(print_r(sqlsrv_errors(), true));
                    }

                    $row_check_logged = sqlsrv_fetch_array($stmt_check_logged, SQLSRV_FETCH_ASSOC);

                    if ($row_check_logged['logged'] === 'true') {
                        // User is logged in, continue displaying the panel
                        // ...
                    } else {
                        // If the "logged" field is set to "false" or doesn't exist, redirect to the logout page
                        echo "<div class='alert alert-danger'>Nie jesteś zalogowany. Proszę zaloguj się ponownie.</div>";
                        header("Location: wyloguj.php");
                        exit();
                    }

                    // Load movie data from a CSV file
                    $lines = file('gatunek.csv');
                    $filmy = [];

                    foreach ($lines as $line) {
                        $data = str_getcsv($line);
                        $tytul = $data[0];
                        $gatunek = $data[1];
                        $filmy[$gatunek][] = $tytul;
                    }

                    // Get the user's genre number from the database
                    $sql_get_gatunku = "SELECT numer_gatunku FROM users WHERE login = ?";
                    $params_get_gatunku = array($login);
                    $stmt_get_gatunku = sqlsrv_query($conn, $sql_get_gatunku, $params_get_gatunku);

                    if ($stmt_get_gatunku === false) {
                        die(print_r(sqlsrv_errors(), true));
                    }

                    $row_get_gatunku = sqlsrv_fetch_array($stmt_get_gatunku, SQLSRV_FETCH_ASSOC);
                    $numerGatunku = $row_get_gatunku['numer_gatunku'];

                    // Display genre number
                    //echo "<div class='alert alert-info'>Numer gatunku: " . $numerGatunku . "</div>";
                    //echo "<div class='alert alert-info'>Login w sesji: " . $login . "</div>";

                    // Get movies based on the genre number
                    if (isset($filmy[$numerGatunku])) {
                        $rekomendacje = array_rand(array_slice($filmy[$numerGatunku], 1), 3);
                    } else {
                        $rekomendacje = array();
                    }

                    if (!empty($rekomendacje)) {
                        
                        echo "<h3 style='color: black;'>    Top 3 filmy w wybranym gatunku:</h3>";
                        echo "<div class='list-group'>";
                        foreach ($rekomendacje as $filmIndex) {
                            $filmTytul = $filmy[$numerGatunku][$filmIndex + 1];
                            $googleSearchLink = "https://www.google.com/search?q=" . urlencode($filmTytul);

                            // Dodaj przycisk "Obejrzyj" dla każdego filmu
                            echo "<br><div class='list-group-item list-group-item-action' style='border-radius: 5px;'>
                            <a href='$googleSearchLink' target='_blank'>$filmTytul</a>
                            <a href='watch.php?title=" . urlencode($filmTytul) . "' class='btn btn-success btn-sm float-right' style='  border-radius: 5px;'>Obejrzyj</a>
                          </div>";
                        }
                        echo "</div>";
                    } else {
                        echo "<p class='alert alert-info'>Niepoprawny numer gatunku.</p>";
                    }

                    // Logout button
                    echo "<div class='text-center'>
                    <!-- Dodaj przycisk Losuj filmy -->
                    <form method='post' action='losuj_filmy.php' style='display: inline-block;'>
                        <input type='submit' class='btn btn-primary' value='Losuj filmy' style='margin-top: 10%;  width: 200px;'>
                    </form>
                    <form method='post' action='ml/mood.html' style='display: inline-block;'>
                        <input src=''; type='submit' class='btn btn-primary' value='Predict' style='margin-top: 10%;  width: 200px;'>
                    </form><br>
                    <form method='post' action='rating.php' style='display: inline-block;'>
                    <input src=''; type='submit' class='btn btn-primary' value='Rating' style='margin-top: 10%;  width: 200px;'>
                </form><br>
                    <form method='post' action='wyloguj.php' style='display: inline-block;'>
                        <input type='submit' class='btn btn-danger' value='Wyloguj' style='margin-top: 10%; width: 200px;'>
                    </form>              
                </div>";

                    // Close the database connection
                    sqlsrv_close($conn);
                } else {
                    echo "<p class='alert alert-warning'>Nie jesteś zalogowany. Proszę zaloguj się.</p>";
                    // You can add a redirection to the login page here
                }

                ?>
            </div>
            
            <div class="historia">
                <?php
                // Sample viewing history
                // $viewingHistory = array(
                //     "Film 1",
                //     "Film 2",
                //     "Film 3",
                //     "Film 4",
                //     "Film 5",
                // );

                // echo "<h3>Historia oglądania:</h3>";
                // echo "<ul class=' text-center '>";
                // foreach ($viewingHistory as $item) {
                //     echo "<li class='text-center list-group-item list-group-item-action'>" . $item . '</li>';
                // }
                // echo '</ul>';

                   // Pobierz login użytkownika z sesji
    $login = $_SESSION['login'];

    // Połączenie z bazą danych MSSQL
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

    // Zapytanie do bazy danych, aby pobrać historię oglądania użytkownika
    $sql = "SELECT TOP 8 nazwa FROM " . $login ;
    $query = sqlsrv_query($conn, $sql);

    echo "<center><h3 >Historia oglądania:</h3></center>";

    echo "<ul class='text-center list-group'>";
    if ($query) {
        while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
            $searchQuery = urlencode($row["nazwa"]);
            $googleLink = "https://www.google.com/search?q=$searchQuery";
            echo "<li class='list-group-item list-group-item-action' style='cursor: pointer;' onclick=\"window.open('$googleLink', '_blank')\">" . $row["nazwa"] . "</li>";
        }

        // Dodaj link "Show More" do strony history.php
        echo "<li class='list-group-item list-group-item-action'><a href='history.php'>Show More</a></li>";
    } else {
        echo "<li class='list-group-item list-group-item-action'>Brak historii oglądania.</li>";
    }

                sqlsrv_close($conn); // Zamykanie połączenia z bazą danych
                ?>
                
            </div>
        </div>
    </div>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
