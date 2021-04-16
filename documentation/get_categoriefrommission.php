<?php
/*
  Realise la connexion avec la base de donnees
*/
include 'application/bdd_connection.php';
      
    //Recupere les categories propres a la mission
    $query=$pdo->prepare("SELECT * FROM categorie_mission INNER JOIN categorie_general ON categorie_mission.id_categorie = categorie_general.id WHERE id_mission=?");
    $query->execute([$_POST['id_mission']]);
    $results=$query->fetchAll();

    echo json_encode($results);
?>