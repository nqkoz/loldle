<?php
function getAvailableChampions() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "loldle";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        return json_encode(array('error' => 'La connexion à la base de données a échoué : ' . $conn->connect_error));
    }

    $sqlChampions = "SELECT nomChampion,idChampion FROM champions";
    $result = $conn->query($sqlChampions);

    $champions = array();

    if ($result === false) {
        return json_encode(array('error' => 'Erreur lors de l\'exécution de la requête : ' . $conn->error));
    }

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $champion = array(
                'label' => $row['nomChampion'],
                'value' => $row['nomChampion'],
                'id' => $row['idChampion'],
                'image' => "images/champions/" . $row['nomChampion'] // Ajustez le chemin au besoin
            );
            $champions[] = $champion;
        }
    }
    $conn->close();

    return json_encode($champions);
}
echo getAvailableChampions()
?>

  