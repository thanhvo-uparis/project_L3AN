<?php

        $servername = "localhost";
        $username = "root";
        $mdp = "";
        $dbname = "bdd_projet-l3an1";
        
        //connection a la base de donnée pou pouvoir saisir des requêtes.
        $connect = mysqli_connect("localhost", "root", "", "bdd_projet-l3an1");
        
        //selection des champs qui nous interesse dans la table controle.
        $query = "select * from controle";
        $result = mysqli_query($connect, $query);//execution de la requete.
        
        //vat permettre de faire la liste déroulante a partir des différentes catégorie.
        $cat= "select * from categorie";
        $listecat = mysqli_query($connect, $cat);//execution de la requete.
        
        //va permettre de saisir les différentes personnes travaillant sur le dossier avec leurs roles correspondants au champs.
        $menu_realite_par = "select * from utilisateur where role_mission = 'junior' order by nom ";
        $liste_rea_p = mysqli_query($connect, $menu_realite_par);
        
        //va permettre de saisir les différentes personnes travaillant sur le dossier avec leurs roles correspondants au champs.
        $menu_revu_par = "select * from utilisateur where role_mission = 'senior' order by nom ";
        $liste_rev_p = mysqli_query($connect, $menu_revu_par);
        
        
        $menu_sign_off = "select * from utilisateur where role_mission = 'Senior Manager' or 'Associé' order by nom ";
        $liste_so = mysqli_query($connect, $menu_sign_off);
        
        //permet d'ajouter la liste des différents champs dans la base de donnée.
        if(isset($_POST["ajouter"])){
            $nom_control = $_POST["nom_du_controle"];
            $deadline = $_POST["deadline"];
            $realise_par = $_POST["email_utilisateur_realise_par"];
            $revu_par = $_POST["email_utilisateur_revu_par"];
            $sign_off_par =$_POST["email_utilisateur_sign_off"];
            $statut = $_POST["statut"];
            $niv_risque = $_POST["niveau_de_risque"];
            $design = $_POST["design"];
            $efficacite = $_POST["efficacite"];
            $categorie = $_POST["categorie"];
            
            $sql = "insert into controle(nom_du_controle, deadline, email_utilisateur_realise_par, email_utilisateur_revu_par, email_utilisateur_sign_off, statut, niveau_de_risque, design, efficacite, categorie ) values ('$nom_control', '$deadline', '$realise_par','$revu_par', '$sign_off_par', '$statut',  '$niv_risque', '$design', '$efficacite', '$categorie')";
            
            mysqli_query($connect, $sql);
        }
     mysqli_close($connect);
    header("loaction : document.php");
?>
<!DOCTYPE html>
<html>
  <head>
    <title>formulaire pour la bdd</title>
    <link rel="stylesheet" type="text/css" href="#" />
    <meta charset="utf-8">
  </head>
  <body>
              <h1>Veuillez remplir les champs suivants :</h1>
            <form action="form_doc.php" method="post">
                <label for="nom_du_controle">Nom du controle :</label>
                <input type="text" class="form-group" id="nom_du_controle" name="nom_du_controle"/>
            <br>
             
                <label for="deadline">Deadline :</label>
                <input type="date" class="form-control" id="deadline" name="deadline"/>
            <br>
                
                <label for="email_utilisateur_realise_par">Réalisé par :</label>
                <select name="email_utilisateur_realise_par">
                    <option value="choix ?"> </option>
                    <?php while($row = mysqli_fetch_array($liste_rea_p)): ?>
                    <option value="<?php echo $row[0]; ?>"><?php echo $row[1]." ".$row[2];?></option>
                    <?php endwhile; ?>
                </select>
            <br>
                
                <label for="email_utilisateur_revu_par">Revu par :</label>
                <select name="email_utilisateur_revu_par">
                    <option value="choix ?"> </option>
                    <?php while($row = mysqli_fetch_array($liste_rev_p)): ?>
                    <option value="<?php echo $row[0]; ?>"><?php echo $row[1]." ".$row[2];?></option>
                    <?php endwhile; ?>
                </select>
            <br>
                
                <label for="email_utilisateur_sign_off">sign-off par :</label>
                <select name="email_utilisateur_sign_off">
                    <option value="choix ?"> </option>
                    <?php while($row = mysqli_fetch_array($liste_so)): ?>
                    <option value="<?php echo $row[0]; ?>"><?php echo $row[1]." ".$row[2];?></option>
                    <?php endwhile; ?>
                </select>
            <br>
                
              
                <label for="statut">Statut :</label>
                <select class="form-control" id="statut" name="statut">
                        <option value="choix ?"></option>
                        <option value="Non debute">Non debute</option>
                        <option value="Documente">Documente</option>
                        <option value="Revu">Revu</option>
                        <option value="Sign-off">Sign-off</option>
                </select>
            <br>
                
              
                <label for="niveau_de_risque">Niveau de risque</label>
                <select name="niveau_de_risque">
                            <option value="choix ?"></option>
                            <option value="Eleve">Eleve</option>
                            <option value="Moyen">Moyen</option>
                            <option value="Faible">Faible</option>
                </select>
            <br>
                
             
               
                    <label for="design">Design :</label>
                 <select class="form-control" id="design"  name="design">
                            <option value="choix ?"></option>
                            <option value="Effectif">Effectif</option>
                            <option value="Remarque majeur">Remarque majeur</option>
                            <option value="Remarque mineur">Remarque mineur</option>
                            <option value="Non-effectif">Non-effectif</option>
                        </select>
            <br>
               
               
                <label for="efficacite">Efficacité :</label>
                 <select class="form-control" id="efficacite"  name="efficacite">
                            <option value="choix ?"></option>
                            <option value="Effectif">Effectif</option>
                            <option value="Remarque majeur">Remarque majeur</option>
                            <option value="Remarque mineur">Remarque mineur</option>
                            <option value="Non-effectif">Non-effectif</option>
                        </select>
            <br>
               
                <label for="categorie">Categorie :</label>
                <select name="categorie">
                    <option value="choix ?"> </option>
                    <?php while($row = mysqli_fetch_array($listecat)): ?>
                    <option value="<?php echo $row[0]; ?>"><?php echo $row[1]; ?></option>
                    <?php endwhile; ?>
                </select>
            <br>
             
              <input type="submit" name="ajouter" class="btn btn-primary"/>
            </form>
    </body>
</html>