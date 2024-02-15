<?php
session_start();

require('connection.php');
$con = connect_to_db();

// Verificare dacă formularul a fost trimis prin metoda POST
if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    // Prelucrarea datelor din formular
    $Nume = isset($_POST['firstName']) ? $_POST['firstName'] : '';
    $Prenume = isset($_POST['surname']) ? $_POST['surname'] : '';
    $Parola = isset($_POST['newPassword']) ? $_POST['newPassword'] : '';
    $categorie_varsta = isset($_POST['categorie_varsta']) ? $_POST['categorie_varsta'] : '';
    $Telefon = isset($_POST['Phone']) ? $_POST['Phone'] : '';
    $Email = isset($_POST['Email']) ? $_POST['Email'] : '';
    

    // Validarea și sanitizarea datelor pot fi adăugate aici
    
    // Obține varstaid corespunzător categoriei selectate
    $query_varsta = "SELECT varstaid FROM categorievarsta WHERE Varsta = '$categorie_varsta'";
    $result_varsta = mysqli_query($con, $query_varsta);

    if ($result_varsta && mysqli_num_rows($result_varsta) > 0) {
        $row_varsta = mysqli_fetch_assoc($result_varsta);
        $varstaid = $row_varsta['varstaid'];

        // Hash parola înainte de a o salva în baza de date
        $hashed_password = password_hash($Parola, PASSWORD_DEFAULT);

        // Interogare pentru a insera datele în baza de date
        $query = "INSERT INTO utilizator (VarstaID, Nume, Prenume, Parola, Telefon, Email) VALUES ('$varstaid', '$Nume', '$Prenume', '$hashed_password', '$Telefon', '$Email')";

        if (mysqli_query($con, $query)) {
            echo "Cont creat cu succes!";
            // Poți adăuga o redirecționare aici către pagina de login sau unde dorești să trimiti utilizatorul după crearea contului.
        } else {
            echo "Eroare la crearea contului: " . mysqli_error($con);
        }
    } else {
        echo "Eroare la obținerea categoriei de vârstă.";
    }
}
?>

