<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles4.css">
    <script src="script.js"></script>
<?php
$imagePath = "images/parametre.png";
function deconnexion(){
    $_SESSION = array();
    session_destroy();
    header("Location: Accueil.php");
    exit();
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deconnexion'])) {
        deconnexion();
    }
    ?>
<header>
    <div class="logo-container">
        <a href="modifierProfil.php">
            <img src="<?php echo $imagePath; ?>" alt="Your Image Description" class="parametre">
        </a>
        <a href="ami.php">
            <img src="images/ajouter-un-ami" alt="amis" class="ami">
        </a>
        <a href="accueilUtilisateur.php">
        <img src="images/logoclean" alt="clean" class="clean">
    </a>

    </div>

    
    <div class="right-section">
        <div class="Profil">
            <a href="profil.php">
                <img src="images/profil" alt="profil" class="profil">
            </a>
        </div>
        <div class="Connexion">
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <button type="submit" name="deconnexion" class="deco">
            <img src="images/loldeco" alt="deco" class="deco">
        </button>
    </form>
</div>
    </div>
</header>

</body>
</html>
