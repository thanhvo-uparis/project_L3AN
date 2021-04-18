<?php 
/*
  Realise la connexion avec la base de donnees
*/
include '../connexion/bdd_connection.php';
//Verifie les donnees de session de l'utilisateur
if(isset($_SESSION['admin_email']) && $_SESSION['admin_email'] !=''){

$date = date('Y-m-d');//date actuel

    //Verifie si l'utilisateur a bien saisie les champs
    if(isset($_POST['textarea'])){
        $query=$pdo->prepare("insert into commentaires (commentaire, date_commentaire, id_controle, email_utilisateur) values (?,?,?,?)");//Ajout du commentaire
        $query->execute([$_POST['textarea'],$date,$_POST['id'],$_SESSION['admin_email']]);
    }
    exit();

}
else{
  header('Location:../connexion/login.php');//Redirection vers la page de connexion si l'utilisateur n'est pas connecte
} 


?>