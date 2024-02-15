<?php
session_start();

require('connection.php');
$con = connect_to_db();

// Verificare conexiune
if (mysqli_connect_errno()) {
    die("Conexiunea la baza de date a eșuat: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prelucrarea datelor din formular
    $filmID = $_POST['filmID'];
    $denumire = $_POST['denumire'];
    $durata = $_POST['durata'];
    $numarlocuri = $_POST['numarlocuri'];
    $categorie = $_POST['categorie'];

    // Actualizare în baza de date
    $queryActualizare = "UPDATE film SET Denumire = '$denumire', Durata = $durata, NumarLocuri = $numarlocuri, Categorie = '$categorie' WHERE FilmID = $filmID";
    mysqli_query($con, $queryActualizare);

  

    // Închide conexiunea la baza de date
    mysqli_close($con);

    // Redirecționează către pagina de succes sau altă pagină
    header("Location: pagina_securizata.php");
    exit();
} else {
    echo "Acces neautorizat.";
    exit();
}
?>
