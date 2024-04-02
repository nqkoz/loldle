<!DOCTYPE html>
<html lang="fr">

<head>
<meta charset="utf-8">
<title>Loldle</title>
<link rel="stylesheet" href="styles.css">
</head>
<body class="backImageBody">


<?php
include('header.php');

function verifier_connexion($nomUtilisateur, $motDePasse) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "projetweb";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }

    $sqlVerifExistence = "SELECT user_id, username,  password, admin FROM user WHERE username = ?";
    $stmt = $conn->prepare($sqlVerifExistence);
    $stmt->bind_param("s", $nomUtilisateur);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username, $hashedPassword, $admin);
        $stmt->fetch();

        if (password_verify($motDePasse, $hashedPassword)) {
            session_start();
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;

            $result = "Connexion réussie.";
            if($admin===1){
            header("Location: AccueilAdmin.php");
            }else{
            header("Location: AccueilUtilisateur.php");
            }
        } else {
            $result = "Mot de passe incorrect.";
        }
    } else {
        $result = "L'utilisateur n'existe pas.";
    }

    $stmt->close();
    $conn->close();
    echo '<div class="container2">';
    return $result;
    echo '</div>';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $result =  verifier_connexion($username, $password);
    echo $result;
}
?>

</body>