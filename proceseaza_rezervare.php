<?php
session_start();

// Conectare la baza de date
require('connection.php');
$con = connect_to_db();

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $difuzare_id = isset($_POST['difuzare_id']) ? $_POST['difuzare_id'] : null;
    $rand = isset($_POST['rand']) ? $_POST['rand'] : null;
    $scaun = isset($_POST['scaun']) ? $_POST['scaun'] : null;

    // Validare și procesare date, aici poți adăuga și validări suplimentare
    if ($email && $difuzare_id && $rand && $scaun) {
        // Cautare user_id in baza de date
        $queryCautareUserID = "SELECT u.UtilizatorID, c.Varsta FROM utilizator u
                            INNER JOIN categorievarsta c ON u.VarstaID = c.VarstaID
                             WHERE u.email = ?";
        $stmtCautareUserID = mysqli_prepare($con, $queryCautareUserID);
        mysqli_stmt_bind_param($stmtCautareUserID, "s", $email);
        mysqli_stmt_execute($stmtCautareUserID);
        mysqli_stmt_bind_result($stmtCautareUserID, $user_id, $categorie_varsta);
        mysqli_stmt_fetch($stmtCautareUserID);
        mysqli_stmt_close($stmtCautareUserID);
        if ($user_id) {

            // Determinare pret in functie de categorie varsta
            switch ($categorie_varsta) {
                case 'Child (0-3':
                    $pret = 15.0; // Pret pentru copii
                    break;
                case 'Student':
                    $pret = 20.5; // Pret pentru adulti
                    break;
                case 'Adult':
                    $pret = 25.5; // Pret pentru adulti
                    break;
                default:
                    $pret = 20.5; // Presupunere preț implicit
            }
            // Adaugare rezervare in baza de date cu instrucțiuni pregătite
            $data = date("Y-m-d"); // Data curentă
            //$pret = isset($_POST['pret']) ? $_POST['pret'] : null;

            $queryAdaugareRezervare = "INSERT INTO rezervare (UtilizatorID, DifuzareID, Data, NumarRand, NumarScaun, Pret) VALUES (?, ?, ?, ?, ?, ?)";
            $stmtAdaugareRezervare = mysqli_prepare($con, $queryAdaugareRezervare);

            // Legare parametri și executare
            mysqli_stmt_bind_param($stmtAdaugareRezervare, "iissid", $user_id, $difuzare_id, $data, $rand, $scaun, $pret);
            $resultAdaugareRezervare = mysqli_stmt_execute($stmtAdaugareRezervare);

            // Verificare rezultat
            if ($resultAdaugareRezervare) {
                // Obțineți ID-ul rezervării recent adăugate
                 $rezervare_id = mysqli_insert_id($con);

                // Redirecționare către noua pagină cu detaliile rezervării
                header("Location: pagina_detalii_rezervare.php?rezervare_id=" . $rezervare_id);
                exit();
            } else {
                echo "Eroare la rezervare: " . mysqli_error($con);
            }

            // Închidere instrucțiune pregătită
            mysqli_stmt_close($stmtAdaugareRezervare);
        } else {
            echo "Email-ul introdus nu corespunde niciunui utilizator.";
        }
    } else {
        echo "Datele introduse sunt incomplete sau invalide.";
    }
}

// Închidere conexiune la baza de date
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina Rezervare</title>
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
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    
    <form action="" method="post">
        <label for="email">Email:</label>
        <input type="email" name="email" required><br>

        <label for="rand">Alege rand:</label>
        <select name="rand" id="rand" required>
            <?php
                for ($i = 1; $i <= 10; $i++) {
                    echo '<option value="' . $i . '">' . $i . '</option>';
                }
            ?>
        </select><br>

        <label for="scaun">Alege scaun:</label>
        <select name="scaun" id="scaun" required>
            <?php
                for ($j = 1; $j <= 10; $j++) {
                    echo '<option value="' . $j . '">' . $j . '</option>';
                }
            ?>
        </select><br>

        <input type="hidden" name="difuzare_id" value="<?php echo isset($_GET['difuzare_id']) ? $_GET['difuzare_id'] : ''; ?>">
        <input type="hidden" name="pret" value="<?php echo isset($_POST['pret']) ? $_POST['pret'] : ''; ?>">
        <button type="submit">Rezerva</button>
    </form>
</body>
</html>
