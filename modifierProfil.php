<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Profil</title>
    <link rel="stylesheet" href="styles5.css">
</head>
<body class="backImageBody">

<?php
session_start();
include('headerutilisateur.php');


function modifierProfil($nomUtilisateur, $file, $motdepasse) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "projetweb";
    $user_id = $_SESSION['user_id'];
    
    $db = new mysqli($servername, $username, $password, $dbname);
    
    if ($db->connect_error) {
        die("La connexion à la base de données a échoué : " . $db->connect_error);
    }
    
    $ancienNomUtilisateur = $_SESSION['username'];
    if ($nomUtilisateur !== $ancienNomUtilisateur) {
        $sqlVerifExistence = "SELECT user_id FROM user WHERE username = ?";
        $stmt = $db->prepare($sqlVerifExistence);
        $stmt->bind_param("s", $nomUtilisateur);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $db->close();
            return "Un utilisateur avec ce pseudo existe déjà.";
        }
    }
    
    $extensionsValides = array('jpg', 'jpeg', 'png');
    $extensionsUpload = strtolower(substr(strrchr($file['name'], '.'), 1));
    
    if (in_array($extensionsUpload, $extensionsValides)) {
        // ...
        $chemin = "images/photoprofil/" . $_SESSION['user_id'] . "." . $extensionsUpload;
        $deplacement = move_uploaded_file($file['tmp_name'], $chemin);
        
        if ($deplacement) {
            $combinedFileName = $_SESSION['user_id'] . "." . $extensionsUpload;
            
            // Mise à jour du champ 'photoprofil' dans la table 'user'
            $updatepdprequest = 'UPDATE user SET photoprofil = ? WHERE user_id = ?';
            $updatepdp = $db->prepare($updatepdprequest);
            $updatepdp->bind_param('si', $combinedFileName, $user_id);
            $updatepdp->execute();
        } else {
            $db->close();
            return "Erreur lors de l'importation du fichier";
        }
    } else {
        $db->close();
        return "La photo de profil doit être en jpg, jpeg ou png";
    }
    
    if (strlen($motdepasse) >= 8 && preg_match('/[a-zA-Z]/', $motdepasse) && preg_match('/\d/', $motdepasse)) {
        $motDePasseHash = password_hash($motdepasse, PASSWORD_DEFAULT);
        
        $updatemdp = 'UPDATE user SET password = ? WHERE user_id = ?';
        $updatemdp = $db->prepare($updatemdp);
        $updatemdp->bind_param('si', $motDePasseHash, $user_id);
        $updatemdp->execute();
        
        $updateUsername = 'UPDATE user SET username = ? WHERE user_id = ?';
        $updateUsername = $db->prepare($updateUsername);
        $updateUsername->bind_param('si', $nomUtilisateur, $user_id);
        $updateUsername->execute();
    } else {
        $db->close();
        return "Le mot de passe doit contenir au moins 8 caractères, incluant des lettres et des chiffres.";
    }
    
    $db->close();
    return "Profil modifié avec succès.";
}

if (isset($_SESSION['user_id'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nouveauNomUtilisateur = $_POST['username'];
        $motDePasse = $_POST['password'];
        $file = $_FILES['photodeprofil'];
        
        $resultatModification = modifierProfil($nouveauNomUtilisateur, $file, $motDePasse);
        
        echo $resultatModification;
    }
}
?>
<div class="container">
    
            <form action="" method="post" enctype="multipart/form-data">
            <h1 class="titre">Modifier votre profil</h1>
            <label class="nom_imput">Nouveau nom d'utilisateur :</label>
            <input type="text" placeholder="Nom d'utilisateur" name="username" required>
            
            <label class="nom_imput">Nouveau Mot de Passe :</label>
            <input type="password" placeholder="Mot de Passe" name="password" required>
            
            <label class="nom_imput">Nouvelle Photo de profil :</label>
            <input type="file" name="photodeprofil" required>
            
            <button type="submit" class="btn">Modifier</button>
            
        </form>
    </div>
</body>
</html>
