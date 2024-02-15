<?php
session_start();
require('connection.php');
$con = connect_to_db();

// Verifică dacă există parametrul rezervare_id în URL
if (isset($_GET['rezervare_id'])) {
    $rezervare_id = $_GET['rezervare_id'];

    // Interogare pentru a obține detaliile rezervării
    $queryDetaliiRezervare = "SELECT rezervare.*, film.Denumire, film.Durata, tipfilm.Tip
                              FROM rezervare
                              INNER JOIN difuzarefilm ON rezervare.DifuzareID = difuzarefilm.DifuzareID
                              INNER JOIN film ON difuzarefilm.FilmID = film.FilmID
                              INNER JOIN tipfilm ON difuzarefilm.TipFilmID = tipfilm.TipFilmID
                              WHERE rezervare.RezervareID = ?";
    $stmtDetaliiRezervare = mysqli_prepare($con, $queryDetaliiRezervare);
    mysqli_stmt_bind_param($stmtDetaliiRezervare, "i", $rezervare_id);
    mysqli_stmt_execute($stmtDetaliiRezervare);
    mysqli_stmt_bind_result($stmtDetaliiRezervare, $rezervare_id, $utilizator_id, $difuzare_id, $data, $rand, $scaun, $pret, $denumire_film, $durata, $tip_film);
    mysqli_stmt_fetch($stmtDetaliiRezervare);
    mysqli_stmt_close($stmtDetaliiRezervare);

}

// Închidere conexiune la baza de date
mysqli_close($con);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalii Rezervare</title>
    <style>
        body {
            background-image: url('cinema.jpg');
            background-size: cover;
            background-position: center;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            
        }

        .chenar-detalii {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        .film-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .film-details {
            font-size: 18px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="chenar-detalii">
        <div class="film-title"><?= $denumire_film ?></div>
        <div class="film-details">
            Durata: <?= $durata ?> minute<br>
            Data: <?= $data ?> <br>
            Pret: <?= $pret ?> lei<br>
            Loc: Rand <?= $rand ?>, Scaun <?= $scaun ?><br>
            Data rezervare: <?= $data ?><br>
        </div>
        <p>Ai Terminat?  <a href="login.html">Mergi la pagina de logare</a>.</p>
    </div>
</body>
</html>