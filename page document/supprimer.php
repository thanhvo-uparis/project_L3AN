<?php

require_once'document.php';
$connect = mysqli_connect("localhost", "root", "", "bdd_projet-l3an1");

$id = $_GET['idl'];

if(isset($_GET[$id])){
  echo "il n'a pas saisi l'id." ;  
}else{
    echo " ";
}

$sql = "delete from control where id = $id";

    if($connect ->query($sql) === TRUE) {
        echo " ";
    } else {
        echo "Error: "  .$sql . "<br>" . $connect -> error;
    }

    $connect -> close();
?>


