<?php
/*
  Realise la connexion avec la base de donnees
*/
include 'application/bdd_connection.php';
      
    //Modification du champ efficacite auquel l'id correspond
    $query=$pdo->prepare("update controle set efficacite = ? where id= ?");
    $query->execute([$_POST['value'], $_POST['id']]);

?>