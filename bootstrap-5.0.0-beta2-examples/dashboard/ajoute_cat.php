<?php
    $servername = "localhost";
        $username = "root";
        $mdp = "";
        $dbname = "bdd_projet-l3an1";
        
        //connection a la base de donnée pou pouvoir saisir des requêtes.
        $connect = mysqli_connect("localhost", "root", "", "bdd_projet-l3an1");
        
        
        //vat permettre de faire la liste déroulante a partir des différentes catégorie.
        $cat= "select * from categorie";
        $listecat = mysqli_query($connect, $cat);//execution de la requete.
        
        //permet d'ajouter la liste des différents champs dans la base de donnée.
        if(isset($_POST["ajouter"])){
            $nom_categorie = $_POST["categorie"];
            
            $sql = "insert into categorie (categorie) values ('$nom_categorie')";
            
            mysqli_query($connect, $sql);
        }
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
      
    <form action="ajout_cat.php" method="post">
                <label for="categorie">Nom de la categorie :</label>
                <input type="text" class="form-group" id="categorie" name="categorie"/>
        
         <input type="submit" name="ajouter" class="btn btn-primary"/>
      </form>
    </body>
</html>