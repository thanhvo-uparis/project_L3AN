<?php
/*
  Realise la connexion avec la base de donnees
*/
include '../connexion/bdd_connection.php';
//Supprime le commentaire que l'utilisateur a saisi   
$query=$pdo->prepare("delete from commentaires where id= ?");
$query->execute([$_POST['id']]);
?>