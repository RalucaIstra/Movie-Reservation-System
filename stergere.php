<?php
session_start();

require('connection.php');
$con = connect_to_db();

// Verificare conexiune
if (mysqli_connect_errno()) {
    die("Conexiunea la baza de date a eșuat: " . mysqli_connect_error());
}

if (!isset($_SESSION['username']) || !isset($_SESSION['special_password'])) {
    header("Location: login.php"); // Redirecționează către pagina de login dacă utilizatorul nu este autentificat sau nu are privilegii de administrator
    exit();
}

// Verificare dacă există un ID de film în URL
if (isset($_GET['id'])) {
    $filmID = $_GET['id'];

    

    // Verificare dacă există rezervări pentru filmul respectiv
    $queryVerificareRezervari = "SELECT COUNT(*) AS NumarRezervari
                                FROM rezervare
                                LEFT JOIN difuzarefilm ON difuzarefilm.DifuzareID = rezervare.DifuzareID
                                WHERE difuzarefilm.FilmID = $filmID";
    $resultVerificareRezervari = mysqli_query($con, $queryVerificareRezervari);
    $rowVerificareRezervari = mysqli_fetch_assoc($resultVerificareRezervari);

    if ($rowVerificareRezervari['NumarRezervari'] == 0) {
        // Nu există rezervări, putem șterge filmul și difuzările asociate
        $queryStergereDifuzari = "DELETE FROM difuzarefilm WHERE FilmID = $filmID";
        $queryStergereFilm = "DELETE FROM film WHERE FilmID = $filmID";

        mysqli_query($con, $queryStergereDifuzari);
        mysqli_query($con, $queryStergereFilm);

        // Închide conexiunea la baza de date
        mysqli_close($con);

        // Redirecționează către pagina de succes sau altă pagină
        header("Location: pagina_securizata.php");
        exit();
    } else {
        // Există rezervări, nu putem șterge filmul
        echo "Nu poți șterge filmul pentru că există rezervări asociate.";
        exit();
    }
} else {
    echo "ID-ul filmului lipsește.";
    exit();
}
?>
