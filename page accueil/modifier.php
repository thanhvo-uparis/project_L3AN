<?php 
require_once'document.php';

$serveur = "localhost";
$user = "root";
$password = "";
$bdd = "bdd_projet-l3an1";

$conn = mysqli_connect($serveur, $user, $password, $bdd);

//recuperer les données de l'ancien contrôle.
if(isset($_GET['idl'])){
    $id=$_GET['idl'];
    $sql = "select * from tableau_doc where id = $id";
    $result = mysqli_query($conn, $sql);
    
    $row=mysqli_fetch_assoc($result);
    $nom_control=$row["nom_du_controle"];
    $deadline=$row["deadline"];
    $realise_par=$row["realise_par"];
    $statut=$row["statut"];
    $niv_risque=$row["niveau_de_risque"];
    $design=$row["design"];
    $efficacite=$row["efficacite"];
    $categorie=$row["categorie"]; 
}

//permet d'ajouter les nouvelles modifications
if(isset($_POST["modifie"])){
    $nom_control = $_POST["nom_du_controle"];
    $deadline = $_POST["deadline"];
    $realise_par = $_POST["realise_par"];
    $statut = $_POST["statut"];
    $niv_risque = $_POST["niveau_de_risque"];
    $design = $_POST["design"];
    $efficacite = $_POST["efficacite"];
    $categorie = $_POST["categorie"];
    
    $conn = mysqli_connect($serveur, $user, $password, $bdd);
    
    $qr = mysqli_query($conn,"update tableau_doc set nom_du_controle = '{$nom_control}',deadline = '{$deadline}',realise_par = '{$realise_par}', statut = '{$statut}',niveau_de_risque = '{$niv_risque}',design = '{$design}',efficacite = '{$efficacite}',categorie= '{$categorie}' where id = ");
    
    if($qr){
     header("Location:document.php");   
    }
}  

?>

<!DOCTYPE html>
<html>
  <head>
    <title>formulaire pour la bdd</title>
    <link rel="stylesheet" type="text/css" href="#" />
    <meta charset="utf-8">
  </head>
  <body>
    <div class="container">
      <div class="row col-md-6 col-md-offset-3">
        <div class="panel panel-primary">
            <div class="formulaire">
          <div class="panel-body">
              <h1>Veuillez remplir les champs suivants :</h1>
            <form action="modifier.php" method="post">
                
            <div class="form"> 
              <div class="form-group">
                <label for="nom_du_controle">Nom du controle :</label>
                <input type="text" class="form-group" id="nom_du_controle" name="nom_du_controle" value="<?php echo $nom_control; ?>"/>
              </div>
                
              <div class="form-group">
                <label for="deadline">Deadline</label>
                <input type="date" class="form-control" id="deadline" name="deadline" value="<?php echo $deadline; ?>"/>
              </div>
              <div class="form-group">
                <label for="realise_par">Realise par</label>
                <input type="text" class="form-control" id="realise_par" name="realise_par" value="<?php echo $realise_par; ?>"/>
             </div>
              <div class="form-group">
                <label for="statut">statut</label>
                <select class="form-control" id="statut" name="statut" >
                        <option value="choix ?"><?php echo $statut; ?></option>
                        <option value="Non debute">Non debute</option>
                        <option value="Documente">Documente</option>
                        <option value="Revu">Revu</option>
                        <option value="Sign-off">Sign-off</option>
                </select>
              </div>
              <div class="form-group">
                <label for="niveau_de_risque">Niveau de risque</label>
                <select name="niveau_de_risque" >
                            <option value="choix ?"><?php echo $niv_risque; ?></option>
                            <option value="Eleve">Eleve</option>
                            <option value="Moyen">Moyen</option>
                            <option value="Faible">Faible</option>
                </select>
              </div>
                <div class="form-group">
                    <label for="design">Design</label>
                 <select class="form-control" id="design"  name="design" >
                            <option value="choix ?"><?php echo $design; ?></option>
                            <option value="Effectif">Effectif</option>
                            <option value="Remarque majeur">Remarque majeur</option>
                            <option value="Remarque mineur">Remarque mineur</option>
                            <option value="Non-effectif">Non-effectif</option>
                        </select>
                </div>
                <div class="form-group">
                <label for="efficacite">Efficacité</label>
                 <select class="form-control" id="efficacite"  name="efficacite" >
                            <option value="choix ?"><?php echo $efficacite; ?></option>
                            <option value="Effectif">Effectif</option>
                            <option value="Remarque majeur">Remarque majeur</option>
                            <option value="Remarque mineur">Remarque mineur</option>
                            <option value="Non-effectif">Non-effectif</option>
                        </select>
                </div>
                
                <div class="form-group">
                <label for="categorie">Nom de la categorie :</label>
                <input type="text" class="form-group" id="categorie" name="categorie" value="<?php echo $categorie; ?>"/>
              </div>
                
              <input type="submit" name="modifie" class="btn btn-primary"/>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
        </div>
    </body>
</html>