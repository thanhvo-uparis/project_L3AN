<?php
session_start();

//va deconnecter l'utilisateur
unset($_SESSION['admin_nom']);
unset($_SESSION['admin_prenom']);
unset($_SESSION['admin_email']);
unset($_SESSION['admin_privilege']);

header("Location:login.php");//redirection vers la page de connexion
?>