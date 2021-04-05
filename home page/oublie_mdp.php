<?php
    session_start();  // pour utiliser $_SESSION
    $bdd = new PDO("mysql:host=localhost;dbname=bdd_projet-l3an1", "root", "");
    
  //PARTIE 1: créer un code aléatoires à 5 chiffres, lorsque l'utilisateur entre l'adresse correcte qui existe déjà à BDD, il remplace le nouveau code.
  //Si l'utilisateur entre un compte qui n'existe pas ou qui n'a pas la syntaxe correcte, l'erreur sera signalée  
    
        if(isset($_GET['section'])){   //initialisation $_GET['section'], contrôle toujours ce qui rentre par l'url
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
                      $mailexist_count = $mailexist->rowCount();  //compter le nombre de rangées 

                      if($mailexist_count == 1){           //faire un test si un mail est déjà exist sur BDD? 
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
        //Redirection vers la page d'entrée du code $GET['section']='code' ??
        //Check code, si le code exact, redirection vers la page de changement de mdp change_mdp.php. $_GET['section']='mdpform'
        //Si les deux mots de passe correspondent -> hashage en sha1() et enregistrement dans la BDD
        //chú ý: href='...c_mdp.php?section=code&code=".$recup_code." ' nhờ đoạn code này mà sau khi click vào đường dẫn trong mail -> chuyển trực tiếp tới page change_mdp.php với email= email do người dùng nhập vào.

        $subject = "Récupération du mot de passe";
        $message = "Cliquez <a href='http://localhost/project_L3AN/page%20accueil/change_mdp.php?section=code&code=".$recup_code." '>ici</a> Voici le code pour la réinitialisation du mot de passe: $recup_code";         
        $header = "MIME-Version: 1.0\r\n"; 
        $header.= 'From: "nopreply@mazars.fr"<support@mazars.fr>'."\n"; 
        $header.= 'Content-type: text/html; cahset="uft-8"'."\n";
        $header.='Content-Transfer-Encoding: 8bit';

        $mail = mail($recup_mail, $subject, $message, $header);
        header("Location: http://localhost/project_L3AN/page%20accueil/change_mdp.php?section=code");   //il nous envoye le mail ET met tout de suite sur la page change_mdp pour mettre le code reçu.
 


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

        //PARTIE 3: Vérifier le code dans le champ.
        // Et vérifier 2 nouveaux mots de passe saisis par l'utilisateur.
  
    if(isset($_POST['verif_submit'],$_POST['verif_code'])){
     if(!empty($_POST['verif_code'])) {   //si le champ n'est pas vide.
      $verif_code = htmlspecialchars($_POST['verif_code']);   //
      $verif_req = $bdd->prepare('SELECT email FROM utilisateur WHERE email = ? AND code = ?');
      $verif_req->execute(array($_SESSION['recup_mail'],$verif_code));     //Vérifier si l'e-mail et le code sont correctement saisis.
      $verif_req = $verif_req->rowCount();
      if($verif_req == 1) {   //vérification.
         $up_req = $bdd->prepare('delete from utilisateur where email = ?');  //supprimer cet champ à la BDD??
         $up_req->execute(array($_SESSION['recup_mail']));
         header("Location: http://localhost/project_L3AN/page%20accueil/change_mdp.php?section=changemdp");    //->redirection à la page change_mdp.php pour changer le mot de passe.
      } else { 
         $error = "Code invalide";  //si l'utilisateur n'a pas saisi le bon code.
      }
   } else {
      $error = "Veuillez entrer votre code de confirmation";    //si le champ ne contient pas le code de confirmation->il nous donne $error.
   }
}
        if(isset($_POST['change_submit'])) {
   if(isset($_POST['change_mdp'],$_POST['change_mdpc'])) {
      $verif_confirme = $bdd->prepare('SELECT email FROM utilisateur WHERE email = ?');
      $verif_confirme->execute(array($_SESSION['recup_mail']));
      $verif_confirme = $verif_confirme->fetch();
      $verif_confirme = $verif_confirme['confirme'];
      if($verif_confirme == 1) {
         $mdp = htmlspecialchars($_POST['change_mdp']);
         $mdpc = htmlspecialchars($_POST['change_mdpc']);
         if(!empty($mdp) AND !empty($mdpc)) {
            if($mdp == $mdpc) {
               $mdp = sha1($mdp);
               $ins_mdp = $bdd->prepare('UPDATE utilisateur SET mot_de_passe = ? WHERE email = ?');
               var_dump($mdp,$_SESSION['recup_mail']);die;
               $ins_mdp->execute(array($mdp,$_SESSION['recup_mail']));
             // $del_req = $bdd->prepare('DELETE FROM utilisateur WHERE email = ?');
             // $del_req->execute(array($_SESSION['recup_mail']));
               header('Location:http://localhost/trang_chu/');
            } else {
               $error = "Vos mots de passes ne correspondent pas";
            }
         } else {
            $error = "Veuillez remplir tous les champs";
         }
      } 
   } 
 }


  
  
     //PARTIE 4: 
      

 ?>