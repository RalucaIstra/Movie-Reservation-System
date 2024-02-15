<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resetare Parolă</title>
    <link rel="stylesheet" href="cinema.css">
</head>
<body>

<?php
// Includeți fișierul de conexiune la baza de date
require('connection.php');
$con = connect_to_db();

// Verificați dacă a fost trimis formularul
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Preveniți SQL injection
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $newPassword = mysqli_real_escape_string($con, $_POST['new_password']);

    // Actualizați parola în baza de date pentru utilizatorul cu adresa de email specificată
    $updateQuery = "UPDATE utilizator SET Parola = '$newPassword' WHERE Email = '$email'";
    $result = mysqli_query($con, $updateQuery);

    if ($result) {
        echo '<p>Parola a fost actualizată cu succes!</p>';
    } else {
        echo '<p>Eroare la actualizarea parolei: ' . mysqli_error($con) . '</p>';
    }

    // Închideți conexiunea la baza de date
    mysqli_close($con);
} else {
    // Dacă nu s-a trimis formularul, afișați formularul de resetare a parolei
    ?>
    <header>
    <h1>Resetare Parolă</h1>
    </header>
    <form method="post" action="">
        <label for="email">Adresă de Email:</label>
        <input type="email" name="email" required>
        <br>
        <label for="new_password">Noua Parolă:</label>
        <input type="password" name="new_password" required>
        <br>
        <button type="submit">Resetare Parolă</button>
    </form>
    <?php
}
?>

</body>
</html>
