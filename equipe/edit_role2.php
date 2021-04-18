<?php
include '../connexion/bdd_connection.php';
     
    $query=$pdo->prepare("update equipe set role = ? where id= ?");//modifie le role d'une personnee dans la mission
    $query->execute([$_POST['value'], $_POST['id']]);

?>