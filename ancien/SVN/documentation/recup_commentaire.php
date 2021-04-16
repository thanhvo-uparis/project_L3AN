<?php 
include 'application/bdd_connection.php';
if(isset($_SESSION['admin_email']) && $_SESSION['admin_email'] !=''){

    if(isset($_POST['id'])){
        $query=$pdo->prepare("SELECT * FROM commentaires INNER JOIN utilisateur ON commentaires.email_utilisateur= utilisateur.email WHERE id_controle = ?");
        $query->execute([$_POST['id']]);
        $commentaires = $query->fetchAll();

        echo json_encode($commentaires);
    }
    exit();

}
else{
  header('Location:login.php');
} 


?>