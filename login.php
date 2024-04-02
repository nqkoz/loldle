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
        
       
</header>
<main>   
    <div class="container">
        <p class="titre">Connexion</p>
        <form action="connexion.php" method="post">
            <label for="username">NOM D'UTILISATEUR</label><br>
            <input class="username" type="text" id="username" name="username" placeholder="Nom d'utilisateur"><br>
            <label for="password">MOT DE PASSE</label><br>
            <input class="mdp" type="password" id="password" name="password" placeholder="Mot de passe"><br>
            <div class="checkbox-container">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Rester connecté</label>
            </div>
            <input type="submit" value="Connexion">
        </form>
        <p class ="mdpoublie" ><a href="#">IMPOSSIBLE DE VOUS CONNECTER ?</a></p>
        <p class ="creercompte" ><a href="register.php">CRÉER UN COMPTE ?</a></p>
    </div>
</main>   
</body>

</html>