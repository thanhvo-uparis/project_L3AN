<?php

   include('oublie_mdp.php');
    ?>

      <div class="" >
	<div class="">
		<ol class="">
			<li><span>Vous êtes ici:</span><a href="">Acceuil</a></li>
			<li><a href="oublie_mdp.php">Récupération de mot de passe</a></li>
		</ol>
		<span></span>
	</div>
	

	   <h4 class="title-element">Récupération de mot de passe</h4>
<?php if($section == 'code') { ?>

Un code de vérification vous a été envoyé par mail: <?= $_SESSION['recup_mail'] ?>
<br /><br />
<form method="post" class="">

   <input type="text" placeholder="Code de vérification" name="verif_code"/><br/>   <!-- Saisir le code de vérification qui a reçu par mail. -->
   <button type="submit" value="Valider" name="verif_submit">valider</button>


 </form>  

<?php } else if($section == "changemdp") { ?>

Nouveau mot de passe pour <?= $_SESSION['recup_mail'] ?><br>
<form method="post">
   <input type="password" placeholder="Nouveau mot de passe" name="change_mdp"/><br/>        <!-- Saisir un nouveau mot de passe -->
   <input type="password" placeholder="Confirmation du mot de passe" name="change_mdpc"/><br/>   <!-- Confirmer un nouveau mot de passe -->
   <button type="submit" value="Valider" name="change_submit">valider</button>
</form>

<?php } ?>

<?php if(isset($error)) { echo '<span style="color:red">'.$error.'</span>'; } else { echo ""; } ?>
