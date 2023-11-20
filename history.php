<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historia Oglądania</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center">Historia Oglądania</h1>
    <div class="row">
        <?php
        session_start();
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

        $sql = "SELECT nazwa FROM $login";
        $query = sqlsrv_query($conn, $sql);

        if ($query) {
            while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
                $filmNazwa = $row["nazwa"];

                echo "<div class='col-md-3 mb-3'>
                        <div class='card text-white bg-primary' style='width: 200px; height: 250px;'>
                            <div class='card-body'>
                                <h5 class='card-title'>$filmNazwa</h5>
                                <a href='https://www.google.com/search?q=$filmNazwa' target='_blank' class='btn btn-light btn-sm mt-2'>Szukaj na Google</a>
                                <a href='watch.php?title=" . urlencode($filmNazwa) . "' class='btn btn-success btn-sm mt-2'>Zmień ocenę</a>
                            </div>
                        </div>
                      </div>";
            }
        } else {
            echo "<div class='col-md-12'><p class='text-muted'>Brak historii oglądania.</p></div>";
        }

        sqlsrv_close($conn);
        ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.8/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
