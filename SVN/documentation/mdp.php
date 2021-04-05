<?php

$pass = "mdp";
$newpass = md5(md5(md5(md5($pass))));
echo $newpass;

?>