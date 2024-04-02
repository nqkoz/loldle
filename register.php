<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loldle</title>
    <link rel="stylesheet" href="styles2.css">
</head>
<body class="backImageBody">
<header>
        <a href="accueil.php">
            <img class = "accueil" src="./images/logoclean.png">
        </a>
<
</header>
<main>
    <div class="container">
        <p class="titre">Inscription</p>
        <form action="inscription.php" method="post">
            <label for="username">NOM D'UTILISATEUR</label><br>
            <input class="username" type="text" id="username" name="username" placeholder="Nom d'utilisateur" onfocus="clearPlaceholder(this)"><br>
            <label for="email">ADRESSE MAIL</label><br>
            <input class="mdp" type="text" id="email" name="email" placeholder="Adresse mail" onfocus="clearPlaceholder(this)"><br>
            <label for="password">MOT DE PASSE</label><br>
            <input class="mdp" type="password" id="password" name="password" placeholder="Mot de passe" onfocus="clearPlaceholder(this)"><br>
            
            <input type="submit" value="Inscription">
        </form>
        <p class ="connectercompte" ><a href="login.php">Vous avez déjà un compte ?</a></p>
    </div>
</main>    
</body>
</html>