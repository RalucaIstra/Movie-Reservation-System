<?php
session_start();

require('connection.php');
$con = connect_to_db();

// Verificare conexiune
if (mysqli_connect_errno()) {
    die("Conexiunea la baza de date a eșuat: " . mysqli_connect_error());
}
if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    // Prelucrarea datelor din formular
    $denumire = isset($_POST['denumire']) ? $_POST['denumire'] : '';
    $durata = isset($_POST['durata']) ? $_POST['durata'] : '';
    $numarlocuri = isset($_POST['numarlocuri']) ? $_POST['numarlocuri'] : '';
    $categorie = isset($_POST['categorie']) ? $_POST['categorie'] : '';
    $difuzare = isset($_POST['difuzare']) ? $_POST['difuzare'] : '';
    $ora = isset($_POST['ora']) ? $_POST['ora'] : '';
    $tip = isset($_POST['tip']) ? $_POST['tip'] : '';

// Inserare în tabelul "film"
$queryFilm = "INSERT INTO film (Denumire, Durata, NumarLocuri, Categorie) VALUES ('$denumire', $durata, $numarlocuri, '$categorie')";
mysqli_query($con, $queryFilm);

// Preia id-ul filmului inserat
$idFilm = mysqli_insert_id($con);


// Inserare în tabelul "tip"
$queryTip = "SELECT TipFilmID FROM tipfilm WHERE Tip = '$tip'";
$resultTip = mysqli_query($con, $queryTip);

if ($resultTip && mysqli_num_rows($resultTip) > 0) {
    $rowTip = mysqli_fetch_assoc($resultTip);
    $idTip = $rowTip['TipFilmID'];

    // Inserare în tabelul "datafilm"
    $queryDataFilm = "INSERT INTO difuzarefilm (TipFilmID, FilmID, Data, Ora) VALUES ($idTip, $idFilm, '$difuzare', '$ora')";
    mysqli_query($con, $queryDataFilm);

    // Închide conexiunea la baza de date
    mysqli_close($con);

    // Redirecționează către pagina de succes sau altă pagină
    header("Location: pagina_securizata.php");
    exit();
} else {
    echo "Eroare la găsirea TipFilmID.";
}
}
?>
