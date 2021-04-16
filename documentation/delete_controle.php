<?php
/*
  Realise la connexion avec la base de donnees
*/
include 'application/bdd_connection.php';
//Verifie qu'il y a bien des valeurs dans le tableau des check box
if(isset($_POST['array_id'])){

    //Supprime les differents controles selectionnes dans la base de donnees
    $query=$pdo->prepare("DELETE FROM controle WHERE id in (".$_POST['array_id'].")");//supprime les différents controles séléctionner
    $query->execute();
}
?>