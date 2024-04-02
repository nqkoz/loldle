<!DOCTYPE html>
<html lang="fr">

<head>
<meta charset="utf-8">
    <title>Loldle</title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script src="script.js"></script>
    <?php
    session_start();
    if (!isset($_SESSION['username'])) {
        header("Location: Accueil.php");
        exit();
    }
    include('headerutilisateur.php');

    if (!isset($_SESSION['idrandom'])) {
        $nombreAleatoire = rand(1, 163);
        $_SESSION['idrandom'] = $nombreAleatoire;
    }

    if (!isset($_SESSION['indices'])) {
        $_SESSION['indices'] = 5;
    }
    
   
    
    function jeu($nomChampion)
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "loldle";
    
        $conn = new mysqli($servername, $username, $password, $dbname);
    
        if ($conn->connect_error) {
            return '<p>La connexion à la base de données a échoué : ' . $conn->connect_error . '</p>';
        }
    
        $idGenre = $idRegion = $idRole = $idPortee = $idRessource = $idAnnee = '';
    

        $sqlChampionInfo = 'SELECT c.idChampion, g.idGenre, GROUP_CONCAT(DISTINCT e.idEspece) AS especes, GROUP_CONCAT(DISTINCT re.idRegion) AS regions, GROUP_CONCAT(DISTINCT ro.idRole) AS roles, p.idPortee, res.idRessource, a.idAnnee
        FROM champions AS c
        INNER JOIN championespeces AS ce ON c.idChampion = ce.idChampion
        INNER JOIN espece AS e ON ce.idEspece = e.idEspece
        INNER JOIN championregions AS cre ON c.idChampion = cre.idChampion
        INNER JOIN regions AS re ON cre.idRegion = re.idRegion
        INNER JOIN championroles AS cro ON c.idChampion = cro.idChampion
        INNER JOIN role AS ro ON cro.idRole = ro.idRole
        INNER JOIN genre AS g ON c.idGenre = g.idGenre
        INNER JOIN portee AS p ON c.idPortee = p.idPortee
        INNER JOIN ressource AS res ON c.idRessource = res.idRessource
        INNER JOIN annee AS a ON c.idAnnee = a.idAnnee
        WHERE c.nomChampion = ?';
    
        $stmt = $conn->prepare($sqlChampionInfo);
        $stmt->bind_param("s", $nomChampion);
        $stmt->execute();
        $result = $stmt->get_result();
    
        while ($row = $result->fetch_assoc()) {
            $idChampion = $row['idChampion'];
            $idGenre = $row['idGenre'];
            $idRegion = $row['regions'];
            $idRole = $row['roles'];
            $idPortee = $row['idPortee'];
            $idRessource = $row['idRessource'];
            $idAnnee = $row['idAnnee'];
            $idEspece = $row['especes'];
        }
    
        $sqlChampionMystereInfo = 'SELECT c.idChampion, g.idGenre, GROUP_CONCAT(DISTINCT e.idEspece) AS especes, GROUP_CONCAT(DISTINCT re.idRegion) AS regions, GROUP_CONCAT(DISTINCT ro.idRole) AS roles, p.idPortee, res.idRessource, a.idAnnee
        FROM champions AS c
        INNER JOIN championespeces AS ce ON c.idChampion = ce.idChampion
        INNER JOIN espece AS e ON ce.idEspece = e.idEspece
        INNER JOIN championregions AS cre ON c.idChampion = cre.idChampion
        INNER JOIN regions AS re ON cre.idRegion = re.idRegion
        INNER JOIN championroles AS cro ON c.idChampion = cro.idChampion
        INNER JOIN role AS ro ON cro.idRole = ro.idRole
        INNER JOIN genre AS g ON c.idGenre = g.idGenre
        INNER JOIN portee AS p ON c.idPortee = p.idPortee
        INNER JOIN ressource AS res ON c.idRessource = res.idRessource
        INNER JOIN annee AS a ON c.idAnnee = a.idAnnee
        WHERE c.idChampion = ?';
    
        $stmtMystere = $conn->prepare($sqlChampionMystereInfo);
        $stmtMystere->bind_param("i", $_SESSION['idrandom']);
        $stmtMystere->execute();
        $resultMystere = $stmtMystere->get_result();
    
        while ($rowMystere = $resultMystere->fetch_assoc()) {
            $idchampionMystere = $rowMystere['idChampion'];
            $idGenreMystere = $rowMystere['idGenre'];
            $idRegionMystere = $rowMystere['regions'];
            $idRoleMystere = $rowMystere['roles'];
            $idPorteeMystere = $rowMystere['idPortee'];
            $idRessourceMystere = $rowMystere['idRessource'];
            $idAnneeMystere = $rowMystere['idAnnee'];
            $idEspeceMystere = $rowMystere['especes'];
        }
        $idEspeceArray = explode(",", $idEspece);
        $idEspeceMystereArray = explode(",", $idEspeceMystere);
        $idRegionArray = explode(",", $idRegion);
        $idRegionMystereArray = explode(",", $idRegionMystere);
        $idRoleArray = explode(",", $idRole);
        $idRoleMystereArray = explode(",", $idRoleMystere);

        $correspondanceEspece = count(array_intersect($idEspeceArray, $idEspeceMystereArray)) > 0;
        $correspondanceRegion = count(array_intersect($idRegionArray, $idRegionMystereArray)) > 0;
        $correspondanceRole = count(array_intersect($idRoleArray, $idRoleMystereArray)) > 0;

        $correspondanceEspeceValue = $correspondanceEspece ? 1 : 0;
        $correspondanceRegionValue = $correspondanceRegion ? 1 : 0;
        $correspondanceRoleValue = $correspondanceRole ? 1 : 0;

        $valeurGenre = ($idGenre === $idGenreMystere) ? "Vert" : (($idGenre == $idGenreMystere) ? "Orange" : "Rouge");
        $valeurRole = ($idRole === $idRoleMystere) ? "Vert" : (($correspondanceRoleValue===1) ? "Orange" : "Rouge");
        $valeurEspece = ($idEspece === $idEspeceMystere) ? "Vert" : (($correspondanceEspeceValue===1) ? "Orange" : "Rouge");
        $valeurRessource = ($idRessource === $idRessourceMystere) ? "Vert" : (($idRessource == $idRessourceMystere) ? "Orange" : "Rouge");
        $valeurPortee = ($idPortee === $idPorteeMystere) ? "Vert" : (($idPortee == $idPorteeMystere) ? "Orange" : "Rouge");
        $valeurRegion = ($idRegion === $idRegionMystere) ? "Vert" : (($correspondanceRegionValue===1) ? "Orange" : "Rouge");
        $valeurAnnee = ($idAnnee === $idAnneeMystere) ? "Vert" : (($idAnnee == $idAnneeMystere) ? "Orange" : "Rouge");


        $sqlChampionNom = 'SELECT c.nomChampion, g.nomGenre, GROUP_CONCAT(DISTINCT e.nomEspece) AS especes, GROUP_CONCAT(DISTINCT re.nomRegion) AS regions, GROUP_CONCAT(DISTINCT ro.nomRole) AS roles, p.nomPortee, res.nomRessource, a.Annee
        FROM champions AS c
        INNER JOIN championespeces AS ce ON c.idChampion = ce.idChampion
        INNER JOIN espece AS e ON ce.idEspece = e.idEspece
        INNER JOIN championregions AS cre ON c.idChampion = cre.idChampion
        INNER JOIN regions AS re ON cre.idRegion = re.idRegion
        INNER JOIN championroles AS cro ON c.idChampion = cro.idChampion
        INNER JOIN role AS ro ON cro.idRole = ro.idRole
        INNER JOIN genre AS g ON c.idGenre = g.idGenre
        INNER JOIN portee AS p ON c.idPortee = p.idPortee
        INNER JOIN ressource AS res ON c.idRessource = res.idRessource
        INNER JOIN annee AS a ON c.idAnnee = a.idAnnee
        WHERE c.nomChampion = ?
        GROUP BY c.nomChampion;';

        $stmt = $conn->prepare($sqlChampionNom);
        $stmt->bind_param("s", $nomChampion);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result === false) {
            return '<p>Erreur lors de l\'exécution de la requête : ' . $conn->error . '</p>';
        }

        $championInfo = '';

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $championInfo .= '<div class="reponse1" id="Reponses">';
                $championInfo .= '<div><img classs="imageChampion"src="images/champions/' . $row['nomChampion'] . '" alt="' . $row['nomChampion'] . '"></div>';
                $championInfo .= '<div class="' . ($valeurGenre === "Vert" ? 'Vert' : ($valeurGenre === "Orange" ? 'Orange' : 'Rouge')) . '"><h4>Genre</h4><p>' . $row['nomGenre'] . '</p></div>';
                $championInfo .= '<div class="' . ($valeurRole === "Vert" ? 'Vert' : ($valeurRole === "Orange" ? 'Orange' : 'Rouge')) . '"><h4>Roles</h4><p>' . $row['roles'] . '</p></div>';
                $championInfo .= '<div class="' . ($valeurEspece === "Vert" ? 'Vert' : ($valeurEspece === "Orange" ? 'Orange' : 'Rouge')) . '"><h4>Especes</h4><p>' . $row['especes'] . '</p></div>';
                $championInfo .= '<div class="' . ($valeurRessource === "Vert" ? 'Vert' : ($valeurRessource === "Orange" ? 'Orange' : 'Rouge')) . '"><h4>Ressource</h4><p>' . $row['nomRessource'] . '</p></div>';
                $championInfo .= '<div class="' . ($valeurPortee === "Vert" ? 'Vert' : ($valeurPortee === "Orange" ? 'Orange' : 'Rouge')) . '"><h4>Portée</h4><p>' . $row['nomPortee'] . '</p></div>';
                $championInfo .= '<div class="' . ($valeurRegion === "Vert" ? 'Vert' : ($valeurRegion === "Orange" ? 'Orange' : 'Rouge')) . '"><h4>Regions</h4><p>' . $row['regions'] . '</p></div>';
                $championInfo .= '<div class="' . ($valeurAnnee === "Vert" ? 'Vert' : ($valeurAnnee === "Orange" ? 'Orange' : 'Rouge')) . '"><h4>Année</h4><p>' . $row['Annee'] . '</p></div>';
                $championInfo .= '</div>';
            }
        } 
        $sqlInsertion = "INSERT INTO infochampion (html) VALUES (?)";
        $sqlInser = $conn->prepare($sqlInsertion);
        $sqlInser->bind_param("s", $championInfo);
        $sqlInser->execute();
        $sqlInser->close();
        
        $sqlhtml = "SELECT html FROM infochampion ORDER BY id DESC";
        $result = $conn->query($sqlhtml);
        
        if ($result->num_rows > 0) {
            echo '<div class="reponse">';
            while ($row = $result->fetch_assoc()) {
                echo $row["html"];
            }
            echo '</div>';
        }
        if ($idChampion == $_SESSION['idrandom']) {
            $_SESSION['idrandom'] = rand(1, 163);
            $_SESSION['indices']= 5;
            $sqlTruncate = "TRUNCATE TABLE infochampion";
            if ($conn->query($sqlTruncate) === TRUE) {  
                echo '  <div class="main-container">';
                echo '    <div class="content">';
                echo '      <img src="images/champions/' . $nomChampion. '" alt="Avatar" style="width:100px">';
                echo '      <div class="text-container">';
                echo '          <h4><b>' . "Bien joué, tu as trouvé :" . '</b></h4>';
                echo '          <h4 class="champion-name"><b>' . $nomChampion. '</b></h4>';
                echo '      </div>';
                echo '    </div>';
                echo '    <a href="AccueilUtilisateur.php"><img class = "rejouer" src="./images/rejouer.png"></a>';   
                echo '  </div>';
            }
            }
        $conn->close();
        $_SESSION['indices'] = $_SESSION['indices']-1;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST["rechercheChampion"];
        echo jeu($id);
        echo $_SESSION['idrandom'];
    }
    ?>
    
</head>

<body class="backImageBody">
     
    
    <style>
    /* Réinitialisation des styles pour le bouton */
    #bouton {
      background: none;
      border: none;
      padding: 0;
      margin: 0;
    }
    /* Assurez-vous que l'image remplit bien le bouton */
    #bouton img {
      display: block;
    }
  </style>
  <div class="jeu1">
        <form action="" method="post">
            <img class = "logo" src="./images/logoclean.png">
            
            <div class = "jeu">
                <p class="titrejeu">Devine le champion de League of Legends !</p>
                <p class = "soustitre">Tape n'importe quel champion</p>
                <p class="soustitre">pour commencer</p>
                <?php
                if($_SESSION['indices']<=0){

                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "loldle";

                    $conn = new mysqli($servername, $username, $password, $dbname);

                    if ($conn->connect_error) {
                        return '<p>La connexion à la base de données a échoué : ' . $conn->connect_error . '</p>';
                    }
                    $sqlAnnee = "SELECT a.Annee
                    FROM annee AS a
                    INNER JOIN champions AS c ON a.idAnnee = c.idAnnee
                    WHERE c.idChampion = ?";
                    $stmtAnnee = $conn->prepare($sqlAnnee);
                    $stmtAnnee->bind_param("i", $_SESSION['idrandom']);
                    $stmtAnnee->execute();
                    $resultAnnee = $stmtAnnee->get_result();
                    $rowAnnee = $resultAnnee->fetch_assoc();
                    $Annee = $rowAnnee['Annee'];

                    echo'<p class="soustitre">Indice : Le champion est de '.$Annee.'</p>';
                }
                ?>
            </div>
           
            <div class="barrerecherche">
                <input type="text" id="rechercheChampion" name="rechercheChampion" placeholder="   Tape le nom d'un champion" class="recherche" required>
                <button type="submit" id="bouton"  value="Envoyer">
                    <img class="soumettre" src="./images/boutonsoumettre.png">
                </button>
            </div>
            

        </form>
</div>
   
</body>
</html>