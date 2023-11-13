<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel użytkownika</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }
    body {
      background: #222; /* Dark gray background */
      overflow: hidden;
    }
    ::selection {
      background: rgba(26, 188, 156, 0.3);
    }
    .container {
      max-width: 1200px;
      padding: 0 19px;
      margin: 95px auto;
    }
    .wrapper {
      width: 100%;
      background: #fff;
      border-radius: 5px;
      box-shadow: 0px 4px 10px 1px rgba(0, 0, 0, 0.1);
      transform: scale(1);
    }
    .wrapper .title {
      height: 90px;
      background: black;
      border-radius: 5px 5px 0 0;
      color: #fff; /* White text color */
      font-size: 36px; /* Increased font size */
      font-weight: 600;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .wrapper form {
      padding: 30px 35px 35px 35px;
      background-color: black;
      color: white;
      font-weight: bold;
      display: flex; /* Make the form flex container */
      flex-wrap: wrap; /* Allow items to wrap to the next line */
      justify-content: space-between; /* Distribute items evenly */
    }
    .wrapper form .form-group {
      flex-basis: 48%; /* Set the width for form-group divs */
      margin-bottom: 20px;
    }
    .wrapper form .row.button {
      flex-basis: 100%; /* Full width for buttons */
      display: flex;
      justify-content: center; /* Center the buttons */
    }
    .wrapper form .row.button input {
      margin: 0 10px; /* Add spacing between buttons */
    }
    .wrapper form input[type="submit"] {
      background-color: #3498db;
      color: #fff;
      font-size: 24px;
      border: none;
      padding: 0px 25px;
      border-radius: 10px;
      cursor: pointer;
    }
    .wrapper form input[type="submit"]:hover {
      background: #FFA933;
    }
    .wrapper form .row {
      height: 45px;
      position: relative;
    }
    .wrapper form .row i {
      position: absolute;
      width: 47px;
      height: 100%;
      color: #151515;
      font-size: 18px;
      background: #16a085;
      border: 1px solid #16a085;
      border-radius: 5px 0 0 5px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .wrapper form .pass {
      margin: -8px 0 30px;
    }
    .wrapper form .pass a {
      color: #16a085;
      font-size: 17px;
      text-decoration: none;
    }
    .wrapper form .pass a:hover {
      text-decoration: underline;
    }
    .wrapper form .signup-link {
      text-align: center;
      margin-top: 25px;
      font-size: 17px;
    }
    .wrapper form .signup-link a {
      color: #16a085;
      text-decoration: none;
    }
    .wrapper form .signup-link a:hover {
      text-decoration: underline;
    }
    .form-group {
      width: 48%; /* Adjusted width for form groups */
    }
    .historia {
      float: right; /* Move "Historia oglądania" div to the right */
    }
    h1
    {
      color: white;
    }
  </style>
</head>

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
                        
                        echo "<h3 style='color: white;'>    Top 3 filmy w wybranym gatunku:</h3>";
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
                    <!-- Dodaj przycisk Losuj filmy -->
                    <form method='post' action='losuj_filmy.php' style='display: inline-block;'>
                        <input type='submit' class='btn btn-primary' value='Losuj filmy' style='margin-top: 10%;  width: 200px;'>
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
            
            <div>
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

                $conn = sqlsrv_connect($serverName, $connectionOptions); // Używamy sqlsrv_connect do połączenia z bazą MSSQL

                if (!$conn) {
                    die("Błąd połączenia z bazą danych: " . print_r(sqlsrv_errors(), true));
                }

                // Zapytanie do bazy danych, aby pobrać historię oglądania użytkownika
                $sql = "SELECT nazwa FROM " . $login;
                $query = sqlsrv_query($conn, $sql);

                echo "<h3 style='color: white;'>Historia oglądania:</h3>";

                echo "<ul class='text-center'>";
                if ($query) {
                    while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
                        $searchQuery = urlencode($row["nazwa"]);
                        $googleLink = "https://www.google.com/search?q=$searchQuery";
                        echo "<li class='text-center list-group-item list-group-item-action google-search' style='cursor: pointer;' onclick=\"window.open('$googleLink', '_blank')\">" . $row["nazwa"] . "</li>";
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
