<?php
   echo "okkk";

  /*if(isset($_POST['verif_submit'],$_POST['verif_code'])){
     if(!empty($_POST['verif_code'])) {   //si le champ n'est pas vide.
      $verif_code = htmlspecialchars($_POST['verif_code']);   //
      $verif_req = $bdd->prepare('SELECT email FROM utilisateur WHERE email = ? AND code = ?');
      $verif_req->execute(array($_SESSION['recup_mail'],$verif_code));     //Vérifier si l'e-mail et le code sont correctement saisis.
      $verif_req = $verif_req->rowCount();
      if($verif_req == 1) {   //vérification.
         $up_req = $bdd->prepare('delete from utilisateur where gmail = ?');  //supprimer cet champ à la BDD??
         $up_req->execute(array($_SESSION['recup_mail']));
         header("Location: http://localhost/project_L3AN/page%20accueil/change_mdp.php?section=changemdp");    //->redirection à la page change_mdp.php pour changer le mot de passe.
      } else { 
         $error = "Code invalide";  //si l'utilisateur n'a pas saisi le bon code.
      }
   } else {
      $error = "Veuillez entrer votre code de confirmation";    //si le champ ne contient pas le code de confirmation->il nous donne $error.
   }
}
*/
if(isset($_POST['verif_submit'],$_POST['verif_code'])) {
   header('Location: http://localhost/project_L3AN/page%20accueil/acceuil.php ');

                 if(!empty($_POST['verif_code'])) {

                    $verif_code = htmlspecialchars($_POST['verif_code']);
                    header('Location: http://localhost/project_L3AN/page%20accueil/change_mdp.php?section=changemdp ');
                    
                }else{
                    $error = "Veuillez entrer votre adresse mail";
                }
        }
?>