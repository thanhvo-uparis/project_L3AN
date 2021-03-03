<?php
    session_start();  // pour utiliser $_SESSION
    $bdd = new PDO("mysql:host=localhost;dbname=bdd_projet-l3an1", "root", ""); //Su dung cette requette thay vi requette nay: $bdd = mysqli_connect("localhost", "root", "", "bdd_projet-l3an1");
    
  //PARTIE 1: créer un code aléatoires à 5 chiffres, lorsque l'utilisateur entre l'adresse correcte qui existe déjà à BDD, il remplace le nouveau code.
  //Si l'utilisateur entre un compte qui n'existe pas ou qui n'a pas la syntaxe correcte, l'erreur sera signalée  
    
        if(isset($_GET['section'])){
          $section =htmlspecialchars($_GET['section']);
        }else{
          $section ="";
        }
    
        if(isset($_POST['recup_submit'],$_POST['recup_mail'])) {
                 if(!empty($_POST['recup_mail'])) {
                    $recup_mail = htmlspecialchars($_POST['recup_mail']);
                    if(filter_var($recup_mail,FILTER_VALIDATE_EMAIL)) {
                      $mailexist = $bdd->prepare('select email from utilisateur where email=?');  //vérifier que l'utilisateur existe si un adresse email exist
                      $mailexist->execute(array($recup_mail));  //mail que on a reçu
                      $mailexist = $mailexist->rowCount();  //compter le nombre de rangées 

                      if($mailexist == 1){           //faire un test si un mail est déjà exist sur BDD? 
                         $_SESSION['recup_mail'] = $recup_mail;  //stockage du mail dans $_SESSION['recup_mail']

                           //fonction: créer automatique un code aléatoires
                            $chars = '0123456789';
                            $recup_code = '';   //créer une variable vide
                            for($i=0; $i<8; $i++){
                                 $recup_code .= $chars[rand(0, strlen($chars)-1)];
                            }
                      

                        $mail_recup_exist = $bdd->prepare('SELECT email FROM utilisateur WHERE email = ?'); //vérifier si un mail est déjà à l'intérieur à BDD
                        $mail_recup_exist->execute(array($recup_mail));
                        $mail_recup_exist = $mail_recup_exist->rowCount();

                        //vérifier si un mail exist ou non? si oui:?  si non: ? 
                        if($mail_recup_exist == 1) {    
                             $recup_insert = $bdd->prepare('UPDATE utilisateur SET code = ? WHERE email = ?');   //si oui: faire mise à jour le code
                             $recup_insert->execute(array($recup_code,$recup_mail));
                        } else {
                              $recup_insert = $bdd->prepare('INSERT INTO utilisateur(code) VALUES (?) where email=$recup_code');   //si non: cet email n'existe pas à BDD ->donne erreur ou bien arrive à la page d'inscription etc
                              $recup_insert->execute(array($recup_code));
                          /*   $recup_insert = $bdd->prepare('INSERT INTO utilisateur(email,code) VALUES (?, ?)'); //si cet email n'existe pas à BDD, insérer une nouvelle entrée dans tableau 'utilisateur'
                              $recup_insert->execute(array($recup_mail,$recup_code));
                            */
                        }

      //PARTIE 2: Envoie d'un code par mail
        $subject = "Récupération du mot de passe";
        $message = "Voici le code pour la réinitialisation du mot de passe: $recup_code";
        $header = "MIME-Version: 1.0\r\n"; 
        $header.= 'From: "nopreply@mazars.fr"<support@mazars.fr>'."\n"; 
        $header.= 'Content-type: text/html; cahset="uft-8"'."\n";
        $header.='Content-Transfer-Encoding: 8bit';

        $mail = mail($recup_mail, $subject, $message, $header);
        /* header("Location: http://localhost/project_L3AN/page%20accueil/oublie_mdp.php?section=code"); */
 


                      }else{
                        $error = "Cette adresse mail n'est pas encore enregistrée";
                      }

                    }else{
                        $error = "Adresse mail invalide";
                    }
                }else{
                    $error = "Veuillez entrer votre adresse mail";
                }
        }

        //PARTIE 3:
    if(isset($_POST['verif_submit'],$_POST['verif_code'])) {
     if(!empty($_POST['verif_code'])) {
      $verif_code = htmlspecialchars($_POST['verif_code']);
      $verif_req = $bdd->prepare('SELECT email FROM utilisateur WHERE email = ? AND code = ?');
      $verif_req->execute(array($_SESSION['recup_mail'],$verif_code));
      $verif_req = $verif_req->rowCount();

      if($verif_req == 1) {
         $up_req = $bdd->prepare('delete from utilisateur where gmail = ?');
         $up_req->execute(array($_SESSION['recup_mail']));
         header('Location: http://localhost/project_L3AN/page%20accueil/change_mdp.php');
      } else { 
         $error = "Code invalide";
      }
   } else {
      $error = "Veuillez entrer votre code de confirmation";
   }
}

         require('form_oublie_mdp.php');

     


 ?>


