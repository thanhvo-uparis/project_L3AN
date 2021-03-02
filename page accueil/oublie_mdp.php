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
				<input type="email" name="email" placeholder="entrez votre adresse email">
				<button type="submit">Envoyer</button>
			</div>
		</form>
	
</body>
</html>

<?php
  if(isset($_POST['email'])){
    $token = uniqid();
    $url = "http://localhost/project_L3AN/page%20accueil/token?token=$token";
    $message = "Bonjour, voici votre lien pour la réinitialisation du mot de passe: $url";
    $headers =  'MIME-Version: 1.0' . "\r\n"; 
$headers .= 'From: congvt.c32012@gmail.com' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n"; 

    if($senmail=mail($_POST['email'], 'Mot de passe oublié', $message, $headers)){
        $sql = "upload utilisateur SET token = ? WHERE email = ?";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([$token, $_POST['email']]);
        echo "Mail envoyé";
    }
    else{
        echo "une erreur est survenue...";
    }
 }
 ?>
