<?php
session_start();

require('connection.php');
$con = connect_to_db();

// Verificare dacă utilizatorul este autentificat și are parola specială
if (!isset($_SESSION['username']) || !isset($_SESSION['special_password'])) {
    header("Location: login.php");
    exit();
}

// Interogare pentru a obține utilizatorul cu cele mai multe rezervări
$query = "SELECT u.*, COUNT(r.RezervareID) AS NumarRezervari
          FROM utilizator u
          JOIN rezervare r ON u.UtilizatorID = r.UtilizatorID
          GROUP BY u.UtilizatorID
          HAVING NumarRezervari = (
              SELECT MAX(numar_rezervari)
              FROM (
                  SELECT UtilizatorID, COUNT(RezervareID) AS numar_rezervari
                  FROM rezervare
                  GROUP BY UtilizatorID
              ) AS subquery
          )";
$result = mysqli_query($con, $query);

// Verificați dacă interogarea a avut succes
if (!$result) {
    die("Eroare la interogare: " . mysqli_error($con));
}

// Afișați rezultatele pentru utilizatorul cu cele mai multe rezervări
while ($row = mysqli_fetch_assoc($result)) {
    echo 'Utilizator: ' . $row['Email'] . ', Rezervări: ' . $row['NumarRezervari'] . '<br>';
}

// Interogare pentru a obține utilizatorul cu cele mai multe rezervări pentru un film specificat
$tip = isset($_GET['tip']) ? mysqli_real_escape_string($con, $_GET['tip']) : '';

if (!empty($tip)) {
    $query1 = "SELECT u.*, f.Denumire AS NumeFilm, COUNT(r.RezervareID) AS NumarRezervari
               FROM utilizator u
               JOIN rezervare r ON u.UtilizatorID = r.UtilizatorID
               JOIN difuzarefilm df ON r.DifuzareID = df.DifuzareID
               JOIN film f ON df.FilmID = f.FilmID
               WHERE f.Denumire = '$tip'
               GROUP BY u.UtilizatorID
               HAVING NumarRezervari >= ALL (
                   SELECT COUNT(r2.RezervareID)
                   FROM rezervare r2
                   JOIN difuzarefilm df2 ON r2.DifuzareID = df2.DifuzareID
                   JOIN film f2 ON df2.FilmID = f2.FilmID
                   WHERE f2.Denumire = '$tip'
                   GROUP BY r2.UtilizatorID
               )";
    
    $result1 = mysqli_query($con, $query1);

    // Verificați dacă interogarea a avut succes
    if (!$result1) {
        die("Eroare la interogare: " . mysqli_error($con));
    }

    // Afișați rezultatele pentru utilizatorul cu cele mai multe rezervări pentru filmul specificat
    while ($row1 = mysqli_fetch_assoc($result1)) {
        echo 'Utilizator: ' . $row1['Email'] . ', Rezervări pentru ' . $row1['NumeFilm'] . ': ' . $row1['NumarRezervari'] . '<br>';
    }

    // Interogare pentru a obține filmul cu cea mai mare sumă a prețurilor rezervărilor
$query2 = "SELECT f.Denumire AS NumeFilm, SUM(rf.pret) AS TotalIncasare
FROM film f
JOIN difuzarefilm df ON f.FilmID = df.FilmID
JOIN rezervare rf ON df.DifuzareID = rf.DifuzareID
GROUP BY f.FilmID
HAVING TotalIncasare IN (
    SELECT MAX(TotalIncasare)
    FROM (
        SELECT f.FilmID, SUM(rf.pret) AS TotalIncasare
        FROM film f
        JOIN difuzarefilm df ON f.FilmID = df.FilmID
        JOIN rezervare rf ON df.DifuzareID = rf.DifuzareID
        GROUP BY f.FilmID
    ) AS subquery
)";
$result2 = mysqli_query($con, $query2);

// Verificați dacă interogarea a avut succes
if (!$result2) {
die("Eroare la interogare: " . mysqli_error($con));
}

// Afișați rezultatele
while ($row = mysqli_fetch_assoc($result2)) {
echo 'Film: ' . $row['NumeFilm'] . ', Total Încasare: ' . $row['TotalIncasare'] . '<br>';
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina Admin</title>
    <link rel="stylesheet" href="cinema.css">
</head>
<style>
body {
    font-family: Arial, sans-serif;
    margin: 150px;
    padding: 150px;
    align-items: center;
    justify-content: center;
    background-color: #f4f4f4;
}
button {
    padding: 10px 15px;
    font-size: 14px;
    margin: 50px;
}
</style>
<body>

    <button onclick="window.location.href='inserare_film.html'">Inserare Film</button>
    <button onclick="window.location.href='actualizare_film.php'">Actualizare Film</button>
    <button onclick="window.location.href='stergere_film.php'">Ștergere Film</button>
    <button type="submit" name="special" value="Utilizatorul cu cele mai multe rezervari">Utilizatorul cu cele mai multe rezervari</button>
    <button type="submit" name="special" value="Filmul cu cele mai multe inacasari">Filmul cu cele mai multe inacasari</button>
    <form id="film" action="" method="get">
        <label for="tip">Alege pentru ce film:</label>
        <select name="tip" id="tip">
            <option value="">Toate</option>
            <option value="Singur Acasa 1">Singur Acasa 1</option>
            <option value="Wonka">Wonka</option>
            <option value="Alba ca Zapada">Alba ca Zapada</option>
        </select>
        <button type="submit">Filtrează Tip</button>
    </form>
</body>
</html>
