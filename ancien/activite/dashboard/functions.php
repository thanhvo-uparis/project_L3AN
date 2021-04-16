<?php
function concernedByMission($pdo, $email)
{
    $query = $pdo->prepare("SELECT t1.mission_nom, t1.mission_id FROM mission as t1 
        INNER JOIN equipe as t2 ON t1.mission_id = t2.id_mission 
        WHERE t2.email_utilisateur =?");
    $query->execute([$email]);
    $results = $query->fetchAll();
    return $results;
}


function concernedByCategorie($pdo, $email)
{
    $query = $pdo->prepare("SELECT DISTINCT t1.nom_categorie, t1.id FROM categorie_general as t1 
        INNER JOIN categorie_mission as t2 on t1.id=t2.id_categorie 
        INNER JOIN equipe as t3 on t3.id_mission=t2.id_mission WHERE t3.email_utilisateur  = ? 
        ");
    $query->execute([$email]);
    $results = $query->fetchAll();
    return $results;
}