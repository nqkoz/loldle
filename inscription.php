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

function creerNouvelUtilisateur($nomUtilisateur, $motDePasse, $email) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "projetweb";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        return "La connexion à la base de données a échoué : " . $conn->connect_error;
    }

    // Vérification de l'existence du nom d'utilisateur
    $sqlVerifExistenceUsername = "SELECT user_id FROM user WHERE username = ?";
    $stmtUsername = $conn->prepare($sqlVerifExistenceUsername);
    $stmtUsername->bind_param("s", $nomUtilisateur);
    $stmtUsername->execute();
    $stmtUsername->store_result();
    if ($stmtUsername->num_rows > 0) {
        $conn->close();
        return "Un utilisateur avec ce pseudo existe déjà.";
    }

    // Vérification de l'existence de l'adresse e-mail
    $sqlVerifExistenceEmail = "SELECT user_id FROM user WHERE email = ?";
    $stmtEmail = $conn->prepare($sqlVerifExistenceEmail);
    $stmtEmail->bind_param("s", $email);
    $stmtEmail->execute();
    $stmtEmail->store_result();
    if ($stmtEmail->num_rows > 0) {
        $conn->close();
        echo '<div class="container2">';
        return "Un utilisateur avec cette adresse e-mail existe déjà.";
        echo '</div>';
    }

    // Vérification du mot de passe
    if (strlen($motDePasse) >= 8 && preg_match('/[a-zA-Z]/', $motDePasse) && preg_match('/\d/', $motDePasse)) {
        $motDePasseHash = password_hash($motDePasse, PASSWORD_DEFAULT);

        $sqlInsertion = "INSERT INTO user (username, password, email, photoprofil) VALUES (?, ?, ?, 'base.png')";
        $stmtInsertion = $conn->prepare($sqlInsertion);
        $stmtInsertion->bind_param("sss", $nomUtilisateur, $motDePasseHash, $email);
        if ($stmtInsertion->execute()) {
            $conn->close();
            echo '<div class="container2">';
            return "L'utilisateur a été créé avec succès.";
            echo '</div>';
            header('Location: Accueil.php');
            exit();
        } else {
            $conn->close();
            return "Erreur lors de la création de l'utilisateur : " . $stmtInsertion->error;
        }
    } else {
        $conn->close();
        echo '<div class="container2">';
        return "Le mot de passe doit contenir au moins 8 caractères, incluant des lettres et des chiffres.";
        echo '</div>';
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];
    $result = creerNouvelUtilisateur($username, $password, $email);
    echo $result;
}
?>
