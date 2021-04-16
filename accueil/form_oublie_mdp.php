<html lang="fr">
<head>
	<title>recupération la mot de passe</title>
</head>
<body>
	    <?php
	    require_once ('oublie_mdp.php');
	    ?>
		<h2>Récupération la mot de passe</h2>
		<form method="post" >
			<div>
				<label for="email">Email</label>
				<input type="email" name="recup_mail" placeholder="Saissiez votre la mot de passe">
				<button type="submit" value="Valider" name="recup_submit">Envoyer</button>
			</div>
            <?php if(isset($error)) { echo '<span style="color:red">'.$error.'</span>'; } else { echo ""; } ?>

		</form>	      
</body>
</html>