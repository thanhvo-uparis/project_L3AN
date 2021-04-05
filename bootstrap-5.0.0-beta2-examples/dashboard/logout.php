<?php
session_start();
//session_destroy();

unset($_SESSION['admin_nom']);
unset($_SESSION['admin_prenom']);
unset($_SESSION['admin_email']);
unset($_SESSION['admin_privilege']);

header("Location:login.php");
?>