//tu viet
<!DOCTYPE html>
<html>
<head>
	<title>Changement du mot de passe </title>
</head>
<body>
	   <? php
	      include('oublie_mdp.php');
	   ?>
		<h2>Changement du mot de passe</h2>
		<form method="post" action="">
			<div>
				<label for="email"><a href="">Utilisateur: $_POST['recup_mail']</a></label>
				<br>
				<label for="email"><a href="">Nouveau mot de passe </a></label>
				<input type="email" name="old_password" placeholder="Entrez un nouveau mot de passe">
            <br>
				<label for="email"><a href="">Confirmer le mot de passe </a></label>
				<input type="email" name="new_password" placeholder="Confirmation du mot de passe">

				<button type="submit" value="Valider" name="changemdp">Envoyer</button>
			</div>
            <?php if(isset($error)) { echo '<span style="color:red">'.$error.'</span>'; } else { echo ""; } ?>

		</form>	
        

<? php 
      $bdd = new PDO("mysql:host=localhost;dbname=bdd_projet-l3an1", "root", "");
      if(isset($_POST['old_password']) && !empty($_POST['old_password'])) && isset($_POST['new_password']) && !empty($_POST['new_password']){

      	$userResult = mysql_query($bdd, "select * form utilisateur")

      }
 ?>
</body>
</html>