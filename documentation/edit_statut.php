<?php
/*
  Realise la connexion avec la base de donnees
*/
include '../connexion/bdd_connection.php';
      
    //Modification du champ statut auquel l'id correspond
    $query=$pdo->prepare("update controle set statut = ? where id= ?");
    $query->execute([$_POST['value'], $_POST['id']]);

?>