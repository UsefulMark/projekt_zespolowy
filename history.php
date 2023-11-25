<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historia Oglądania</title>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
        .card {
    width: 200px; /* zachowaj szerokość */
    height: 300px; /* zachowaj wysokość */
    margin: 10px; /* zachowaj marginesy */
}

.card-title {
    font-size: 18px; /* zwiększ rozmiar czcionki dla nazw filmów */
}

.card-body {
    position: relative; /* ustaw pozycję względną dla card-body */
    padding-bottom: 50px; /* dodaj margines na dole, aby zrobić miejsce na przyciski */
}

.btn-sm {
    display: inline-block; /* ustaw wyświetlanie na inline-block, aby przyciski były obok siebie */
    margin: 5px 2px; /* dodaj niewielki margines wokół przycisków */
}
    

/* Dodatkowy styl, aby przyciski były wyśrodkowane na dole karty */
.card-body .btn-container {
    position: absolute;
    bottom: 10px;
    left: 50%;
    transform: translateX(-50%);
}
.film-card {
    cursor: pointer;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animated {
    animation: fadeIn 1s ease;
}
.home-icon {
    position: fixed;
    right: 30px;
    top: 30px;
    font-size: 30px;
    color: white; /* Możesz zmienić kolor według preferencji */
    z-index: 1000; /* Upewnia się, że ikona jest nad innymi elementami */
}


    </style>
</head>
<body>

<div class="container mt-5">
<a href="panel.php" class="home-icon">
    <i class="fas fa-home"></i>
</a>
    <h1 class="text-center">Historia Oglądania</h1>
    <div class="row" id="filmContainer">
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
            $filmCount = 0;
            while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
                $filmNazwa = $row["nazwa"];
                $filmCount++;

                echo "<div class='col-md-3 mb-3'>
                        <div class='card text-white bg-primary film-card' style='width: 200px; height: 250px;' data-filmnazwa='" . urlencode($filmNazwa) . "'>
                            <div class='card-body d-flex flex-column justify-content-between'>
                                <h5 class='card-title'>$filmNazwa</h5>
                                <div class='d-flex justify-content-around mt-auto'>
                                    <a href='watch.php?title=" . urlencode($filmNazwa) . "' class='btn btn-success btn-sm'>Zmień ocenę</a>
                                </div>
                            </div>
                        </div>
                    </div>";

                if ($filmCount >= 8) {
                    break; // Wyświetl tylko pierwsze 8 filmów
                }
            }
        } else {
            echo "<div class='col-md-12'><p class='text-muted'>Brak historii oglądania.</p></div>";
        }

        sqlsrv_close($conn);
        ?>
    </div>
    <?php
    // Jeśli jest więcej niż 8 filmów, wyświetl przycisk "Pokaż więcej"
    if ($filmCount >= 4) {
        echo "<div class='text-center'>
                <button id='showMoreButton' class='btn btn-primary mt-3'>Pokaż więcej</button>
              </div>";
    }
    ?>
</div>
<script>
// document.addEventListener('DOMContentLoaded', (event) => {
//     const filmCards = document.querySelectorAll('.film-card');
//     const showMoreButton = document.getElementById('showMoreButton');
//     let visibleCount = 8;

//     function hideFilmCards() {
//         filmCards.forEach((card, index) => {
//             if (index < visibleCount) {
//                 card.style.display = 'block';
//             } else {
//                 card.style.display = 'none';
//             }
//         });
//     }

//     hideFilmCards();

//     showMoreButton.addEventListener('click', () => {
//         visibleCount += 8;
//         hideFilmCards();
//     });

//     filmCards.forEach(card => {
//         card.addEventListener('click', function() {
//             var filmNazwa = this.getAttribute('data-filmnazwa');
//             window.open('https://www.google.com/search?q=' + filmNazwa, '_blank');
//         });
//     });
// });
document.addEventListener('DOMContentLoaded', (event) => {
    const showMoreButton = document.getElementById('showMoreButton');
    let currentPage = 1;

    showMoreButton.addEventListener('click', () => {
        currentPage++;
        loadMoreFilms(currentPage);
    });

    function loadMoreFilms(page) {
    $.ajax({
        url: 'load_more_films.php',
        type: 'GET',
        data: {page: page},
        success: function(response) {
            if (response.trim() !== '') {
                // Tworzenie elementu HTML z odpowiedzi i dodawanie klasy animacji
                var newFilms = $(response).addClass('animated');
                $('#filmContainer').append(newFilms);
            } else {
                $('#showMoreButton').hide();
            }
        }
    });
}

});
</script>


<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.8/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
