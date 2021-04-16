<?php 
include 'application/bdd_connection.php';
if(isset($_SESSION['admin_email']) && $_SESSION['admin_email'] !=''){

    if(isset($_POST['id'])){
        $query=$pdo->prepare("SELECT * FROM controle WHERE id = ?");
        $query->execute([$_POST['id']]);
        $controle = $query->fetch();

        echo json_encode($controle);
    }
    exit();

}
else{
  header('Location:login.php');
} 


?>