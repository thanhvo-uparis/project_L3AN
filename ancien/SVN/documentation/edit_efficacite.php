<?php
include 'application/bdd_connection.php';
      
     
    $query=$pdo->prepare("update controle set efficacite = ? where id= ?");
    $query->execute([$_POST['value'], $_POST['id']]);

?>