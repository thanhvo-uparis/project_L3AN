<?php
 $bdd = mysqli_connect("localhost", "root", "", "bdd_projet-l3an1");

   
 if(isset($_GET['token']) && $_GET['token'] != ''){

     $stmt = $bdd->prepare('select email from utilisateur where token = ?');
     $stmt->execute([$_GET['token']]);
     $email = $stmt->fetchColumn();
     

     if($email){
     	?>

     	  <!DOCTYPE html>
        <html>
        <head>
	        <title>récupération du mot de passe </title>
        </head>
          <body>
          	<form method="post">
          		<label for="newPassword">Nouveau mot de passe</label>
          		<input type="password" name="newPassword">
          		<input type="submit" value="Confirmer">
          		
          	</form>
	
	
          </body>
        </html>
        <?php

     }
   } 
   if(isset($_POST['newPassword'])){
   	$hashedPassword = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);
   	$sql = "upload utilisateur SET mot_de_passe = ?, token = NULL where email= ?";
   	$stmt = $bdd ->prepare($sql);
   	$stmt->execute([$hashedPassword, $email]);
   	echo "Mot de passe modifié avec succés";
   }


