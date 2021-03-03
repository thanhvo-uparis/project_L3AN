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


 

	
</body>
</html>
 <?php
 $mail = mail('', 'Gmail lai lai mat khau', 'code activation: 12348, 'From: congvt.c32012@gmail.com');
    if($mail){
    echo "thanh cong";
    }
    else{
    echo "error";
     }

 ?>
   
    $mail = mail('', 'Gmail lai lai mat khau', 'code activation: 12348, 'From: congvt.c32012@gmail.com');
    if($mail){
    echo "thanh cong";
    }
    else{
    echo "error";
     }

 ?>