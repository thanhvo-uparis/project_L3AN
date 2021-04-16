<?php
include 'application/bdd_connection.php';
      
     
    $query=$pdo->prepare("update controle set statut = ? where id= ?");
    $query->execute([$_POST['value'], $_POST['id']]);

?>