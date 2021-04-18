<?php
/*
  Realise la connexion avec la base de données
*/
include '../connexion/bdd_connection.php';
/* 
  Verifie que l'utilisateur est bien connecte 
*/
if(isset($_SESSION['admin_email']) && $_SESSION['admin_email'] !=''){

/*
  Requete SQL qui va permettre lors de son execution d'avoir les informations necessaires pour l'affichage des controles 
*/
$query='SELECT controle.id as id, controle.mission_id, controle.categorie_id,controle.nom_du_controle,controle.deadline,controle.email_utilisateur_realise_par, controle.email_utilisateur_revu_par,controle.email_utilisateur_sign_off, controle.statut,controle.niveau_de_risque,controle.design,controle.efficacite, controle.lu,controle.lu_statut, mission.mission_id,mission.mission_nom, mission.email_proprietaire,categorie_general.id as id_categorie,categorie_general.nom_categorie FROM controle INNER JOIN mission ON controle.mission_id = mission.mission_id INNER JOIN categorie_general ON controle.categorie_id = categorie_general.id ORDER BY controle.mission_id';
$resultSet = $pdo->query($query);
$controles = $resultSet->fetchAll();

/*
  Affichage des categories generales
*/
$query='SELECT * FROM categorie_general'; //requete SQL qui va permettre cette affichage
$resultSet = $pdo->query($query);
$categories = $resultSet->fetchAll();

/*
  Affichage des categories en fonction de leurs asscoiations à une mission specifique
*/
$query='SELECT * FROM categorie_mission'; //requete SQL qui va permettre cette affichage
$resultSet = $pdo->query($query);
$categories_missions = $resultSet->fetchAll();

/*
  Requete SQL qui va permettre d'avoir les differentes missions lors de son execution.
*/
$query='SELECT * FROM mission'; //Requete SQL qui va permettre cette affichage
   $resultSet = $pdo->query($query);
   $missions = $resultSet->fetchAll();
  
/*
    Requete SQL qui agira afin de determiner quelles sont les colonnes du tableau a afficher en fonction des choix de l'utilisateur
*/
  $query=$pdo->prepare("SELECT * FROM set_colonne WHERE id = ?");
/*
    Requete SQL pour selectionner seulement les colonnes avec pour valeur = 1
*/
    $query->execute([1]);
    $colonnes=$query->fetch();

/*
  Fonction qui permet à partir d'un email de retourner le nom et prenom de l'utilisateur
*/
  function getNameByEmail($pdo, $email){
    
/*
    requete SQL qui va selectionner dans les utilisateur la personne possedant ce mail
*/
    $query=$pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
    // Envoi du mail dans la requete 
    $query->execute([$email]);
    $utilisateur=$query->fetch();
    //Retourne le nom et prenom de l'utilisateur possedant ce mail
    return $utilisateur['nom']." ".$utilisateur['prenom'];
}     

//Ajout d'une categorie generale
if(isset($_POST['ajouter_cat'])){
  //Requete SQL qui va permettre d'ajouter une categorie generale
  $query=$pdo->prepare("insert into categorie_general (nom_categorie) values(?)");

  //Nom de la categorie a ajouter
  $query->execute([$_POST['categorie_nom']]);
  }

  /*
    Association d'une categorie a une mission donnee
  */
  if(isset($_POST['ajouter_categorie'])){

    //Requete SQL qui va permettre d'associer une categorie des categories generale et de les associer à des missions.
    $query=$pdo->prepare("insert into categorie_mission (id_categorie, id_mission) values(?,?)"); 

    //Nom de la categorie a associer a la mission
    $query->execute([$_POST['categorie'],$_POST['mission']]);
  }
  
  //Permet de supprimer une categorie ainsi que tous les controles qui lui sont associés dans une mission donnée par l'utilisateur
  if(isset($_POST['supprimer_cat'])){

  /*
    Requete SQL qui supprime la categorie dans la mission 
  */
    $query=$pdo->prepare("delete from categorie_mission where id_mission= ? and id_categorie = ?"); 
    $query->execute([$_POST['mission'],$_POST['categorie']]);

  /*
    Requete SQL qui supprime les controles de cette categorie de mission
  */
    $query=$pdo->prepare("delete from controle where mission_id = ? AND categorie_id = ?");
    $query->execute([$_POST['mission'],$_POST['categorie']]);

    header('Location:documentation.php');// redirection vers la page 'mes controles'
  }

  
/*
  Fonction qui va permettre de retourner a partir d'une requete SQL d'afficher le dernier commentaire d'un controle. 
*/
  function getLastComment($pdo, $id){

    //Requete SQL qui va permettre cette affichage
    $query=$pdo->prepare("SELECT * FROM commentaires WHERE id_controle=? ORDER BY id DESC");
    $query->execute([$id]);

    //Verifie qu'il y a un dernier commentaire si non affiche 'pas de commentaires'
    $last_commentaire=$query->fetch();
    if($last_commentaire){
      return $last_commentaire['commentaire'];
    }else{
      return "pas de commentaires";
    }
    
  }

/*
  Fonction qui permmettra a partir d'une requete SQL de retourner les missions dans lesquelles l'utilisateur a été identifié.
*/
  function concernedByMission($pdo, $id, $email){
     //Requete SQL qui permet de determiner si l'utilisateur apparait dans l'equipe de la mission
    $query=$pdo->prepare("SELECT * FROM equipe WHERE id_mission = ? AND email_utilisateur =?");
    $query->execute([$id,$email]);
    $results=$query->fetch();

    //La fonction renvoi 'true' si il appartient a la mission si nan 'false'.
    if($results){
      return true;
    }else{
      return false;
    }
  }

  /*
    Fonction qui permettra a partir d'une requete SQL de retourner le role d'une personne dans une mission, et ainsi traiter les priviléges par mission
  */
  function getRole($pdo,$mission){

    //Requete SQL qui verra si l'utilisateur fait parti de la mission
    $query=$pdo->prepare("SELECT * FROM equipe WHERE id_mission = ? AND email_utilisateur = ?");
    $query->execute([$mission,$_SESSION['admin_email']]);
    $role=$query->fetch();

    //La fonction retourne le role de l'utilisateur dans la mission
    return $role['role'];
  }
?>

<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Documentation</title>

    

    <!-- Bootstrap core CSS -->
    <link href="../bootstrap/bootstrap.min.css" rel="stylesheet">
    

    <style>
      a{
      text-decoration: none !important;
    }
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>

    
    <!-- Style de la page 'mes controles' -->
    <link href="../bootstrap/controle.css" rel="stylesheet">
  </head>
  <body>
    <!-- Barre de menu en haut de la page -->
<header class="navbar navbar-info sticky-top bg-info flex-md-nowrap p-0 shadow">
<a class="navbar-info col-md-3 col-lg-2 me-0 px-3" href="#"><img style="height : 2àpx; width:150px;" src="mazars-logo.png"></a>
  <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <input class="form-control form-control-dark w-100 filter-input" type="text" placeholder="Recherche" name="recherche"  aria-label="Search">
  <li class="nav-item dropdown">
    <a class="nav-link" href="#" id="dropdown01" data-bs-toggle="dropdown" aria-expanded="false"><span id="notifs-count"></span><img src="../bootstrap/bell.svg">   </a>
    <ul id="notifs-wrapper" class="dropdown-menu" aria-labelledby="Notfications">
<?php
      //affichage des notifications
      include '../notif/action.php';
      foreach ($notifsStatut as $notifStatut) {
         $classNotifs = '';
        if($notifStatut['lu_statut']){
          $classNotifs = 'notif-read';
        } 
?>
        <!-- Message de la notification concernant le changement d'un statut -->
        <li class="<?php echo $classNotifs; ?>"><a class="dropdown-item-left" href="#"><small><i><i><br>Le statut a changé <?php echo $notifStatut['statut']; ?> pour : <?php echo $notifStatut['nom_du_controle']; ?></small></a></li>
<?php
      
      }
      foreach ($notifs as $notif) {

        $classNotifs = '';
        if($notif['lu']){
          $classNotifs = 'notif-read';
        } 
?>
        <!-- Message de la notification concernant l'approche de la deadline -->
        <li class="<?php echo $classNotifs; ?>"><a class="dropdown-item-left" href="#"><small ><i><?php echo $notif['deadline']; ?>, <i><br>Attention la deadline pour : <?php echo $notif['nom_du_controle']; ?></small><strong><small> arrive bientôt à échéance.</small></strong></a></li>
<?php
      }
?>

  
    </ul>
  </li>
  <li>
    <!-- Pole utilisateur -->
    <div class="dropdown">
    <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle" id="dropdownUser2" data-bs-toggle="dropdown" aria-expanded="false">
      <strong><?php echo $_SESSION['admin_nom']; ?></strong>
    </a>
    <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser2">
      <li><a class="dropdown-item" href="../aide/nous_contacter.php">Nous contacter</a></li>
      <li><hr class="dropdown-divider"></li>
      <li><a class="dropdown-item" href="../connexion/logout.php">Déconnexion</a></li>
    </ul>
  </div>
</li>
  </ul>
</header>

<!-- Barre latérale contenant le menu, les filtres et les outils -->
<div class="container-fluid">
<div class="row">
    <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
      <div class="position-sticky pt-3">
        <ul class="nav flex-column">
          <div class="accordion accordion-flush" id="accordionFlushExample">
            <div class="accordion-item">
              <h2 class="accordion-header" id="flush-headingOne">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                  <img src="../bootstrap/graph-up.svg">Tableau de bord
                </button>
              </h2>
            </div>
          <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
          <div class="accordion-body">
            <button type="button" class="btn nav-link"><img src="../bootstrap/folder-plus.svg"><a href="../activite/activite.php"> Activité</a></button>
            <button type="button" class="btn nav-link"><img src="../bootstrap/folder-plus.svg"><a href="../activite/activite_perso.php"> Mon activité</a></button>
          </div>
        </div>
          <div class="accordion accordion-flush" id="accordionFlushExample">
            <div class="accordion-item disabled">
              <h2 class="accordion-header" id="flush-headingOne">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                  <img src="../bootstrap/person-lines-fill.svg"> Équipe
                </button>
              </h2>
              </div>
          <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
          <div class="accordion-body">
            <button type="button" class="btn nav-link"><img src="../bootstrap/folder-plus.svg"><a href="../equipe/mission.php"> Mes missions</a></button>
            <button type="button" class="btn nav-link"><img src="../bootstrap/folder-plus.svg"><a href="../equipe/carnet_addresse.php"> Carnet d'adresse</a></button>
          </div>
          </div>

          <div class="accordion accordion-flush" id="accordionFlushExample">
            <div class="accordion-item">
              <h2 class="accordion-header" id="flush-headingTree">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTree" aria-expanded="false" aria-controls="flush-collapseTree">
                  <img src="../bootstrap/folder-check.svg"> Documentation
                </button>
              </h2>
              </div>
          <div id="flush-collapseTree" class="accordion-collapse collapse" aria-labelledby="flush-headingTree" data-bs-parent="#accordionFlushExample">
          <div class="accordion-body">
            <button type="button" class="btn nav-link"><img src="../bootstrap/folder-plus.svg"><a href="documentation.php"> Mes contrôles</a></button>
          </div>
        </div>
        <div class="accordion accordion-flush" id="accordionFlushExample">
            <div class="accordion-item nav-item">
              <h2 class="accordion-header" id="flush-headingFor">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFor" aria-expanded="false" aria-controls="flush-collapseFor">
                  <img src="../bootstrap/info.svg"> Aide
                </button>
              </h2>
              </div>
          <div id="flush-collapseFor" class="accordion-collapse collapse" aria-labelledby="flush-headingFor" data-bs-parent="#accordionFlushExample">
          <div class="accordion-body">
            <button type="button" class="btn  nav-link"><img src="../bootstrap/folder-plus.svg"><a href="../aide/FAQ.php"> FAQ</a></button>
            <button type="button" class="btn  nav-link"><img src="../bootstrap/folder-plus.svg"><a href="../aide/nous_contacter.php"> Nous contacter</a></button>
          </div>
        </div>
        </ul>

        <!-- Fonctionnalites de la page -->
        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
          <span>Outils :</span>
          <a class="link-secondary" href="" aria-label="ajouter un controle">
          <img src="../bootstrap/tools.svg">
          </a>
        </h6>
        <ul class="nav flex-column mb-2">
          <li class="nav-item">
            <a class="nav-link" href="form_doc.php">
            <img src="../bootstrap/file-earmark-plus.svg">
              Ajouter un contrôle
            </a>
          </li>
          <li class="nav-item">
            <button type="button" class="btn  nav-link" data-bs-toggle="modal" data-bs-target="#ajoutCategorie"><img src="../bootstrap/folder-plus.svg"> <small><i>Ajouter une catégorie</i></small></button>
          </li>
          <li class="nav-item">
          <button type="button" class="btn  nav-link" data-bs-toggle="modal" data-bs-target="#ajoutermissionsCategorie"><img src="../bootstrap/folder-minus.svg"> <small><i>Associer une catégorie</i></small></button>
          </li> 
          <?php 
          //Gestion des privileges
          if($_SESSION['admin_privilege'] == 'Senior Manager' || $_SESSION['admin_privilege'] == 'Associé'){ 
          ?>
          <li class="nav-item">
            <button type="button" class="btn  nav-link" data-bs-toggle="modal" data-bs-target="#SupCategorie"><img src="../bootstrap/folder-minus.svg"> <small><i>Supprimer une catégorie</i></small></button>
          </li>
          <?php } ?>
          <li class="nav-item">
            <button type="button" class="btn  nav-link" id="delete-checkbox"><img src="../bootstrap/file-earmark-minus.svg"> <small><i>Supprimer les contrôles</i></small></button>
          </li> 
          <?php 
          //Gestion des privileges
          if($_SESSION['admin_privilege'] == 'Senior Manager' || $_SESSION['admin_privilege'] == 'Associé'){ 
            ?>
          <li>
            <button type="button" class="btn nav-link" data-bs-toggle="modal" data-bs-target="#modal-colonnes">
            <img src="../bootstrap/file-earmark-minus.svg"> <small><i>Modifier les colonnes</i></small>
            </button>
          <?php } ?>
          </li>
        </ul>

        <!-- Filtres lies a la page -->
        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
          <span>Filtres : </span>
          <a class="link-secondary" href="" aria-label=" ">
          <img src="../bootstrap/filter.svg">
          </a>
        </h6>
        <ul class="nav flex-column mb-2">
          <li class="nav-item">
            <a class="nav-link" href="#">
            <img src="../bootstrap/briefcase.svg">
              Par Mission : 
              <select name="mission" class="form-control form-control-white w-100 filter-select">
                <option>Sélectionner une mission :</option>
                <?php foreach($missions as $mission){ 
                  //Verifie si l'utilisateur fait bien parti de la mission grace a la fonction concernedByMission()
                  if(concernedByMission($pdo, $mission['mission_id'], $_SESSION['admin_email'])){?>
                  <option value="<?php echo $mission['mission_nom']; ?>"><?php echo $mission['mission_nom']; ?></option>
                <?php } } ?>
              </select>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
            <img src="../bootstrap/bar-chart-steps.svg">
              Par Catégorie :
              <select name="categorie" class="form-control form-control-white w-100 filter-select">
                <option>Sélectionner une catégorie :</option>
                <?php foreach($categories as $categorie){ ?>
                  <option value="<?php echo $categorie['nom_categorie']; ?>"><?php echo $categorie['nom_categorie']; ?></option>
                <?php } ?>
              </select>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
            <img src="../bootstrap/ui-checks.svg">
              Par Statut :
              <select name="statut" class="form-control form-control-white w-100 filter-select">
                <option>Sélectionner un statut :</option>
                <option value="Non débuté">Non débuté</option>
                <option value="Documenté">Documenté</option>
                <option value="Revu">Revu</option>
                <option value="Sign-off">Sign-off</option>
              </select>
            </a>
          </li>
        </ul>
      </div>
    </nav>
    
    

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <br>
     <!-- Modal qui permettra de changer les colonnes à afficher sur le tableau des controles -->
      <div class="modal fade" id="modal-colonnes" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">

            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Modifier les colonnes</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="mission" <?php if($colonnes['mission'] == 0){ echo "checked"; }?>>
              <label class="form-check-label" for="mission">Mission</label>
            </div>

            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="categorie" <?php if($colonnes['categorie'] == 0){ echo "checked"; }?>>
              <label class="form-check-label" for="categorie">Catégorie</label>
            </div>

            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="nom_du_controle" <?php if($colonnes['nom_du_controle'] == 0){ echo "checked"; }?>>
              <label class="form-check-label" for="nom_du_controle">Nom du contrôle</label>
            </div>

            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="deadline" <?php if($colonnes['deadline'] == 0){ echo "checked"; }?>>
              <label class="form-check-label" for="deadline">Deadline</label>
            </div>

            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="affecte_a" <?php if($colonnes['affecte_a'] == 0){ echo "checked"; }?>>
              <label class="form-check-label" for="affecte_a">Affecté a </label>
            </div>

            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="statut" <?php if($colonnes['statut'] == 0){ echo "checked"; }?>>
              <label class="form-check-label" for="statut">Statut</label>
            </div>

            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="niveau_de_risque" <?php if($colonnes['niveau_de_risque'] == 0){ echo "checked"; }?>>
              <label class="form-check-label" for="niveau_de_risque">Niveau de risque</label>
            </div>

            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="design" <?php if($colonnes['design'] == 0){ echo "checked"; }?>>
              <label class="form-check-label" for="design">Design</label>
            </div>

            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="efficacite" <?php if($colonnes['efficacite'] == 0){ echo "checked"; }?>>
              <label class="form-check-label" for="efficacite">Efficacité</label>
            </div>

            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="commentaires" <?php if($colonnes['commentaires'] == 0){ echo "checked"; }?>>
              <label class="form-check-label" for="commentaires">Commentaires</label>
            </div>


            </div>
            <div class="modal-footer">
              <button type="submit" id="edit-colonne" class="btn btn-primary">Sauvegarder</button>
            </div>

          </div>
        </div>
      </div>
      <div class="table-responsive-sm">
        <table class="table table-striped table-xxl">
          <thead>
            <tr>
              <th> </th>
              <!-- Verification des valeurs des colonnes pour verifier si l'utilisateur affichera certaines colonnes ou pas -->
              <?php if($colonnes['mission'] == "0"){ ?>
              <th>Mission</th>
              <?php } ?>
              <?php if($colonnes['categorie'] == "0"){ ?>
              <th>Catégorie</th>
              <?php } ?>
              <?php if($colonnes['nom_du_controle'] == "0"){ ?>
              <th>Nom du contrôle</th>
              <?php } ?>
              <?php if($colonnes['deadline'] == "0"){ ?>
              <th>Deadline</th>
              <?php } ?>
              <?php if($colonnes['affecte_a'] == "0"){ ?>
              <th>Affecté a</th>
              <?php } ?>
              <?php if($colonnes['statut'] == "0"){ ?>
              <th>Statut</th>
              <?php } ?>
              <?php if($colonnes['niveau_de_risque'] == "0"){ ?>
              <th>Niveau de risque</th>
              <?php } ?>
              <?php if($colonnes['design'] == "0"){ ?>
              <th>Design</th>
              <?php } ?>
              <?php if($colonnes['efficacite'] == "0"){ ?>
              <th>Efficacité</th>
              <?php } ?>
              <?php if($colonnes['commentaires'] == "0"){ ?>
              <th>Commentaires</th>
              <?php } ?>
            </tr>
          </thead>
        
          <!-- Tableau des controles -->
          <tbody>
            <?php foreach($categories as $categorie){ ?>
            <tr>
            <th colspan="11"><h5 class="my-0"><?php echo $categorie['nom_categorie']; ?></h5></th>
            </tr>
            <?php 
            foreach($controles as $controle){
              if(($controle['categorie_id'] == $categorie['id'])){ 
               if(concernedByMission($pdo, $controle['mission_id'], $_SESSION['admin_email'])){
                 ?>
           <tr>
              <td><input class="controle-checkbox" type='checkbox' data-id="<?php echo $controle['id']; ?>"></td>
              <?php if($colonnes['mission'] == "0"){ ?>
              <td class="search-mission search-recherche"><strong><?php echo $controle['mission_nom']; ?></strong></td>
              <?php } ?>
              <?php if($colonnes['categorie'] == "0"){ ?>
              <td class="search-categorie search-recherche"><?php echo $controle['nom_categorie']; ?></td>
              <?php } ?>
              <?php if($colonnes['nom_du_controle'] == "0"){ ?>
              <td class="search-recherche"><a href="telechargement.php?id=<?php echo $controle['id']; ?>"><?php echo $controle['nom_du_controle']; ?></a></td>
              <?php } ?>
              <?php if($colonnes['deadline'] == "0"){ ?>
              <td><?php echo $controle['deadline']; ?></td>
              <?php } ?>
              <?php if($colonnes['affecte_a'] == "0"){ ?>
              <td class="search-recherche"><?php 
               $email = "";
               if($controle["statut"] == 'Non débuté'){
                 $email = $controle["email_utilisateur_realise_par"];
               } elseif($controle["statut"] == 'Documenté'){
                 $email = $controle["email_utilisateur_revu_par"];
               }elseif($controle["statut"] == 'Revu'){
                 $email = $controle["email_utilisateur_revu_par"];
               }else{
                 $email = $controle["email_utilisateur_sign_off"];
               }
              echo getNameByEmail($pdo, $email); ?></td>
            <?php } ?>
            <?php if($colonnes['statut'] == "0"){ ?>

              <?php 
              //Traitrement des couleurs des différents champs : statut, niveau de risque, design, efficacite en fonction des choix saisis
              $color_statut = "";
              if($controle["statut"] == 'Non débuté'){
                $color_statut = "red";
              }
              elseif($controle["statut"] == 'Documenté'){
                $color_statut = "orange";
              }elseif($controle["statut"] == 'Revu'){
                $color_statut = "yellowgreen";
              }else{
                $color_statut = "green";
              }    

             
              $color_risque = "";
              if($controle["niveau_de_risque"] == 'Élevé'){
                $color_risque = "red";
              }
              elseif($controle["niveau_de_risque"] == 'Moyen'){
                $color_risque = "orange";
              }else{
                $color_risque = "green";
              }    

              $color_design = "";
              if($controle["design"] == 'Non effectif'){
                $color_design = "red";
              }
              elseif($controle["design"] == 'Remarque mineure'){
                $color_design = "orange";
              }elseif($controle["design"] == 'Remarque majeure'){
                  $color_design = "yellow";
              }else{
                $color_design = "green";
              }   

              $color_efficacite = "";
              if($controle["efficacite"] == 'Non effectif'){
                $color_efficacite = "red";
              }
              elseif($controle["efficacite"] == 'Remarque mineure'){
                $color_efficacite = "orange";
              }elseif($controle["efficacite"] == 'Remarque majeure'){
                  $color_efficacite = "yellow";
              }else{
                $color_efficacite = "green";
              }    
              ?>
              <!-- Gestion des privileges sur le statut de la mission -->
              <td class='statut' style="color: <?php echo $color_statut ;?>;">
              <?php if(getRole($pdo, $controle['mission_id']) == 'Junior') {?>
              <span class='search-statut span-statut search-recherche'><?php echo $controle['statut'] ;?></span>
              <select class='statut-select' data-id='<?php echo $controle['id'] ;?>'> 
                    <option value='Non débuté'
                    <?php if($controle['statut'] == "Non débuté"){echo 'selected="selected"';}?>>Non débuté</option>
                    <option value='Documenté' <?php if($controle['statut'] == "Documenté"){echo 'selected="selected"';}?>>Documenté</option>
                    <?php }elseif(getRole($pdo, $controle['mission_id']) == 'Senior') {?>
                      <span class='search-statut span-statut search-recherche'><?php echo $controle['statut'] ;?></span>
                      <select class='statut-select' data-id='<?php echo $controle['id'] ;?>'> 
                        <option value='Non débuté' <?php if($controle['statut'] == "Non débuté"){echo 'selected="selected"';}?>>Non débuté</option>
                        <option value='Documenté' <?php if($controle['statut'] == "Documenté"){echo 'selected="selected"';}?>>Documenté</option>
                        <option value='Revu' <?php if($controle['statut'] == "Revu"){echo 'selected="selected"';}?>>Revu</option>
                    <?php }else{?>
                      <span class='search-statut span-statut search-recherche'><?php echo $controle['statut'] ;?></span>
                        <select class='statut-select' data-id='<?php echo $controle['id'] ;?>'> 
                            <option value='Non débuté'
                             <?php if($controle['statut'] == "Non débuté"){echo 'selected="selected"';}?>>Non débuté</option>
                            <option value='Documenté' <?php if($controle['statut'] == "Documenté"){echo 'selected="selected"';}?>>Documenté</option>
                            <option value='Revu' <?php if($controle['statut'] == "Revu"){echo 'selected="selected"';}?>>Revu</option>
                            <option value='Sign-off' <?php if($controle['statut'] == "Sign-off"){echo 'selected="selected"';}?>>Sign-off</option>
                    <?php } ?>
                    </select>
              </td>
              <?php } ?>

              <?php if($colonnes['niveau_de_risque'] == "0"){ ?>
              <td class='niv_risque' style="color: <?php echo $color_risque ;?>;">
              <span class='span_niv_risque search-recherche'><?php echo $controle['niveau_de_risque'] ;?></span>
                    <select class='niv-risque-select' data-id='<?php echo $controle['id'] ;?>'> 
                      <option value='Élevé'>Élevé</option>
                      <option value='Moyen'>Moyen</option>
                      <option value='Faible'>Faible</option>
                    </select>
              </td>
              <?php } ?>
              <?php if($colonnes['design'] == "0"){ ?>
                <td class='design' style="color: <?php echo $color_design ;?>;">
              <span class='span-design search-recherche'><?php echo $controle['design'] ;?></span>
                    <select class='design-select' data-id='<?php echo $controle['id'] ;?>'> 
                    <option value='Non effectif'>Non effectif</option>
                    <option value='Remarque mineure'>Remarque mineure</option>
                    <option value='Remarque majeure'>Remarque majeure</option>
                    <option value='Effectif'>Effectif</option>
                    </select>
              </td>
              <?php } ?>
              <?php if($colonnes['efficacite'] == "0"){ ?>
                <td class='efficacite' style="color: <?php echo $color_efficacite ;?>;">
              <span class='span-efficacite search-recherche'><?php echo $controle['efficacite'] ;?></span>
                    <select class='efficacite-select' data-id='<?php echo $controle['id'] ;?>'> 
                    <option value='Non effectif'>Non effectif</option>
                    <option value='Remarque mineure'>Remarque mineure</option>
                    <option value='Remarque majeure'>Remarque majeure</option>
                    <option value='Effectif'>Effectif</option>
              <?php } ?>
              <?php if($colonnes['commentaires'] == "0"){ ?>
              <td class="popup-commentaire" data-id="<?php echo $controle['id']; ?>"><?php echo getLastComment($pdo, $controle['id']); ?></td>
              <?php } ?>
            </tr>
            <?php } } } ?>
            <?php }  ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</div>

<!-- MODAL COMMENTAIRE LISTE - AJOUT -->
<div class="modal fade" id="modal-commentaire" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-nom-controle">Commentaires</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="d-flex flex-column justify-content-center"><!-- faire le design -->
          <label for="ajout-commentaire">Ajout de commentaire</label>
          <textarea id="ajout-commentaire"></textarea>
          <button type="button" class="btn btn-info" id="bouton-ajout-commentaire">Ajouter</button>        
        </div>


        <div id="modal-liste-commentaires">
          
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>
<!-- FIN MODAL COMMENTAIRE LISTE - AJOUT -->

<!-- Modal pour l'ajout d'une categorie generale -->
<div class="modal fade" id="ajoutCategorie" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="documentation.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ajout d'une catégorie</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                   
                    <div class="col-sm-9">
                        <label for="categorie_nom" class="form-label">Nom de la catégorie</label>
                        <div class="input-group has-validation">
                        <span class="input-group-text"><span><img src="../bootstrap/file-text.svg"></span></span>
                        <input type="text" name="categorie_nom" class="form-control" id="categorie_nom" placeholder="Nom de la catégorie" required>
                        <div class="invalid-feedback">
                        Ce champ est obliagtoire.
                        </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" name="ajouter_cat">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Fin de la modal ajout d'une categorie generale -->

<!-- La modal permmettra d'associer une categorie generale à une mission propre -->
<div class="modal fade" id="ajoutermissionsCategorie" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="documentation.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ajout d'une catégorie</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                <div style="padding-left : 10px;" class="col-sm-11">
                        <label for="mission" class="form-label"><i>Dans quelle mission ?</i></label>
                        <select class="form-select" name="mission">
                      <option value="" disabled hidden selected>Choisir une mission</option>
                      <?php foreach($missions as $mission){ ?>
                        <option value="<?php echo $mission['mission_id']; ?>"><?php echo $mission['mission_nom']; ?></option>
                        <?php }?>
                    </select>
                        <div class="invalid-feedback">
                        Ce champ est obliagtoire.
                        </div>
                    </div>

                    <div style="padding-left : 10px;" class="col-sm-11">
                        <label for="mission" class="form-label"><i>Quelle est la catégorie ?</i></label>
                        <select class="form-select" name="categorie">
                          <option value="" disabled hidden selected>Choisir une catégorie</option>
                            <?php foreach($categories as $categorie){ ?>
                          <option value="<?php echo $categorie['id']; ?>"><?php echo $categorie['nom_categorie']; ?></option>
                            <?php }?>
                        </select>
                        <div class="invalid-feedback">
                        Ce champ est obliagtoire.
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" name="ajouter_categorie">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- fin de modal qui permettra d'associer une categorie generale à une mission propre -->

<!-- modal qui permettra de supprimer une categorie et par le biais de la requete d'en haut va aussi supprimer les controles de cette categorie -->
<div class="modal fade" id="SupCategorie" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="documentation.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Supprimer une catégorie</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col-sm-9">
                        <label for="mission" class="form-label"><i>Quelle est la mission ?</i></label>
                        <select name="mission" id="delete-from-mission" class="form-select">
                        <option>Sélectionner une mission :</option>
                        <?php foreach($missions as $mission){ ?>
                        <option value="<?php echo $mission['mission_id']; ?>"><?php echo $mission['mission_nom']; ?></option>
                        <?php } ?>
                        </select>
                        <div class="invalid-feedback">
                        Ce champ est obliagtoire.
                        </div>
                    </div>
                    <div class="col-sm-9">
                        <label for="categorie" class="form-label">Nom de la catégorie</label>
                        <div class="input-group has-validation">
                        <span class="input-group-text"><span><img src="../bootstrap/file-text.svg"></span></span>
                        <select id="delete-categorie" class="form-control" name="categorie">
                          <option value="" disabled hidden selected>Choisir une catégorie</option>
                        </select>
                        <div class="invalid-feedback">
                        Ce champ est obliagtoire.
                        </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="confirm_delete_categorie" class="btn btn-primary" name="supprimer_cat">Supprimer</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Fin de modal pour la suppresion des categories -->

<!-- Pied de page -->
<footer class="my-5 pt-5 text-muted text-center text-small">
<p>&copy;Copyright 2021 - Mazars - France Inc. &middot;</p>
<p class="float"><a href="#">Retourner en haut</a></p>
</footer>
<!-- JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"
        integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg=="
        crossorigin="anonymous"></script>
    <script src="../bootstrap/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
  
<script>

 /*
  Modification du statut lors d'un double click sur le champ 
 */

 //Renvoi une NodeList statique représentant une liste des éléments du document qui correspondent au span selectionne.
  var span_statut = document.querySelectorAll('.span-statut');

for(var i = 0; i < span_statut.length; i++){
  const on = span_statut[i];
    //Evenement sur les elements du groupes de selecteurs specifies qui va permettre le changement du span en select au moment du double clique
    on.addEventListener('dblclick', () => {
      var td = on.closest('td');
      td.classList.add('active');
    })
}

/*
  Renvoi une NodeList statique représentant une liste des éléments du document qui correspondent au select selectionne.
*/
var select_statut = document.querySelectorAll('.statut-select');

for(var i = 0; i < select_statut.length; i++){
  const on = select_statut[i];
  //Evenement : changer la valeur du champ selectionne
    on.addEventListener('change', () => {
      var td = on.closest('td');
      var select_value = on.value;
      var id = on.getAttribute("data-id");
      var span = td.querySelector('span');

      
      //Changement de la valeur du statut dans la base de donnees
      $.ajax({
          url: "edit_statut.php",
          method: "POST",
          data: "id=" + id + "&value=" + select_value,
          success: function() {
            span.innerHTML = select_value;
            var color ="";
            //Change la couleur en fonction de la selection
            switch (select_value) {
              case 'Non débuté':
                color = "red";
                break;
              case 'Documenté':
                color = "orange";
              case 'Revu':
                color ="yellowgreen";
                break;
              default:
                color = "green";
            }
            td.style.color = color;
            td.classList.remove('active');

          }
      })

      
    })
}
// Fin statut

/*
  Modification du niveau de risque lors d'un double click sur le champ 
 */
 //Renvoi une NodeList statique représentant une liste des éléments du document qui correspondent au span selectionne.
var span_niv_risque = document.querySelectorAll('.span_niv_risque');

for(var i = 0; i < span_niv_risque.length; i++){
  const on = span_niv_risque[i];
    //Evenement sur les elements du groupes de selecteurs specifies qui va permettre le changement du span en select au moment du double clique
    on.addEventListener('dblclick', () => {
      var td = on.closest('td');
      td.classList.add('active');
    })
}

/*
  Renvoi une NodeList statique représentant une liste des éléments du document qui correspondent au select selectionne.
*/
var select_niv_risque = document.querySelectorAll('.niv-risque-select');

for(var i = 0; i < select_niv_risque.length; i++){
  const on = select_niv_risque[i];
  //Evenement : changer la valeur du champ selectionne
    on.addEventListener('change', () => {
      var td = on.closest('td');
      var select_value = on.value;
      var id = on.getAttribute("data-id");
      var span = td.querySelector('span');

      
      //Changement de la valeur du niveau de risque dans la base de donnees
      $.ajax({
          url: "edit_niv_risque.php",
          method: "POST",
          data: "id=" + id + "&value=" + select_value,
          success: function() {
            span.innerHTML = select_value;
            var color ="";
            //Change la couleur en fonction de la selection
            switch (select_value) {
              case 'Élevé':
                color = "red";
                break;
              case 'Moyen':
                color = "orange";
                break;
              default:
                color = "green";
            }
            td.style.color = color;
            td.classList.remove('active');

          }
      })
    })
}
// fin niveau de risque

/*
  Modification du design lors d'un double click sur le champ 
 */
 //Renvoi une NodeList statique représentant une liste des éléments du document qui correspondent au span selectionne.
var span_design = document.querySelectorAll('.span-design');

for(var i = 0; i < span_design.length; i++){
  const on = span_design[i];
  //Evenement sur les elements du groupes de selecteurs specifies qui va permettre le changement du span en select au moment du double clique
    on.addEventListener('dblclick', () => {
      var td = on.closest('td');
      td.classList.add('active');
    })
}

/*
  Renvoi une NodeList statique représentant une liste des éléments du document qui correspondent au select selectionne.
*/
var select_design = document.querySelectorAll('.design-select');

for(var i = 0; i < select_design.length; i++){
  const on = select_design[i];
  //Evenement : changer la valeur du champ selectionne
    on.addEventListener('change', () => {
      var td = on.closest('td');
      var select_value = on.value;
      var id = on.getAttribute("data-id");
      var span = td.querySelector('span');

      
      //Changement de la valeur du design dans la base de donnees
      $.ajax({
          url: "edit_design.php",
          method: "POST",
          data: "id=" + id + "&value=" + select_value,
          success: function() {
            span.innerHTML = select_value;
            var color ="";
            //Change la couleur en fonction de la selection
            switch (select_value) {
              case 'Non effectif':
                color = "red";
                break;
              case 'Remarque mineure':
                color = "orange";
                break;
              case 'Remarque majeure':
                color = "yellow";
                break;
              default:
                color = "green";
            }
            td.style.color = color;
            td.classList.remove('active');

          }
      })
    })
}
//fin modif design


/*
  Modification de l'efficacite lors d'un double click sur le champ 
 */
 //Renvoi une NodeList statique représentant une liste des éléments du document qui correspondent au span selectionne.
var span_efficacite = document.querySelectorAll('.span-efficacite');

for(var i = 0; i < span_efficacite.length; i++){
  const on = span_efficacite[i];
  //Evenement sur les elements du groupes de selecteurs specifies qui va permettre le changement du span en select au moment du double clique
    on.addEventListener('dblclick', () => {
      var td = on.closest('td');
      td.classList.add('active');
    })
}

/*
  Renvoi une NodeList statique représentant une liste des éléments du document qui correspondent au select selectionne.
*/
var select_efficacite = document.querySelectorAll('.efficacite-select');

for(var i = 0; i < select_efficacite.length; i++){
  const on = select_efficacite[i];
  //Evenement : changer la valeur du champ selectionne
    on.addEventListener('change', () => {
      var td = on.closest('td');
      var select_value = on.value;
      var id = on.getAttribute("data-id");
      var span = td.querySelector('span');

      
      //Changement de la valeur de l'efficacite dans la base de donnees
      $.ajax({
          url: "edit_efficacite.php",
          method: "POST",
          data: "id=" + id + "&value=" + select_value,
          success: function() {
            span.innerHTML = select_value;
            var color ="";
            //Change la couleur en fonction de la selection
            switch (select_value) {
              case 'Non effectif':
                color = "red";
                break;
              case 'Remarque mineure':
                color = "orange";
                break;
              case 'Remarque majeure':
                color = "yellow";
                break;
              default:
                color = "green";
            }
            td.style.color = color;
            td.classList.remove('active');

          }
      })
    })
}
//fin modif efficacite

//modifier colonnes
var submit_edit_colonne = document.getElementById('edit-colonne');
submit_edit_colonne.addEventListener('click', () =>{
  var mission_check = document.getElementById('mission');
  var categorie_check = document.getElementById('categorie');
  var nom_du_controle_check = document.getElementById('nom_du_controle');
  var deadline_check = document.getElementById('deadline');
  var affecte_a_check = document.getElementById('affecte_a');
  var statut_check = document.getElementById('statut');
  var niveau_de_risque_check = document.getElementById('niveau_de_risque');
  var design_check = document.getElementById('design');
  var efficacite_check = document.getElementById('efficacite');
  var commentaires_check = document.getElementById('commentaires');

  if (mission_check.checked == true) {
        var mission = 0;
    } else {
        var mission = 1;
    }
    if (categorie_check.checked == true) {
        var categorie = 0;
    } else {
        var categorie = 1;
    }
    if (nom_du_controle_check.checked == true) {
        var nom_du_controle = 0;
    } else {
        var nom_du_controle = 1;
    }
    if (deadline_check.checked == true) {
        var deadline = 0;
    } else {
        var deadline = 1;
    }
    if (affecte_a_check.checked == true) {
        var affecte_a = 0;
    } else {
        var affecte_a = 1;
    }
    if (statut_check.checked == true) {
        var statut = 0;
    } else {
        var statut = 1;
    }
    if (niveau_de_risque_check.checked == true) {
        var niveau_de_risque = 0;
    } else {
        var niveau_de_risque = 1;
    }
    if (design_check.checked == true) {
        var design = 0;
    } else {
        var design = 1;
    }
    if (efficacite_check.checked == true) {
        var efficacite = 0;
    } else {
        var efficacite = 1;
    }
    if (commentaires_check.checked == true) {
        var commentaires = 0;
    } else {
        var commentaires = 1;
    }

    $.ajax({
        url: "edit_colonnes.php",
        method: "POST",
        data: "mission=" + mission + "&categorie=" + categorie + "&nom_du_controle=" + nom_du_controle + "&deadline=" + deadline +
            "&affecte_a=" + affecte_a + "&statut=" + statut + "&niveau_de_risque=" + niveau_de_risque + "&design=" +
            design + "&efficacite=" + efficacite + "&commentaires=" + commentaires,
        success: function() {
            window.location.reload();
        }
    })
})
//fin modifier colonnes

// Filtres : selection et barre de recherche 
var filter_select = document.querySelectorAll('.filter-select');
        var filter_input = document.querySelectorAll('.filter-input');

        var rows = document.querySelectorAll("table tbody tr")
        console.log(rows);
        for (var i = 0; i < filter_select.length; i++) {
            filter_select[i].addEventListener('change', (e) => {
                filterRow(e.target)
            });
        }
        for (var i = 0; i < filter_input.length; i++) {
             filter_input[i].addEventListener('keyup', (e) => {
                 filterRow(e.target)
             });
        }
        

        function filterRow(e, reset = false) {
            switch (e.nodeName) {
                case 'INPUT':
                    var search_value = e.value.toLowerCase();
                    break;
                case 'SELECT':
                    var search_value = e.options[e.selectedIndex].value.toLowerCase();
                    break;
            }

            var nameInput = e.name;
            for (var i = 0; i < rows.length; i++) {
              
                let td = rows[i].querySelectorAll('.search-' + nameInput);
                for (let index = 0; index < td.length; index++) {
                  if(td){
                  td[index].setAttribute('data-search', search_value);
                }
                }
                refreshRow(rows[i]);
            
                
            }
            if (e.getAttribute('name') == "date") {
                var search_value = document.querySelector('#date-picker').value;
            }
        }

        function refreshRow(row) {
            var data_search = row.querySelectorAll('[data-search]:not([data-search=""])');
            var match = true;
            if (data_search) {
              

                for (var i = 0; i < data_search.length; i++) {
                    search_value = data_search[i].getAttribute('data-search');

                    if (data_search[i].classList.contains('search-date')) {
                        search_value = search_value.split('-');
                        search_value = search_value[2] + '/' + search_value[1] + '/' + search_value[0];

                        if (data_search[i].innerHTML.toLowerCase() != search_value) {
                            match = false;
                        }

                    } else if(data_search[i].classList.contains('search-recherche')) {
                      var row_value = data_search[i].innerHTML.toLowerCase();
                        
                        if (row_value.indexOf(search_value) > -1) {
                          match = true;
                          return;
                        } else {
                          match = false;
                        }
                    }else{
                      var row_value = data_search[i].innerHTML.toLowerCase();
                        
                        if (row_value.indexOf(search_value) > -1) {} else {
                          match = false;
                        }
                    }

                }
            }

            if (match) {
                row.classList.add('visible');
                row.classList.remove('invisible');
            } else {
                row.classList.remove('visible');
                row.classList.add('invisible');
            }
        }
//fin filtres

// suppression un/des controle(s) avec check box
  var checkbox_all = document.querySelectorAll('.controle-checkbox');//Saisie des id des controles de chaque checkbox selectionne
  function id_checked() {
    var tab_id_checked = [];//Creation d'un tableau comprenant tout les id selectionne
    for (var i = 0; i < checkbox_all.length; i++) {
        const on = checkbox_all[i];
        var data_id = on.getAttribute("data-id");
        if (on.checked == true) {
            //Ajout de l'id de la case dans le tableau
            tab_id_checked.push(data_id)
        }
    }
    return tab_id_checked;//Renvoi le tableau selectionne 
}

  //Action du clic sur le bouton supprimer les controles
  var bouton_delete = document.getElementById('delete-checkbox');
  bouton_delete.addEventListener('click', () => {
    all_id_client_checked = id_checked();
    //Interaction avec la base de donnees
    $.ajax({
            url: "delete_controle.php",
            method: "POST",
            data: "array_id=" + all_id_client_checked,
            success: function() {
                window.location.reload();//La page est raffraichie
            }
        })
  })
// FIN suppression controles checkbox

//Au clic sur un commentaire ouvrir popup pour ajouter commentaire ou editer ou supprimer et afficher la liste des commentaires
//On clique sur un commentaire, le popup s'ouvre !
var collect_td_commentaire = document.querySelectorAll('.popup-commentaire');
for(var i=0; i < collect_td_commentaire.length; i++){
 const on = collect_td_commentaire[i];
 on.addEventListener('dblclick', (e) =>{
   td_id = e.currentTarget.getAttribute('data-id');
   $.ajax({
            url: "recup_controle.php",
            method: "POST",
            dataType: 'json',
            data: "id=" + td_id,
            success: function(data) {
              var titre_controle = document.getElementById('modal-nom-controle').innerHTML = data.nom_du_controle;
            }
        })


  get_commentaires(td_id)
  //Au double clic sur le commentaire il affiche la modal
   $('#modal-commentaire').modal('show');
   

 })
}

//On clique sur ajouter, on ajoute un commentaire
var bouton_ajout_commentaire = document.getElementById('bouton-ajout-commentaire');
bouton_ajout_commentaire.addEventListener('click', () => {
  var textarea = document.getElementById('ajout-commentaire').value;
  $.ajax({
            url: "ajout_commentaires.php",
            method: "POST",
            data: "textarea=" + textarea + "&id=" + td_id,
            success: function() {
              get_commentaires(td_id);
            }
        })
})

function get_commentaires(id){
  $.ajax({
            url: "recup_commentaire.php",
            method: "POST",
            dataType: 'json',
            data: "id=" + id,
            success: function(data) {
              var div_list_commentaire = document.getElementById('modal-liste-commentaires');
              div_list_commentaire.innerHTML = "";
              for (var i = 0; i < data.length; i++) {
                div_list_commentaire.innerHTML += '<div class="commentaire-div"><p>'+data[i].date_commentaire+', '+data[i].nom+ ' '+ data[i].prenom + ' : '+ data[i].commentaire+ '</p><div class="textarea"><textarea>'+data[i].commentaire+'</textarea><button type="button" class="maj btn btn-light">Mettre a jour</button></div><button type="button" class="edit-bouton btn btn-primary" data-id="'+data[i].id+'"><img src="../bootstrap/pencil.svg"></button><button type="button" class="delete-bouton btn btn-danger" data-id="'+data[i].id+'"><img src="../bootstrap/trash.svg"></button></div>';
              }

              var collect_bouton_edit = document.querySelectorAll('.edit-bouton');
              for(var i = 0; i < collect_bouton_edit.length; i++){
                const on = collect_bouton_edit[i];
                on.addEventListener('click', (e) =>{
                  div_commentaire = on.closest('.commentaire-div');
                  div_commentaire.classList.toggle('show');

                  var id_edit = on.getAttribute('data-id');
                  var maj = div_commentaire.querySelector('.maj');
                  
                  maj.addEventListener('click', () =>{
                    div_commentaire.classList.remove('show');
                    var textarea_value = div_commentaire.querySelector('textarea').value;
                    $.ajax({
                        url: "edit_commentaires.php",
                        method: "POST",
                        data: "id=" + id_edit + "&textarea=" + textarea_value,
                        success: function() {
                          get_commentaires(td_id);
                        }
                    })
                  })
                  
                })
              }

              var collect_bouton_delete = document.querySelectorAll('.delete-bouton');
              for(var i = 0; i < collect_bouton_delete.length; i++){
                const on = collect_bouton_delete[i];
                on.addEventListener('click', (e) =>{
                  var id_delete = on.getAttribute('data-id');
                  $.ajax({
                        url: "delete_commentaires.php",
                        method: "POST",
                        data: "id=" + id_delete,
                        success: function() {
                          get_commentaires(td_id);
                        }
                    })
                })

              }
            }
        })
}

// Generer select categorie en fonction de la mission selectionnée ! 
var select_mission = document.getElementById('delete-from-mission');
select_mission.addEventListener('change', (e) =>{
  id_mission = e.currentTarget.value;
  $.ajax({
      url: "get_categoriefrommission.php",
      dataType: 'json',
      method: "POST",
      data: "id_mission=" + id_mission,
      success: function(data) {
        var select_categorie = document.getElementById('delete-categorie');
        for(var i = 0; data.length; i++){
         select_categorie.innerHTML += '<option value="'+data[i].id_categorie+'">'+data[i].nom_categorie+'</option>';
        }
      }
  })
})
// FIN generer select categorie en fonction de la mission selectionnée ! 
// CONFIRM Delete categorie
var bouton_confirm_delete = document.getElementById('confirm_delete_categorie');
bouton_confirm_delete.addEventListener('click', (e) =>{
  if (confirm("Voulez-vous vraiment supprimer ?")) {
            // Clic sur OK
        } else {
            e.preventDefault();

        }
})
var bouton_confirm_delete2 = document.getElementById('delete-checkbox');
bouton_confirm_delete2.addEventListener('click', (e) =>{
  if (confirm("Voulez-vous vraiment supprimer ?")) {
            // Clic sur OK
        } else {
            e.preventDefault();

        }
})

// FIN de confirm delete categories
  </script>
  
  
    </body>
    <!-- Script JavaScript sur les notifications-->
    <script src="../notif/notif.js" type="text/javascript"></script>
</html>


<?php
}else{
  header('Location:../connexion/login.php');//Redirection vers la page de connexion si on n'est pas encore connecté à l'espace.
}
?>
