<?php
/*
  Realise la connexion avec la base de donnees
*/
include '../connexion/bdd_connection.php';
     
    //Recupere les categories propres a la mission
    $query=$pdo->prepare("SELECT * FROM categorie_mission INNER JOIN categorie_general ON categorie_mission.id_categorie = categorie_general.id WHERE id_mission=?");
    $query->execute([$_POST['id_mission']]);
    $results=$query->fetchAll();

    //Recupere dans la table equipe, les gens qui font parties de la mission ayant le role = 'Junior'
    $query=$pdo->prepare("SELECT * FROM equipe INNER JOIN utilisateur ON equipe.email_utilisateur = utilisateur.email WHERE id_mission=? AND role = 'Junior'");
    $query->execute([$_POST['id_mission']]);
    $juniors=$query->fetchAll();

    //Recupere dans la table equipe, les gens qui font parties de la mission ayant le role = 'Senior'
    $query=$pdo->prepare("SELECT * FROM equipe INNER JOIN utilisateur ON equipe.email_utilisateur = utilisateur.email WHERE id_mission=? AND role = 'Senior'");
    $query->execute([$_POST['id_mission']]);
    $seniors=$query->fetchAll();

    //Recupere dans la table equipe, les gens qui font parties de la mission ayant le role = 'Senior Manager' ou 'Associe'
    $query=$pdo->prepare("SELECT * FROM equipe INNER JOIN utilisateur ON equipe.email_utilisateur = utilisateur.email WHERE id_mission=? AND role = 'Associé' OR role='Senior Manager'");
    $query->execute([$_POST['id_mission']]);
    $signoff=$query->fetchAll();

    //Stock les variables dans un tableau
    $array = [$results,$juniors,$seniors,$signoff];

    echo json_encode($array);
?>