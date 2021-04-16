<?php
include 'application/bdd_connection.php';
if(isset($_POST['array_id'])){

    $query=$pdo->prepare("DELETE FROM controle WHERE id in (".$_POST['array_id'].")");
    $query->execute();
}

  


?>