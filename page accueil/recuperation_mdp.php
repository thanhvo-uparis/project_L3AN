<?php
   $bdd = mysqli_connect("localhost", "root", "", "bdd_projet-l3an1");
   
?>
<!DOCTYPE html>
<html>
<head>
	<title>récupération du mot de passe </title>
</head>
<body>
	
		<h2>récupération du mot de passe</h2>
		<form method="post">
			<div>
				<label for="email"><a href="">Votre Email</a></label>
				<input type="email" name="recup_mail" placeholder="entrez votre adresse email">
				<button type="submit" value="Valider" name="recup_submit">Envoyer</button>
			</div>
            <?php if(isset($error)) { echo '<span style="color:red">'.$error.'</span>'; } else { echo ""; } ?>

		</form>


 <?php
    session_start();
 
if(isset($_POST['recup_submit'],$_POST['recup_mail'])) {
   
   if(!empty($_POST['recup_mail'])) {
      $recup_mail = htmlspecialchars($_POST['recup_mail']);
      if(filter_var($recup_mail,FILTER_VALIDATE_EMAIL)) {
         $mailexist = $bdd->prepare('SELECT email FROM utilisateur WHERE email = ?');
         $mailexist->execute(array($recup_mail));
         $mailexist_count = $mailexist->rowCount();

         if($mailexist == 1) {

            $_SESSION['recup_mail'] = $recup_mail;
            $recup_code = "";

            for($i=0; $i < 8; $i++){ 
               $recup_code .= mt_rand(0,9);
            }

            $mail_recup_exist = $bdd->prepare('SELECT email FROM utilisateur WHERE email = ?');
            $mail_recup_exist->execute(array($recup_mail));
            $mail_recup_exist = $mail_recup_exist->rowCount();

            if($mail_recup_exist == 1) {
               $recup_insert = $bdd->prepare('UPDATE utilisateur SET code = ? WHERE email = ?');
               $recup_insert->execute(array($recup_code,$recup_mail));
            } else {
               $recup_insert = $bdd->prepare('INSERT INTO utilisateur(email,code) VALUES (?, ?)');
               $recup_insert->execute(array($recup_mail,$recup_code));
            }
        }

    $mail = mail($_POST['recup_mail'], 'Gmail lai lai mat khau', 'code activation: $recup_code', 'From: share point2.0');
    if($mail){
    echo "thanh cong";
    }
    else{
    echo "error";
     }
	
</body>
</html>




   //methode: https://www.youtube.com/watch?v=geOKaFhaD-4
  /* B1: utilisation 'token', ajoute un colone 'token' dans un tableau 'utilisateur'
   B2: envoyer un mail 
   
  
  if(isset($_POST['email'])){
    $token = uniqid();
    $url = "http://localhost/project_L3AN/page%20accueil/token?token=$token";
    $subject = "Mot de passe oublié";
    $message = "Bonjour, voici votre lien pour la réinitialisation du mot de passe: $url";
    $header = "MIME-Version: 1.0\r\n"; 
$header.= 'From: "mazars.fr"<support@primfx.com>'."\n"; 
$header.= 'Content-type: text/html; cahset="uft-8"'."\n";
$header.='Content-Transfer-Encoding: 8bit';

    if(mail($_POST['email'], $subject, $message, $header)){
        $sql = "upload utilisateur SET token = ? WHERE email = ?";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([$token], $_POST['email']);
        echo "Mail envoyé";
    }
    else{
        echo "une erreur est survenue...";
    }
 }
    */

 /* methode: une test d'envoye plus simple.
 $mail = mail('thanhvo.uparis@gmail.com', 'Gmail lai lai mat khau', 'code activation: 12345', 'From: congvt.c32012@gmail.com');
 if($mail){
    echo "thanh cong";
 }
 else{
    echo "error";
 }
 */

 /* methode finale
     if(isset($_POST['verif_submit'],$_POST['verif_code'])) {
   if(!empty($_POST['verif_code'])) {
      $verif_code = htmlspecialchars($_POST['verif_code']);
      $verif_req = $bdd->prepare('SELECT id FROM recuperation WHERE mail = ? AND code = ?');
      $verif_req->execute(array($_SESSION['recup_mail'],$verif_code));
      $verif_req = $verif_req->rowCount();
      if($verif_req == 1) {
         $up_req = $bdd->prepare('UPDATE recuperation SET confirme = 1 WHERE mail = ?');
         $up_req->execute(array($_SESSION['recup_mail']));
         header('Location:http://127.0.0.1/path/recuperation.php?section=changemdp');
      } else {
         $error = "Code invalide";
      }
   } else {
      $error = "Veuillez entrer votre code de confirmation";
   }
}
if(isset($_POST['change_submit'])) {
   if(isset($_POST['change_mdp'],$_POST['change_mdpc'])) {
      $verif_confirme = $bdd->prepare('SELECT confirme FROM recuperation WHERE mail = ?');
      $verif_confirme->execute(array($_SESSION['recup_mail']));
      $verif_confirme = $verif_confirme->fetch();
      $verif_confirme = $verif_confirme['confirme'];
      if($verif_confirme == 1) {
         $mdp = htmlspecialchars($_POST['change_mdp']);
         $mdpc = htmlspecialchars($_POST['change_mdpc']);
         if(!empty($mdp) AND !empty($mdpc)) {
            if($mdp == $mdpc) {
               $mdp = sha1($mdp);
               $ins_mdp = $bdd->prepare('UPDATE membres SET motdepasse = ? WHERE mail = ?');
               $ins_mdp->execute(array($mdp,$_SESSION['recup_mail']));
              $del_req = $bdd->prepare('DELETE FROM recuperation WHERE mail = ?');
              $del_req->execute(array($_SESSION['recup_mail']));
               header('Location:http://127.0.0.1/path/connexion/');
            } else {
               $error = "Vos mots de passes ne correspondent pas";
            }
         } else {
            $error = "Veuillez remplir tous les champs";
         }
      } else {
         $error = "Veuillez valider votre mail grâce au code de vérification qui vous a été envoyé par mail";
      }
   } else {
      $error = "Veuillez remplir tous les champs";
   }
} 
*/
 ?>
 
