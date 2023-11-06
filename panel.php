    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Panel użytkownika</title>
        <!-- Include Bootstrap CSS -->
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <style>
        body {
                background-color: #151515;
            }
            .container {
                background-color: #AF57FF;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
            .alert {
                margin-top: 10px;
            }
            .list-group {
                margin-top: 20px;
            }

        
    .google-search {
        cursor: pointer; /* Zmiana kształtu kursora na "pointer" po najechaniu */
    }


    </style>
    <body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="text-center">
                    <h1 class="display-4">Panel użytkownika</h1>
                </div>
                <?php
                // Start a PHP session
                session_start();
                
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
                    echo "<div class='alert alert-info'>Numer gatunku: " . $numerGatunku . "</div>";
                    echo "<div class='alert alert-info'>Login w sesji: " . $login . "</div>";
                    
                    // Get movies based on the genre number
                    if (isset($filmy[$numerGatunku])) {
                        $rekomendacje = array_rand(array_slice($filmy[$numerGatunku], 1), 3);
                    } else {
                        $rekomendacje = array();
                    }
                    
                    if (!empty($rekomendacje)) {
                        echo "<h3>Top 3 filmy w wybranym gatunku:</h3>";
                        echo "<div class='list-group'>";
                        foreach ($rekomendacje as $filmIndex) {
                            $filmTytul = $filmy[$numerGatunku][$filmIndex + 1];
                            $googleSearchLink = "https://www.google.com/search?q=" . urlencode($filmTytul);
                            
                            // Dodaj przycisk "Obejrzyj" dla każdego filmu
                            echo "<a href='$googleSearchLink' class='list-group-item list-group-item-action' target='_blank'>$filmTytul
                                <a href='watch.php?title=" . urlencode($filmTytul) . "' class='btn btn-success btn-sm float-right'>Obejrzyj</a></a>";
                        }
                        echo "</div>";
                    } else {
                        echo "<p class='alert alert-info'>Niepoprawny numer gatunku.</p>";
                    }
                    
                    
                    // Logout button
                    echo "<div class='text-center'>
                    <form method='post' action='wyloguj.php' style='display: inline-block;'>
                        <input type='submit' class='btn btn-danger' value='Wyloguj' style='margin-top: 10%; width: 200px;'>
                    </form>              
                    <form method='post' action='ml/mood.html' style='display: inline-block;'>
                        <input src=''; type='submit' class='btn btn-danger' value='Predict' style='margin-top: 10%;  width: 200px;'>
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
            <div >
    <?php
    //   // Sample viewing history
    //   $viewingHistory = array(
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

    $conn = sqlsrv_connect($serverName, $connectionOptions); // Używamy sqlsrv_connect do połączenia z bazą MSSQL

    if (!$conn) {
        die("Błąd połączenia z bazą danych: " . print_r(sqlsrv_errors(), true));
    }

    // Zapytanie do bazy danych, aby pobrać historię oglądania użytkownika
    $sql = "SELECT nazwa FROM " . $login;
    $query = sqlsrv_query($conn, $sql);

    echo "<h3>Historia oglądania:</h3>";
    echo "<ul class='text-center'>";
    if ($query) {
        while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
            $searchQuery = urlencode($row["nazwa"]);
            $googleLink = "https://www.google.com/search?q=$searchQuery";
            echo "<li class='text-center list-group-item list-group-item-action google-search' onclick=\"window.open('$googleLink', '_blank')\">" . $row["nazwa"] . "</li>";
        }
    } else {
        echo "<li class='text-center list-group-item list-group-item-action'>Brak historii oglądania.</li>";
    }




    sqlsrv_close($conn); // Zamykanie połączenia z bazą danych
    ?>



    </div>
        </div>
    </div>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </body>
    </html>
