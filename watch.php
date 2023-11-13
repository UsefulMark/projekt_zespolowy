<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Obejrzyj film</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        body {
            background: #222; /* Dark gray background */
            overflow: hidden;
        }
        h1 {
            color: #fff; /* White text color */
        }
        .alert {
            color: black;
        }
        .form-label, .form-control {
            color: #fff;
        }
        .form-control {
            background-color: rgba(255, 255, 255, 0.2);
            color: #3498db;
            border: none;
            width: 100%;
            outline: none;
            padding: 10px;
            transition: all 0.3s ease;
            border-bottom: 2px solid #3498db;
        }
        .form-control:focus {
            border-color: #151515;
            /* color: #fff; */
            box-shadow: inset 2px 2px 2px 2px rgba(26, 188, 20, 0.25);
        }
        .form-control::placeholder {
            color: #151515;
        }
        .btn-primary {
            background-color: #3498db;
            color: #fff;
            font-size: 24px;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            margin-top: 5%;
            display: block;
        }
        .btn-primary:hover {
            background: #FFA933;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <?php
                session_start();
                if (isset($_GET['title'])) {
                    $filmTytul = urldecode($_GET['title']);
                    echo "<h1 class='display-4'>Obejrzyj film: $filmTytul</h1>";

                    // Sprawdzenie, czy użytkownik jest zalogowany
                    if (isset($_SESSION['login'])) {
                        $login = $_SESSION['login'];

                        // Nawiązanie połączenia z bazą danych
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

                        // Sprawdzenie, czy film już istnieje w bazie użytkownika
                        $sqlCheck = "SELECT COUNT(*) AS Count FROM $login WHERE nazwa = ?";
                        $paramsCheck = array($filmTytul);
                        $stmtCheck = sqlsrv_query($conn, $sqlCheck, $paramsCheck);
                        $row = sqlsrv_fetch_array($stmtCheck);

                        if ($row['Count'] > 0) {
                            echo "<p class='alert alert-info'>Film o nazwie '$filmTytul' jest już w twojej tabeli użytkownika.</p>";
                        } else {
                            // Wstawienie nazwy filmu do tabeli użytkownika
                            $sqlInsert = "INSERT INTO $login (nazwa, ocena) VALUES (?, 0)";
                            $paramsInsert = array($filmTytul);

                            $stmtInsert = sqlsrv_query($conn, $sqlInsert, $paramsInsert);

                            if ($stmtInsert) {
                                echo "<p class='alert alert-success'>Nazwa filmu została dodana do twojej tabeli użytkownika.</p>";
                            } else {
                                $errors = sqlsrv_errors();
                                $errorMessages = array();
                                foreach ($errors as $error) {
                                    $errorMessages[] = $error['message'];
                                }
                                $errorMessage = implode(', ', $errorMessages);

                                echo "<p class='alert alert-danger'>Błąd podczas dodawania nazwy filmu do twojej tabeli użytkownika: $errorMessage</p>";
                            }
                        }

                        // Formularz do oceny filmu
                        echo "
                        <form method='post' action='ocena.php'>
                            <input type='hidden' name='filmTytul' value='$filmTytul'> <!-- Dodaj pole ukryte do przekazania tytułu -->
                            <div class='mb-3'>
                                <label for='ocena' class='form-label'>Oceń film (0.0 - 5.0):</label>
                                <input type='number' step='0.5' min='0' max='5' name='ocena' id='ocena' class='form-control' required>
                            </div>
                            <button type='submit' class='btn btn-primary'>Zapisz ocenę</button>
                        </form>
                        ";

                        // Zamknięcie połączenia z bazą danych
                        sqlsrv_close($conn);
                    } else {
                        echo "<p class='alert alert-danger'>Użytkownik nie jest zalogowany.</p>";
                    }
                } else {
                    echo "<p class='alert alert-danger'>Nie wybrano filmu do obejrzenia.</p>";
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
