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
function getAllRessources(){
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "loldle";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }

    $sqlRessources = "SELECT idRessource, nomRessource FROM ressource";
    $result = $conn->query($sqlRessources);

    $ressources = array();

    if($result->num_rows > 0){
        while ($row = $result->fetch_assoc()) {
            $ressources[] = array(
                'idRessource' => $row['idRessource'],
                'nomRessource' => $row['nomRessource']
            );
        }
    }

    return $ressources;
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

    $sqlRegions = "SELECT idRegion, nomRegion FROM regions";
    $result = $conn->query($sqlRegions);

    $regions = array();

    if($result->num_rows > 0){
        while ($row = $result->fetch_assoc()) {
            $regions[] = array(
                'idRegion' => $row['idRegion'],
                'nomRegion' => $row['nomRegion']
            );
        }
    }

    return $regions;
}

function getAllEspeces(){
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "loldle";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }

    $sqlEspeces = "SELECT idEspece, nomEspece FROM espece";
    $result = $conn->query($sqlEspeces);

    $especes = array();

    if($result->num_rows > 0){
        while ($row = $result->fetch_assoc()) {
            $especes[] = array(
                'idEspece' => $row['idEspece'],
                'nomEspece' => $row['nomEspece']
            );
        }
    }

    return $especes;
}

function getAllChampions(){
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "loldle";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }

    $sqlChampions = "SELECT nomChampion, idChampion FROM champions";
    $result = $conn->query($sqlChampions);

    $champions = array();

    if($result->num_rows > 0){
        while ($row = $result->fetch_assoc()) {
            $champions[] = array(
                'nomChampion' => $row['nomChampion'],
                'idChampion' => $row['idChampion']
            );
        }
    }

    return $champions;
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
    $sqlNom = "SELECT nomChampion FROM champions WHERE idChampion = ?";
    $stmtNom = $conn->prepare($sqlNom);
    $stmtNom->bind_param("i", $nom);
    $stmtNom->execute();
    $resultNom= $stmtNom->get_result();
    $rowNom = $resultNom->fetch_assoc();
    $NomChampion = $rowNom['nomChampion'];



    $sqlDelete = "DELETE FROM champions WHERE idChampion = '$nom'";
    $conn->query($sqlDelete);
    $sqlDelete = "DELETE FROM championespeces WHERE idChampion = '$nom'";
    $conn->query($sqlDelete);
    $sqlDelete = "DELETE FROM championregions WHERE idChampion = '$nom'";
    $conn->query($sqlDelete);
    $sqlDelete = "DELETE FROM championroles WHERE idChampion = '$nom'";
    $conn->query($sqlDelete);
   
    $sqlAnnee = "SELECT idAnnee FROM annee WHERE Annee = ?";
    $stmtAnnee = $conn->prepare($sqlAnnee);
    $stmtAnnee->bind_param("i", $annee);
    $stmtAnnee->execute();
    $resultAnnee = $stmtAnnee->get_result();
    $rowAnnee = $resultAnnee->fetch_assoc();
    $idAnnee = $rowAnnee['idAnnee'];

    $sqlInsert = "INSERT INTO champions (idChampion, nomChampion, idGenre, idRessource, idPortee, idAnnee) VALUES (?, ?, ?, ?, ?, ?)";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bind_param("isiiii", $nom, $NomChampion, $genre, $ressource, $portee, $idAnnee);
    $stmtInsert->execute();
    if ($stmtInsert->affected_rows > 0) {
        echo "Champion inséré avec succès.";
    } else {
        echo "Erreur lors de l'insertion du champion.";
    }
    $stmtAnnee->close();
    $stmtInsert->close();

$idRoles = array();
$idEspeces = array();
$idRegions = array();

// Traitement des rôles
foreach ($role as $role_single) {
    $sqlInsert = "INSERT INTO championroles (idChampion, idRole) VALUES (?, ?)";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bind_param("ii",$nom,$role_single);
    $stmtInsert->execute();
    if ($stmtInsert->affected_rows > 0) {
        echo "Champion inséré avec succès.";
    } else {
        echo "Erreur lors de l'insertion du champion.";
    }
}

// Traitement des espèces
foreach ($espece as $espece_single) {
    $sqlInsert = "INSERT INTO championespeces (idChampion, idEspece) VALUES (?, ?)";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bind_param("ii",$nom,$espece_single);
    $stmtInsert->execute();
    if ($stmtInsert->affected_rows > 0) {
        echo "Champion inséré avec succès.";
    } else {
        echo "Erreur lors de l'insertion du champion.";
    }
}

// Traitement des régions
foreach ($region as $region_single) {
    $sqlInsert = "INSERT INTO championregions (idChampion, idRegion) VALUES (?, ?)";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bind_param("ii",$nom,$region_single);
    $stmtInsert->execute();
    if ($stmtInsert->affected_rows > 0) {
        echo "Champion inséré avec succès.";
    } else {
        echo "Erreur lors de l'insertion du champion.";
    }
}  
    $conn->close();
    $chemin = "images/champions/" . $NomChampion. ".png";

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
<select id="champion" name="champion" required>
    <?php
    $champions = getAllChampions();
    foreach ($champions as $champion) {
        echo "<option value=\"{$champion['idChampion']}\">{$champion['nomChampion']}</option>";
    }
    ?>
</select>

    
    
    <label for="genre">Genre :</label><br>
    <select id="genre" name="genre" required>
        <option value="1">Masculin</option>
        <option value="2">Féminin</option>
        <option value="3">Autre</option>
    </select><br>
    
    <label for="roles">Rôles :</label><br>
    <select id="roles" name="roles[]" multiple required>
        <option value="1">Haut</option>
        <option value="2">Jungle</option>
        <option value="3">Milieu</option>
        <option value="4">Bas</option>
        <option value="5">Support</option>
    </select><br>
    
    <label for="especes">Espèces :</label><br>
    <select id="especes" name="especes[]" multiple required>
    <?php
    $especes = getAllEspeces();
    foreach ($especes as $espece) {
        echo "<option value=\"{$espece['idEspece']}\">{$espece['nomEspece']}</option>";
    }
    ?>
</select>

    
<select id="ressource" name="ressource" required>
    <?php
    $ressources = getAllRessources();
    foreach ($ressources as $ressource) {
        echo "<option value=\"{$ressource['idRessource']}\">{$ressource['nomRessource']}</option>";
    }
    ?>
</select>

    
    <label for="portee">Portée :</label><br>
    <select id="portee" name="portee" required>
        <option value="1">Mêlée</option>
        <option value="2">À distance</option>
        <option value="3">Mixte</option>
    </select><br>
    
    <select id="region" name="region[]" multiple required>
    <?php
    $regions = getAllRegions();
    foreach ($regions as $region) {
        echo "<option value=\"{$region['idRegion']}\">{$region['nomRegion']}</option>";
    }
    ?>
</select>

    
    <label for="annee">Année :</label><br>
    <input type="number" id="annee" name="annee" min="1900" max="2100" required><br>
    <label for="image">Image :</label><br>
    <input type="file" name="image" id="image" required><br>
    <input type="submit" value="Soumettre">
</form>
</body>
</html>
