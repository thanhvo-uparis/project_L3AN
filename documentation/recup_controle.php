<?php 
/*
  Realise la connexion avec la base de donnees
*/
include 'application/bdd_connection.php';
//Verifie les donnees de session de l'utilisateur
if(isset($_SESSION['admin_email']) && $_SESSION['admin_email'] !=''){

    //Verifie que qu'il y a bien une valeur pour 'id'
    if(isset($_POST['id'])){
        $query=$pdo->prepare("SELECT * FROM controle WHERE id = ?");//requete pour selectionner les controles avec cette ID.
        $query->execute([$_POST['id']]);
        $controle = $query->fetch();

        echo json_encode($controle);
    }
    exit();

}
else{
  header('Location:login.php');//Redirection vers la page de connexion si l'utilisateur n'est pas connecte 
} 


?>