<?php
/*
  Realise la connexion avec la base de donnees
*/
include 'application/bdd_connection.php';
      
    //Modification du champ design auquel l'id correspond
    $query=$pdo->prepare("update controle set design = ? where id= ?");
    $query->execute([$_POST['value'], $_POST['id']]);

?>