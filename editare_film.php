<?php
session_start();

require('connection.php');
$con = connect_to_db();

// Verificare conexiune
if (mysqli_connect_errno()) {
    die("Conexiunea la baza de date a eșuat: " . mysqli_connect_error());
}

// Verificare dacă există un ID de film în URL
if (isset($_GET['id'])) {
    $filmID = $_GET['id'];

    // Selectare informații din toate cele trei tabele
    $queryInfo = "SELECT film.*, tipfilm.Tip, difuzarefilm.Data, difuzarefilm.Ora
                  FROM difuzarefilm
                  LEFT JOIN tipfilm ON difuzarefilm.TipFilmID = tipfilm.TipFilmID
                  LEFT JOIN film ON film.FilmID = difuzarefilm.FilmID
                  WHERE difuzarefilm.FilmID = $filmID";
    
    $resultInfo = mysqli_query($con, $queryInfo);

    // Verificare dacă filmul există
    if ($resultInfo && mysqli_num_rows($resultInfo) > 0) {
        $rowInfo = mysqli_fetch_assoc($resultInfo);
    } else {
        echo "Filmul nu există.";
        exit();
    }
} else {
    echo "ID-ul filmului lipsește.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editare Film</title>
    <link rel="stylesheet" href="cinema.css">
</head>
<body>
    <header>
    <h2>Editare Film</h2>
    </header>
    <!-- Formularul pentru editare -->
    <form action="salvare_modificar.php" method="post">
        <input type="hidden" name="filmID" value="<?php echo $rowInfo['FilmID']; ?>">

        <label for="denumire">Denumire Film:</label>
        <input type="text" id="denumire" name="denumire" value="<?php echo $rowInfo['Denumire']; ?>" required>

        <label for="durata">Durata (minute):</label>
        <input type="number" id="durata" name="durata" value="<?php echo $rowInfo['Durata']; ?>" required>

        <label for="numarlocuri">Numar Locuri:</label>
        <input type="number" id="numarlocuri" name="numarlocuri" value="<?php echo $rowInfo['NumarLocuri']; ?>" required>

        <label for="categorie">Categorie:</label>
        <input type="text" id="categorie" name="categorie" value="<?php echo $rowInfo['Categorie']; ?>" required>

        <label for="tip">Tip Film:</label>
        <input type="text" id="tip" name="tip" value="<?php echo $rowInfo['Tip']; ?>" required>

        <label for="data">Data difuzării:</label>
        <input type="date" id="data" name="data" value="<?php echo $rowInfo['Data']; ?>" required>

        <label for="ora">Ora difuzării:</label>
        <input type="time" id="ora" name="ora" value="<?php echo $rowInfo['Ora']; ?>" required>


        <button type="submit">Salvează Modificările</button>
    </form>

</body>
</html>
