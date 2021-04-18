<?php
/*
  Realise la connexion avec la base de donnees
*/
include '../connexion/bdd_connection.php';
      
    //Modification du champ niveau de risque auquel l'id correspond
    $query=$pdo->prepare("update controle set niveau_de_risque = ? where id= ?");
    $query->execute([$_POST['value'], $_POST['id']]);

?>