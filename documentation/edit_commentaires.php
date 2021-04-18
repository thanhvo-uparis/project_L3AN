<?php
/*
  Realise la connexion avec la base de donnees
*/
include '../connexion/bdd_connection.php';
      
    //Permet de modifier un commentaire
    $query=$pdo->prepare("update commentaires set commentaire = ? where id= ?");
    $query->execute([$_POST['textarea'], $_POST['id']]);
?>