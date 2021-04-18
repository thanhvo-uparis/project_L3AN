<?php 
/*
  Realise la connexion avec la base de donnees
*/
include '../connexion/bdd_connection.php';
//Verifie les donnes de session de l'utilisateur pour vérifier ca connexion
if(isset($_SESSION['admin_email']) && $_SESSION['admin_email'] !=''){

    //Verifie que l'utilisateur a bien saisie un ID
    if(isset($_POST['id'])){
        $query=$pdo->prepare("SELECT * FROM commentaires INNER JOIN utilisateur ON commentaires.email_utilisateur= utilisateur.email WHERE id_controle = ?");
        $query->execute([$_POST['id']]);
        $commentaires = $query->fetchAll();

        echo json_encode($commentaires);
    }
    exit();

}
else{
  header('Location:../connexion/login.php');//Redirection si il n'est pas connecte 
} 


?>