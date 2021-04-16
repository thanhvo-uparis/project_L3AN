<?php
include 'application/bdd_connection.php';
      
    $query=$pdo->prepare("SELECT * FROM categorie_mission INNER JOIN categorie_general ON categorie_mission.id_categorie = categorie_general.id WHERE id_mission=?");
    $query->execute([$_POST['id_mission']]);
    $results=$query->fetchAll();

    $query=$pdo->prepare("SELECT * FROM equipe INNER JOIN utilisateur ON equipe.email_utilisateur = utilisateur.email WHERE id_mission=? AND role = 'Junior'");
    $query->execute([$_POST['id_mission']]);
    $juniors=$query->fetchAll();

    $query=$pdo->prepare("SELECT * FROM equipe INNER JOIN utilisateur ON equipe.email_utilisateur = utilisateur.email WHERE id_mission=? AND role = 'Senior'");
    $query->execute([$_POST['id_mission']]);
    $seniors=$query->fetchAll();

    $query=$pdo->prepare("SELECT * FROM equipe INNER JOIN utilisateur ON equipe.email_utilisateur = utilisateur.email WHERE id_mission=? AND role = 'Associé' OR role='Senior Manager'");
    $query->execute([$_POST['id_mission']]);
    $signoff=$query->fetchAll();

    $array = [$results,$juniors,$seniors,$signoff];

    echo json_encode($array);
?>