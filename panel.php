<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel użytkownika</title>
</head>
<body>
    <h1>Panel użytkownika</h1>
    <?php
    session_start(); // Rozpocznij sesję (jeśli jeszcze nie rozpoczęta)

    // Sprawdź, czy użytkownik jest zalogowany (sprawdzamy, czy istnieje zmienna sesyjna "login")
    if (isset($_SESSION['login'])) {
        // Wyświetl zmienną sesyjną (login)
        echo "Zalogowany użytkownik: " . $_SESSION['login'];
    } else {
        // Jeśli użytkownik nie jest zalogowany, przekieruj go na stronę logowania lub wyświetl odpowiedni komunikat
        echo "Nie jesteś zalogowany. Proszę zaloguj się.";
        // Możesz dodać przekierowanie na stronę logowania tutaj
    }
    ?>
</body>
</html>
