<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listă Filme</title>
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

        form {
            background-color: rgba(255, 255, 255, 0.8); /* Fundal semi-transparent */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        input,
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #4caf50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-bottom: 10px;
        }

        button:hover {
            background-color: #45a049;
        }

        .film-box {
            border: 1px solid #ccc;
            padding: 10px;
            margin: 10px;
            width: 300px;
            background-color: rgba(255, 255, 255, 0.8); /* Fundal semi-transparent */
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            text-align: center;
            color: #333;
        }

        .film-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <form id="filter-form" action="" method="get">
        <label for="tip">Alege tipul:</label>
        <select name="tip" id="tip">
            <option value="">Toate</option>
            <option value="2D">2D</option>
            <option value="3D">3D</option>
            <option value="4DX">4DX</option>
            <option value="VIP">VIP</option>
        </select>
        <button type="submit">Filtrează Tip</button>
    </form>

    <form id="special-buttons-form" action="" method="get">
    <label for="submit">Sorteaza dupa:</label>
        <button type="submit" name="special" value="Cel mai rezervat">Cel mai rezervat</button>
        <button type="submit" name="special" value="Cel mai putin rezervat">Cel mai putin rezervat</button>
        <button type="submit" name="special" value="Cele mai multe locuri disponibile">Cele mai multe locuri disponibile</button>
        <button type="submit" name="special" value="Cele mai putine locuri disponibile">Cele mai putine locuri disponibile</button>
    </form>
    
    <?php
// Funcție pentru a obține rezultatele unei interogări
function getQueryResults($con, $query, $message) {
    $result = mysqli_query($con, $query);

    if (!$result) {
        die("Eroare la interogarea '$message': " . mysqli_error($con));
    }

    return $result;
}

// Conectare la baza de date
require('connection.php');
$con = connect_to_db();

// Verificare dacă a fost specificat un tip în URL
$tip = isset($_GET['tip']) ? mysqli_real_escape_string($con, $_GET['tip']) : '';

// Construirea interogării cu câmp variabil pentru tip
$queryFilme = "SELECT film.*, tipfilm.Tip, difuzarefilm.DifuzareID, difuzarefilm.Data, difuzarefilm.Ora,
                (film.NumarLocuri - COUNT(rezervare.RezervareID)) AS LocuriDisponibile
                FROM difuzarefilm
                LEFT JOIN tipfilm ON difuzarefilm.TipFilmID = tipfilm.TipFilmID
                LEFT JOIN film ON film.FilmID = difuzarefilm.FilmID
                LEFT JOIN rezervare ON rezervare.DifuzareID = difuzarefilm.DifuzareID";

if (!empty($tip)) {
    $queryFilme .= " WHERE tipfilm.Tip = '$tip'";
}

$queryFilme .= " GROUP BY difuzarefilm.DifuzareID";

// Verificare dacă s-a apăsat unul dintre butoanele speciale
if (isset($_GET['special'])) {
    $specialType = mysqli_real_escape_string($con, $_GET['special']);

    switch ($specialType) {
        case 'Cel mai rezervat':
            $querySpecial = "SELECT film.*, (SELECT COUNT(rezervare.RezervareID)
                                    FROM difuzarefilm
                                    LEFT JOIN rezervare ON difuzarefilm.DifuzareID = rezervare.DifuzareID
                                    WHERE film.FilmID = difuzarefilm.FilmID) AS NumarRezervari
                            FROM film
                            ORDER BY NumarRezervari DESC";
            break;

        case 'Cel mai putin rezervat':
            $querySpecial = "SELECT film.*, (SELECT COUNT(rezervare.RezervareID)
                                             FROM difuzarefilm
                                            LEFT JOIN rezervare ON difuzarefilm.DifuzareID = rezervare.DifuzareID
                                            WHERE film.FilmID = difuzarefilm.FilmID) AS NumarRezervari
                            FROM film
                            ORDER BY NumarRezervari ASC";
            break;

        case 'Cele mai multe locuri disponibile':
            $querySpecial = "SELECT film.*, difuzarefilm.DifuzareID, difuzarefilm.Data, difuzarefilm.Ora,(film.NumarLocuri - COUNT(rezervare.RezervareID)) AS LocuriDisponibile
                            FROM film
                            LEFT JOIN difuzarefilm ON film.FilmID = difuzarefilm.FilmID
                            LEFT JOIN rezervare ON difuzarefilm.DifuzareID = rezervare.DifuzareID
                            GROUP BY film.FilmID, difuzarefilm.DifuzareID
                            ORDER BY LocuriDisponibile DESC";

            break;

        case 'Cele mai putine locuri disponibile':
            $querySpecial = "SELECT film.*, difuzarefilm.DifuzareID, difuzarefilm.Data, difuzarefilm.Ora,(film.NumarLocuri - COUNT(rezervare.RezervareID)) AS LocuriDisponibile
            FROM film
            LEFT JOIN difuzarefilm ON film.FilmID = difuzarefilm.FilmID
            LEFT JOIN rezervare ON difuzarefilm.DifuzareID = rezervare.DifuzareID
            GROUP BY film.FilmID, difuzarefilm.DifuzareID
            ORDER BY LocuriDisponibile ASC";

            break;

        default:
            // Tratează cazul în care nu se potrivește nicio opțiune
            break;
    }

    $resultSpecial = getQueryResults($con, $querySpecial, $specialType);

    // Afișează rezultatele pentru butonul special apăsat
    while ($row = mysqli_fetch_assoc($resultSpecial)) {
        echo '<div class="film-box">';
        echo '    <div class="film-title">' . $row['Denumire'] . '</div>';
        echo '    Durata: ' . $row['Durata'] . ' minute<br>';
        echo '    Data: ' . $row['Data'] . ' <br>';
        echo '    Gen: ' . $row['Categorie'] . '<br>';
        echo '    Pret: 20.5 lei<br>';
        echo '    Tip: ' . $row['Tip'] . '<br>';
        echo '    Ora: ' . $row['Ora'] . '<br>';
        echo '    Locuri disponibile: ' . $row['LocuriDisponibile'] . '<br>';
        echo '    <a href="proceseaza_rezervare.php?difuzare_id=' . $row['DifuzareID'] . '">Rezerva</a>';
        echo '</div>';
    }
} else {
    // Afișează rezultatele pentru celelalte filme
    $resultFilme = getQueryResults($con, $queryFilme, "Lista filme");

    while ($row = mysqli_fetch_assoc($resultFilme)) {
        echo '<div class="film-box">';
        echo '    <div class="film-title">' . $row['Denumire'] . '</div>';
        echo '    Durata: ' . $row['Durata'] . ' minute<br>';
        echo '    Data: ' . $row['Data'] . ' <br>';
        echo '    Gen: ' . $row['Categorie'] . '<br>';
        echo '    Pret: 20.5 lei<br>';
        echo '    Tip: ' . $row['Tip'] . '<br>';
        echo '    Ora: ' . $row['Ora'] . '<br>';
        echo '    Locuri disponibile: ' . $row['LocuriDisponibile'] . '<br>';
        echo '    <a href="proceseaza_rezervare.php?difuzare_id=' . $row['DifuzareID'] . '">Rezerva</a>';
        echo '</div>';
    }
}

// Închidere conexiune la baza de date
mysqli_close($con);
?>

</body>
</html>
