<?php
session_start();

require('connection.php');
$con = connect_to_db();

// Verificare conexiune
if (mysqli_connect_errno()) {
    die("Conexiunea la baza de date a eșuat: " . mysqli_connect_error());
}

// Selectare toate filmele cu informații complete
$queryFilme = "SELECT film.*, tipfilm.Tip, difuzarefilm.Data, difuzarefilm.Ora 
              FROM difuzarefilm
              LEFT JOIN tipfilm ON difuzarefilm.TipFilmID = tipfilm.TipFilmID
              LEFT JOIN film ON film.FilmID = difuzarefilm.FilmID";
$resultFilme = mysqli_query($con, $queryFilme);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stergere Filme</title>
    <link rel="stylesheet" href="cinema.css">
    
</head>
<style>
    table {
        width: 100%;
    }
</style>
<body>
    <header>
    <h1>Stergere Filme</h1>
    </header>
    <?php
    // Verificare dacă există filme
    if ($resultFilme && mysqli_num_rows($resultFilme) > 0) {
        echo "<table border='1'>
              <tr>
                <th>ID</th>
                <th>Denumire</th>
                <th>Durata</th>
                <th>Numar Locuri</th>
                <th>Categorie</th>
                <th>Tip</th>
                <th>Data</th>
                <th>Ora</th>
                <th>Acțiuni</th>
              </tr>";

        while ($rowFilm = mysqli_fetch_assoc($resultFilme)) {
            echo "<tr>
                  <td>{$rowFilm['FilmID']}</td>
                  <td>{$rowFilm['Denumire']}</td>
                  <td>{$rowFilm['Durata']}</td>
                  <td>{$rowFilm['NumarLocuri']}</td>
                  <td>{$rowFilm['Categorie']}</td>
                  <td>{$rowFilm['Tip']}</td>
                  <td>{$rowFilm['Data']}</td>
                  <td>{$rowFilm['Ora']}</td>
                  <td><a href='stergere.php?id={$rowFilm['FilmID']}' onclick='return confirm(\"Sigur doriți să ștergeți filmul?\")'>Ștergere</a>
                </tr>";
        }

        echo "</table>";
    } else {
        echo "Nu există filme de afișat.";
    }

    // Închide conexiunea la baza de date
    mysqli_close($con);
    ?>

</body>
</html>
