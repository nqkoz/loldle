<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['user_id'];
    $amiId = $_POST['ami_id'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "projetweb";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }

    $stmt = $conn->prepare("UPDATE amis SET statut = 1 WHERE id_receveur = ? AND id_demandeur = ?");
    $stmt->bind_param("ii", $userId, $amiId);
    $stmt->execute();

    if ($stmt->error) {
        die("Erreur d'exécution de la requête : " . $stmt->error);
    }

    $stmt->close();
    $conn->close();
    header("Location: AccueilUtilisateur.php");
} else {
    header("Location: AccueilUtilisateur.php");
    exit();
}
?>
