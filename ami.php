<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Amis</title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="styles6.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script src="script.js"></script>
    <?php
        session_start();
        include('headerutilisateur.php');
        if (!isset($_SESSION['username'])) {
            header("Location: Accueil.php");
            exit();
        }

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "projetweb";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("La connexion à la base de données a échoué : " . $conn->connect_error);
        }

        $userId = mysqli_real_escape_string($conn, $_SESSION['user_id']);

        $stmtPending = $conn->prepare("
            SELECT u.user_id, u.username, u.photoprofil
            FROM user AS u
            WHERE u.user_id IN (
                SELECT a.id_demandeur
                FROM amis AS a
                WHERE a.id_receveur = ? AND a.statut = 0
            );
        ");

        $stmtPending->bind_param("i", $userId);
        $stmtPending->execute();

        $resultPending = $stmtPending->get_result();

        $stmtConfirmed = $conn->prepare("
            SELECT u.user_id, u.username, u.photoprofil
            FROM user AS u
            WHERE u.user_id IN (
                SELECT a.id_demandeur
                FROM amis AS a
                WHERE (a.id_receveur = ? OR a.id_demandeur = ?) AND a.statut = 1
            );
        ");

        $stmtConfirmed->bind_param("ii", $userId, $userId);
        $stmtConfirmed->execute();

        $resultConfirmed = $stmtConfirmed->get_result();
    ?>
</head>
<body class="backImageBody">
<div class="container">
    <div class = "titre">
    <h1>Liste d'Amis</h1>
    </div>
    <?php
        if ($resultPending->num_rows > 0) {
            echo"<h1>VOUS AVEZ REÇU UNE DEMANDE D'AMI</h1>";
            echo "<form id='accepterAmiForm' action='accepterAmi.php' method='post'>";
            while ($row = $resultPending->fetch_assoc()) {
                echo "<div class='ami'>";
                echo "<p>Nom d'utilisateur : " . $row['username'] . "</p>";
                echo "<img class='demandeamis' src='images/photoprofil/" . $row['photoprofil'] . "' alt='Photo de profil'>";
                echo "<button type='submit' class='accepter-ami' name='ami_id' value='" . $row['user_id'] . "'>Accepter</button>";
                echo "</div>";
            }
            echo "</form>";
        } else {
            echo "<p>Aucune demande d'ami en attente.</p>";
        }

        if ($resultConfirmed->num_rows > 0) {
            echo "<div id='amisConfirmesContainer'>";
            echo "<div class='vosamis'>";
            echo "<h1>VOS AMIS :</h1>";
            echo "</div>"; 
            while ($row = $resultConfirmed->fetch_assoc()) {
                echo "<div class='ami'>";
                echo '<p class="kk"> Nom utilisateur : ' . $row['username'] .' </p>';
                echo "<img class='demandeamis' src='images/photoprofil/" . $row['photoprofil'] . "' alt='Photo de profil'>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<p>Vous n'avez pas encore ajouté d'amis</p>";
        }

        $stmtPending->close();
        $stmtConfirmed->close();
        $conn->close();
    ?>
</body>
<div id="jeu" class="jeu">

<form action="" id="Loldle" method="post">
    <label class="nom_imput" for="rechercheAmis">Ajouter un ami:</label>
    <input type="text" id="rechercheAmis" name="Amis" placeholder="Ajouter un ami " class="recherche" value="" required>
    <input class="btn" type="submit" value="Envoyer">
</form>
    </div>
</div>
<?php
function DemandeAmis($username, $id_demandeur) {
    $servername = "localhost";
    $db_username = "root"; 
    $password = "";
    $dbname = "projetweb";

    $conn = new mysqli($servername, $db_username, $password, $dbname);

    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }

    $RecupererId = $conn->prepare("SELECT user_id FROM user WHERE username = ?");
    $RecupererId->bind_param("s", $username);
    $RecupererId->execute();
    $resultat = $RecupererId->get_result();
    $row = $resultat->fetch_assoc();
    $id_receveur = $row['user_id'];
    $RecupererId->close();

    $sqlInsertion = "INSERT INTO amis (id_demandeur, id_receveur, statut) VALUES (?, ?, 0);";
    $stmtInsertion = $conn->prepare($sqlInsertion);
    $stmtInsertion->bind_param("ii", $id_demandeur, $id_receveur);

    if ($stmtInsertion->execute()) {
        $conn->close();
        return "Demande d'ami envoyée";
    } else {
        $conn->close();
        return "Erreur dans l'ajout de l'ami : " . $stmtInsertion->error;
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["Amis"];
    $id = $_SESSION["user_id"];
    echo DemandeAmis($username,$id);
}
?>
</html>
