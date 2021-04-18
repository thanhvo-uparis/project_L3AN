<?php
include '../connexion/bdd_connection.php';
      
    $query=$pdo->prepare('SELECT * FROM equipe INNER JOIN utilisateur ON equipe.email_utilisateur = utilisateur.email WHERE id_mission=?');
    $query->execute([$_POST['id_mission']]);
    $results=$query->fetchAll();

    $array = [$results];

    echo json_encode($array);
?>