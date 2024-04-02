<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Profil</title>
  <link rel="stylesheet" href="styles7.css">
  <script src="script.js"></script>
</head>
<body class="backImageBody">
  
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

$sqlVerifExistence = "SELECT photoprofil FROM user WHERE user_id = ?";
$stmt = $conn->prepare($sqlVerifExistence);
$stmt->bind_param("s", $_SESSION['user_id']);
$stmt->execute();
$stmt->store_result();

$stmt->bind_result($photoprofil);
$stmt->fetch();
$stmt->close();
$sqlVerifExistence = "SELECT username FROM user WHERE user_id = ?";
$stmt = $conn->prepare($sqlVerifExistence);
$stmt->bind_param("s", $_SESSION['user_id']);
$stmt->execute();
$stmt->store_result();

$stmt->bind_result($nomUtilisateur);
$stmt->fetch();
$stmt->close();

$cheminProfil = "images/photoprofil/" . $photoprofil;
?>
<div class="container">
      <a class ="titre">
        <p>Mon Profil :</p>
      </a>
      <a class ="titre1">
        <p><?php echo $nomUtilisateur; ?></p>
      </a>
      <img class="photoprofil" src="<?php echo $cheminProfil; ?>" alt="Avatar">
   
</div>
</body>
</html>