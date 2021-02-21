<?php
   
   $u = $_POST['username'];
   $p = $_POST['password'];

   $db = mysqli_connect("localhost", "root", "", "mazarsproject");

   $sql = "select * from user where nom='$u' and motdepasse='$p'";

   $rs = mysqli_query($db, $sql);

   if(mysqli_num_rows($rs) > 0){
      header("Location: document.php");
   }
   else{
       
       echo "<h2>vous etes pas reussi à enregistrementt<h2>";
    
    } 
   
   
?>