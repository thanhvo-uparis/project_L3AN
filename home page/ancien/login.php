<?php
   
   $u = $_POST['email'];
   $p = $_POST['mot_de_passe'];

   $bdd = mysqli_connect("localhost", "root", "", "bdd_projet-l3an1");

   $sql = "select * from utilisateur where email='$u' and mot_de_passe='$p'";

   $rs = mysqli_query($bdd, $sql);

   if(mysqli_num_rows($rs) > 0){
      header("Location: document.php");
   }
   else{
    
    } 
   
   
?>