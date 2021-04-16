<?php
include 'application/bdd_connection.php';
      
     
    $query=$pdo->prepare("update controle set niveau_de_risque = ? where id= ?");
    $query->execute([$_POST['value'], $_POST['id']]);

?>