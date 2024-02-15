<?php
require('connection.php');
$con = connect_to_db();

if (mysqli_connect_errno()) {
    die("Conexiunea la baza de date a eșuat: " . mysqli_connect_error());
}

$queryIstoric = "SELECT * FROM film WHERE TipActiune IS NOT NULL ORDER BY DataOra DESC";
$resultIstoric = mysqli_query($con, $queryIstoric);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Istoric Acțiuni</title>
    <link rel="stylesheet" href="cinema.css">
</head>
<body>
    <header>
        <h1>Istoric Acțiuni</h1>
    </header>
    <?php
    if ($resultIstoric && mysqli_num_rows($resultIstoric) > 0) {
        echo "<table border='1'>
              <tr>
                <th>ID</th>
                <th>Tip Acțiune</th>
                <th>Data și Ora</th>
              </tr>";

        while ($rowIstoric = mysqli_fetch_assoc($resultIstoric)) {
            echo "<tr>
                  <td>{$rowIstoric['FilmID']}</td>
                  <td>{$rowIstoric['TipActiune']}</td>
                  <td>{$rowIstoric['DataOra']}</td>
                </tr>";
        }

        echo "</table>";
    } else {
        echo "Nu există acțiuni de afișat.";
    }

    mysqli_close($con);
    ?>

</body>
</html>
