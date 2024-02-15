<?php
session_start();

// Conectare la baza de date
require('connection.php');
$con = connect_to_db();

if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    // Prelucrarea datelor din formular
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Verificare directă pentru utilizatorul de admin
    if ($username === 'istratiraluca3@gmail.com') {
        // Autentificare reușită pentru utilizatorul de admin
        $_SESSION['username'] = $username;
        header("Location: pagina_securizata.php");
        exit(); // Important pentru a opri execuția scriptului după redirecționare
    }

    // Interogare pentru a obține parola asociată cu utilizatorul dat
    $query = "SELECT * FROM utilizator WHERE email='$username'";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $hashed_password = $row['Parola'];

        // Verificare parolă pentru utilizatorul specific
        if ($username === 'username' && password_verify($password, $hashed_password)) {
            // Autentificare reușită pentru utilizatorul specific
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $row['UtilizatorID'];
            header("Location: pagina_securizata.php");
        } else {
            // Autentificare eșuată pentru ceilalți utilizatori
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $row['UtilizatorID'];
            header("Location: pagina2.php");
        }

    } else {
        // Utilizatorul nu există
        echo "Utilizator sau parolă greșită.";
    }

}
// Închidere conexiune la baza de date
mysqli_close($con);
?>
