<?php
   
   $u = $_POST['username'];
   $p = $_POST['password'];

   $db = mysqli_connect("localhost", "root", "", "mazarsproject");

   $sql = "select * from user where nom='$u' and motdepasse='$p'";

   $rs = mysqli_query($db, $sql);

   if(mysqli_num_rows($rs) > 0){
      header("Location: contact.php");
   }
   else{
       
       echo "<h2>Le nom d'utilisateur ou le mot de passe est incorrect<h2>";
    
    } 
   
   
?>