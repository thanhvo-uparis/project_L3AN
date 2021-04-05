<?php

    //connection a la base de donnée pou pouvoir saisir des requêtes.
    $connect = mysqli_connect("localhost", "root", "", "bdd_projet-l3an1");

    $cat= "select * from categorie";
    $listecat = mysqli_query($connect, $cat);

    if(isset($_POST["supprimer"])){
            $nom_categorie = $_POST["categorie"];
            
            $sql = "delete from categorie where categorie = '$nom_categorie'";
            
            mysqli_query($connect, $sql);
        }
     mysqli_close($connect);
    header("Location : document.php")
?>
<!DOCTYPE html>
<html>
  <head>
    <title>formulaire pour la bdd</title>
    <link rel="stylesheet" type="text/css" href="#" />
    <meta charset="utf-8">
  </head>
  <body>
    <form action="sup_cat.php" method="post">
                <label for="categorie">Nom de la categorie :</label>
                <input type="text" class="form-group" id="categorie" name="categorie"/>
        
         <input type="submit" name="supprimer" class="btn btn-primary"/>
      </form>
    </body>
</html>