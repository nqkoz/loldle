<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Titre de la page</title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="styles8.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script src="script.js"></script>
</head>
<?php
include('headerUtilisateur.php');
function getAllEspeces(){
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "loldle";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }

    $sqlEspeces = "SELECT nomEspece FROM espece";
    $result = $conn->query($sqlEspeces);

    $especes = array();

    if($result->num_rows > 0){
        while ($row = $result->fetch_assoc()) {
            $especes[] = $row['nomEspece'];
        }
    }

    return $especes;
}
function getAllRegions(){
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "loldle";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }

    $sqlRegions = "SELECT nomRegion FROM regions";
    $result = $conn->query($sqlRegions);

    $regions = array();

    if($result->num_rows > 0){
        while ($row = $result->fetch_assoc()) {
            $regions[] = $row['nomRegion'];
        }
    }

    return $regions;
}

function getAllRessources(){
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "loldle";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }

    $sqlRessources = "SELECT nomRessource FROM ressource";
    $result = $conn->query($sqlRessources);

    $ressources = array();

    if($result->num_rows > 0){
        while ($row = $result->fetch_assoc()) {
            $ressources[] = $row['nomRessource'];
        }
    }

    return $ressources;
}
    function AjouterChampion($nom, $genre, $role, $espece, $ressource, $portee, $region, $annee, $image)
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "loldle";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }

    $sqlGenre = "SELECT idGenre FROM genre WHERE nomGenre = ?";
    $stmtGenre = $conn->prepare($sqlGenre);
    $stmtGenre->bind_param("s", $genre);
    $stmtGenre->execute();
    $resultGenre = $stmtGenre->get_result();
    $rowGenre = $resultGenre->fetch_assoc();
    $idGenre = $rowGenre['idGenre'];

    $sqlRessource = "SELECT idRessource FROM ressource WHERE nomRessource = ?";
    $stmtRessource = $conn->prepare($sqlRessource);
    $stmtRessource->bind_param("s", $ressource);
    $stmtRessource->execute();
    $resultRessource = $stmtRessource->get_result();
    $rowRessource = $resultRessource->fetch_assoc();
    $idRessource = $rowRessource['idRessource'];

    $sqlPortee = "SELECT idPortee FROM portee WHERE nomPortee = ?";
    $stmtPortee = $conn->prepare($sqlPortee);
    $stmtPortee->bind_param("s", $portee);
    $stmtPortee->execute();
    $resultPortee = $stmtPortee->get_result();
    $rowPortee = $resultPortee->fetch_assoc();
    $idPortee = $rowPortee['idPortee'];

    $sqlAnnee = "SELECT idAnnee FROM annee WHERE Annee = ?";
    $stmtAnnee = $conn->prepare($sqlAnnee);
    $stmtAnnee->bind_param("i", $annee);
    $stmtAnnee->execute();
    $resultAnnee = $stmtAnnee->get_result();
    $rowAnnee = $resultAnnee->fetch_assoc();
    $idAnnee = $rowAnnee['idAnnee'];

    $sqlInsert = "INSERT INTO champions (nomChampion, idGenre, idRessource, idPortee, idAnnee) VALUES (?, ?, ?, ?, ?)";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bind_param("siiii", $nom, $idGenre, $idRessource, $idPortee, $idAnnee);
    $stmtInsert->execute();
    if ($stmtInsert->affected_rows > 0) {
        echo "Champion inséré avec succès.";
    } else {
        echo "Erreur lors de l'insertion du champion.";
    }

    $stmtGenre->close();
    $stmtRessource->close();
    $stmtPortee->close();
    $stmtAnnee->close();
    $stmtInsert->close();

$idRoles = array();
$idEspeces = array();
$idRegions = array();

// Traitement des rôles
foreach ($role as $role_single) {
    $sqlRole = "SELECT idRole FROM role WHERE nomRole = ?";
    $stmtRole = $conn->prepare($sqlRole);
    $stmtRole->bind_param("s", $role_single);
    $stmtRole->execute();
    $resultRole = $stmtRole->get_result();
    
    if ($resultRole->num_rows > 0) {
        $rowRole = $resultRole->fetch_assoc();
        $idRole = $rowRole['idRole'];
        $idRoles[] = $idRole; // Ajouter l'ID au tableau
    }
}

// Traitement des espèces
foreach ($espece as $espece_single) {
    $sqlEspece = "SELECT idEspece FROM espece WHERE nomEspece = ?";
    $stmtEspece = $conn->prepare($sqlEspece);
    $stmtEspece->bind_param("s", $espece_single);
    $stmtEspece->execute();
    $resultEspece = $stmtEspece->get_result();
    
    if ($resultEspece->num_rows > 0) {
        $rowEspece = $resultEspece->fetch_assoc();
        $idEspece = $rowEspece['idEspece'];
        $idEspeces[] = $idEspece; // Ajouter l'ID au tableau
    }
}

// Traitement des régions
foreach ($region as $region_single) {
    $sqlRegion = "SELECT idRegion FROM regions WHERE nomRegion = ?";
    $stmtRegion = $conn->prepare($sqlRegion);
    $stmtRegion->bind_param("s", $region_single);
    $stmtRegion->execute();
    $resultRegion = $stmtRegion->get_result();
    
    if ($resultRegion->num_rows > 0) {
        $rowRegion = $resultRegion->fetch_assoc();
        $idRegion = $rowRegion['idRegion'];
        $idRegions[] = $idRegion; // Ajouter l'ID au tableau
    }
}
$sqlid = "SELECT idChampion FROM champions WHERE nomChampion = ?";
$stmtid = $conn->prepare($sqlid);
$stmtid->bind_param("s", $nom);
$stmtid->execute();
$resultid = $stmtid->get_result();
$rowid = $resultid->fetch_assoc();
$id = $rowid['idChampion'];

foreach($idRoles as $idRoles_single){
    $sqlInsert = "INSERT INTO championroles (idChampion, idRole) VALUES (?, ?)";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bind_param("ii",$id,$idRoles_single);
    $stmtInsert->execute();
    if ($stmtInsert->affected_rows > 0) {
        echo "Champion inséré avec succès.";
    } else {
        echo "Erreur lors de l'insertion du champion.";
    }
}
foreach($idEspeces as $idEspece_single){
    $sqlInsert = "INSERT INTO championespeces (idChampion, idEspece) VALUES (?, ?)";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bind_param("ii",$id,$idEspece_single);
    $stmtInsert->execute();
    if ($stmtInsert->affected_rows > 0) {
        echo "Champion inséré avec succès.";
    } else {
        echo "Erreur lors de l'insertion du champion.";
    }
}
foreach($idRegions as $idRegion_single){
    $sqlInsert = "INSERT INTO championregions (idChampion, idRegion) VALUES (?, ?)";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bind_param("ii",$id,$idRegion_single);
    $stmtInsert->execute();
    if ($stmtInsert->affected_rows > 0) {
        echo "Champion inséré avec succès.";
    } else {
        echo "Erreur lors de l'insertion du champion.";
    }
}   
    $conn->close();
    $chemin = "images/champions/" . $nom . ".png";

// Chemin temporaire de l'image
$image_temporaire = $_FILES['image']['tmp_name']; // Si l'image est téléchargée depuis un formulaire

// Déplacer l'image vers le nouveau chemin
if (move_uploaded_file($image_temporaire, $chemin)) {
    echo "L'image a été déplacée avec succès.";
} else {
    echo "Erreur lors du déplacement de l'image.";
}
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérification si les champs requis sont présents
    if (isset($_POST['champion'], $_POST['genre'], $_POST['roles'], $_POST['especes'], $_POST['ressource'], $_POST['portee'], $_POST['region'], $_POST['annee'], $_FILES['image'])) {
        // Récupération des données du formulaire
        $nom = $_POST['champion'];
        $genre = $_POST['genre'];
        $roles = $_POST['roles'];
        $especes = $_POST['especes'];
        $ressource = $_POST['ressource'];
        $portee = $_POST['portee'];
        $region = $_POST['region'];
        $annee = $_POST['annee'];
        // Récupération de l'image téléchargée
        $image = $_FILES['image'];
        // Appel de la fonction AjouterChampion avec les données récupérées
        AjouterChampion($nom, $genre, $roles, $especes, $ressource, $portee, $region, $annee, $image);
    }
}
?>
<body>
<form action="" method="post" enctype="multipart/form-data">
    <label for="champion">Nom du Champion :</label><br>
    <input type="text" name="champion" id="champion" placeholder="Nom Champion" required><br>
    
    
    <label for="genre">Genre :</label><br>
    <select id="genre" name="genre" required>
        <option value="Masculin">Masculin</option>
        <option value="Féminin">Féminin</option>
        <option value="Autre">Autre</option>
    </select><br>
    
    <label for="roles">Rôles :</label><br>
    <select id="roles" name="roles[]" multiple required>
        <option value="Haut">Haut</option>
        <option value="Jungle">Jungle</option>
        <option value="Milieu">Milieu</option>
        <option value="Bas">Bas</option>
        <option value="Support">Support</option>
    </select><br>
    
    <label for="especes">Espèces :</label><br>
    <select id="especes" name="especes[]" multiple required>
        <?php
        $especes = getAllEspeces();
        foreach ($especes as $espece) {
            echo "<option value=\"$espece\">$espece</option>";
        }
        ?>
    </select><br>
    
    <label for="ressource">Ressource :</label><br>
    <select id="ressource" name="ressource" required>
        <?php
        $ressources = getAllRessources();
        foreach ($ressources as $ressource) {
            echo "<option value=\"$ressource\">$ressource</option>";
        }
        ?>
    </select><br>
    
    <label for="portee">Portée :</label><br>
    <select id="portee" name="portee" required>
        <option value="Mélée">Mélée</option>
        <option value="À distance">À distance</option>
        <option value="Mixte">Mixte</option>
    </select><br>
    
    <label for="region">Région :</label><br>
    <select id="region" name="region[]" multiple required>
        <?php
        $regions = getAllRegions();
        foreach ($regions as $region) {
            echo "<option value=\"$region\">$region</option>";
        }
        ?>
    </select><br>
    
    <label for="annee">Année :</label><br>
    <input type="number" id="annee" name="annee" min="1900" max="2100" required><br>
    
    <label for="image">Image :</label><br>
    <input type="file" name="image" id="image" required><br>
    
    <input type="submit" value="Soumettre">
</form>
</body>
</html>
