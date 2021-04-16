<?php
include 'application/bdd_connection.php';
if(isset($_SESSION['admin_email']) && $_SESSION['admin_email'] !=''){


$query='SELECT * FROM controle INNER JOIN mission ON controle.mission_id = mission.mission_id INNER JOIN categorie ON controle.categorie_id = categorie.categorie_id ORDER BY controle.mission_id';
  $resultSet = $pdo->query($query);
  $controles = $resultSet->fetchAll();

  $query='SELECT * FROM categorie';
  $resultSet = $pdo->query($query);
  $categories = $resultSet->fetchAll();

  $query='SELECT * FROM mission';
  $resultSet = $pdo->query($query);
  $missions = $resultSet->fetchAll();
  
  $query=$pdo->prepare("SELECT * FROM set_colonne WHERE id = ?");
    $query->execute([1]);
    $colonnes=$query->fetch();

  function getNameByEmail($pdo, $email){
    $query=$pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
    $query->execute([$email]);
    $utilisateur=$query->fetch();
    return $utilisateur['nom']." ".$utilisateur['prenom'];
}     
if(isset($_POST['ajouter_cat'])){
  $query=$pdo->prepare("insert into categorie (id_mission,categorie_nom) values(?,?)");
  $query->execute([$_POST['mission'],$_POST['categorie_nom']]);
  }

  if(isset($_POST['supprimer_cat'])){
    $query=$pdo->prepare("delete from categorie where id_mission= ? and categorie_nom = ?");
    $query->execute([$_POST['mission'],$_POST['categorie_nom']]);
  }

  

  function getLastComment($pdo, $id){
    $query=$pdo->prepare("SELECT * FROM commentaires WHERE id_controle=? ORDER BY id DESC");
    $query->execute([$id]);
    $last_commentaire=$query->fetch();
    if($last_commentaire){
      return $last_commentaire['commentaire'];
    }else{
      return "pas de commentaire";
    }
    
  }
  //login
  //notifications
  //checkbox suppression controle
?>



<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Documentation</title>

    

    <!-- Bootstrap core CSS -->
<link href="bootstrap.min.css" rel="stylesheet">

    <style>
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

    
    <!-- Custom styles for this template -->
    <link href="controle.css" rel="stylesheet">
  </head>
  <body>
    
<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
  <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">Mazars</a>
  <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <input class="form-control form-control-dark w-100 filter-input" type="text" placeholder="Recherche" name="recherche"  aria-label="Search">
  <li class="nav-item dropdown">
    <a class="nav-link" href="#" id="dropdown01" data-bs-toggle="dropdown" aria-expanded="false"><img src="bootstrap-icons-1.4.0/bell.svg">   </a>
    <ul class="dropdown-menu" aria-labelledby="Notfications">
<?php
      include './notif/action.php';
      foreach ($notifsStatut as $notifStatut) {
?>
        <li><a class="dropdown-item" href="#"><small><i><i><br>Le statut à changé pour <?php echo $notifStatut['statut']; ?> pour : <?php echo $notifStatut['nom_du_controle']; ?></small></a></li>
<?php
        $query=$pdo->prepare("UPDATE controle set set_statut = ? where id= ?");
        $query->execute([0, $notifStatut['id']]);
      }
      foreach ($notifs as $notif) {

        $classNotifs = '';
        if($notif['lu']){
          $classNotifs = 'notif-read';
        } 
?>
        <li><a class="dropdown-item" href="#"><small class="<?php echo $classNotifs; ?>"><i><?php echo $notif['deadline']; ?>, <i><br>Attention la deadline pour : <?php echo $notif['nom_du_controle']; ?></small></a></li>
<?php
        $query=$pdo->prepare("UPDATE controle set lu = ? where id= ?");
        $query->execute([1, $notif['id']]);
      }
?>

  
    </ul>
  </li>
  <ul class="navbar-nav px-3">
    <li class="nav-item text-nowrap">
      <a class="nav-link" href="logout.php">Deconnexion</a>
    </li>
  </ul>
</header>

<div class="container-fluid">
  <div class="row">
    <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
      <div class="position-sticky pt-3">
        <ul class="nav flex-column">
          <div class="accordion accordion-flush" id="accordionFlushExample">
            <div class="accordion-item nav-item">
              <h2 class="accordion-header" id="flush-headingOne">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                  <img src="bootstrap-icons-1.4.0/person-lines-fill.svg"> Tableau de bord
                </button>
              </h2>
              </div>
          <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
          <div class="accordion-body">
            <button type="button" class="btn  nav-link"><img src="bootstrap-icons-1.4.0/folder-plus.svg"><a href="mission.php"> Activité</a></button>
            <button type="button" class="btn  nav-link"><img src="bootstrap-icons-1.4.0/folder-plus.svg"><a href="carnet_addresse.php"> Mon activité</button>
          </div>
        </div>
          <div class="accordion accordion-flush" id="accordionFlushExample">
            <div class="accordion-item nav-item">
              <h2 class="accordion-header" id="flush-headingOne">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                  <img src="bootstrap-icons-1.4.0/person-lines-fill.svg"> Équipe
                </button>
              </h2>
              </div>
          <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
          <div class="accordion-body">
            <button type="button" class="btn  nav-link"><img src="bootstrap-icons-1.4.0/folder-plus.svg"><a href="mission.php"> Mes missions</a></button>
            <button type="button" class="btn  nav-link"><img src="bootstrap-icons-1.4.0/folder-plus.svg"><a href="carnet_addresse.php"> Carnet d'addesses</button>
          </div>
          </div>

          <div class="accordion accordion-flush" id="accordionFlushExample">
            <div class="accordion-item nav-item">
              <h2 class="accordion-header" id="flush-headingTree">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTree" aria-expanded="false" aria-controls="flush-collapseTree">
                  <img src="bootstrap-icons-1.4.0/folder-check.svg"> Documentation
                </button>
              </h2>
              </div>
          <div id="flush-collapseTree" class="accordion-collapse collapse" aria-labelledby="flush-headingTree" data-bs-parent="#accordionFlushExample">
          <div class="accordion-body">
            <button type="button" class="btn  nav-link"><img src="bootstrap-icons-1.4.0/folder-plus.svg"><a href="index.php">Mes contrôles</a></button>
            <button type="button" class="btn  nav-link"><img src="bootstrap-icons-1.4.0/folder-plus.svg"><a href="telechargement.php">Téléchargements</a></button>
          </div>
        </div>
        <div class="accordion accordion-flush" id="accordionFlushExample">
            <div class="accordion-item nav-item">
              <h2 class="accordion-header" id="flush-headingFor">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFor" aria-expanded="false" aria-controls="flush-collapseFor">
                  <img src="bootstrap-icons-1.4.0/info.svg"> Aide
                </button>
              </h2>
              </div>
          <div id="flush-collapseFor" class="accordion-collapse collapse" aria-labelledby="flush-headingFor" data-bs-parent="#accordionFlushExample">
          <div class="accordion-body">
            <button type="button" class="btn  nav-link"><img src="bootstrap-icons-1.4.0/folder-plus.svg"><a href="index.php">FAQ</a></button>
            <button type="button" class="btn  nav-link"><img src="bootstrap-icons-1.4.0/folder-plus.svg"><a href="telechargement.php">Nous contacter</a></button>
          </div>
        </div>
        </ul>

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
          <span>Outils :</span>
          <a class="link-secondary" href="" aria-label="ajouter un controle">
          <img src="bootstrap-icons-1.4.0/tools.svg">
          </a>
        </h6>
        <ul class="nav flex-column mb-2">
          <li class="nav-item">
            <a class="nav-link" href="form_doc.php">
            <img src="bootstrap-icons-1.4.0/file-earmark-plus.svg">
              Ajouter un contrôle
            </a>
          </li>
          <li class="nav-item">
            <button type="button" class="btn  nav-link" data-bs-toggle="modal" data-bs-target="#ajoutCategorie"><img src="bootstrap-icons-1.4.0/folder-plus.svg"> <small><i>Ajouter une catégorie</i></small></button>
          </li>
          <li class="nav-item">
            <button type="button" class="btn  nav-link" data-bs-toggle="modal" data-bs-target="#SupCategorie"><img src="bootstrap-icons-1.4.0/folder-minus.svg"> <small><i>Supprimer une catégorie</i></small></button>
          </li>
          <li class="nav-item">
            <button type="button" class="btn  nav-link" id="delete-checkbox"><img src="bootstrap-icons-1.4.0/file-earmark-minus.svg"> <small><i>Supprimer les contrôles</i></small></button>
          </li> 
        </ul>
    


        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
          <span>Filtres : </span>
          <a class="link-secondary" href="" aria-label="ajouter un controle">
          <img src="bootstrap-icons-1.4.0/filter.svg">
          </a>
        </h6>
        <ul class="nav flex-column mb-2">
          <li class="nav-item">
            <a class="nav-link" href="#">
            <img src="bootstrap-icons-1.4.0/briefcase.svg">
              Par Mission : 
              <select name="mission" class="form-control form-control-white w-100 filter-select">
                <option>Selectionner une mission :</option>
                <?php foreach($missions as $mission){ ?>
                  <option value="<?php echo $mission['mission_nom']; ?>"><?php echo $mission['mission_nom']; ?></option>
                <?php } ?>
              </select>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
            <img src="bootstrap-icons-1.4.0/bar-chart-steps.svg">
              Par Catégorie :
              <select name="categorie" class="form-control form-control-white w-100 filter-select">
                <option>Selectionner une categorie :</option>
                <?php foreach($categories as $categorie){ ?>
                  <option value="<?php echo $categorie['categorie_nom']; ?>"><?php echo $categorie['categorie_nom']; ?></option>
                <?php } ?>
              </select>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
            <img src="bootstrap-icons-1.4.0/ui-checks.svg">
              Par Statut :
              <select name="statut" class="form-control form-control-white w-100 filter-select">
                <option>Selectionner un statut :</option>
                <option value="Non debute">Non debute</option>
                <option value="Documente">Documente</option>
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
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-colonnes">
           Modifier les colonnes
      </button>
      <!-- Modal -->
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
              <label class="form-check-label" for="mission">mission</label>
            </div>

            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="categorie" <?php if($colonnes['categorie'] == 0){ echo "checked"; }?>>
              <label class="form-check-label" for="categorie">categorie</label>
            </div>

            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="nom_du_controle" <?php if($colonnes['nom_du_controle'] == 0){ echo "checked"; }?>>
              <label class="form-check-label" for="nom_du_controle">nom du controle</label>
            </div>

            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="deadline" <?php if($colonnes['deadline'] == 0){ echo "checked"; }?>>
              <label class="form-check-label" for="deadline">deadline</label>
            </div>

            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="affecte_a" <?php if($colonnes['affecte_a'] == 0){ echo "checked"; }?>>
              <label class="form-check-label" for="affecte_a">affecte a </label>
            </div>

            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="statut" <?php if($colonnes['statut'] == 0){ echo "checked"; }?>>
              <label class="form-check-label" for="statut">statut</label>
            </div>

            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="niveau_de_risque" <?php if($colonnes['niveau_de_risque'] == 0){ echo "checked"; }?>>
              <label class="form-check-label" for="niveau_de_risque">niveau de risque</label>
            </div>

            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="design" <?php if($colonnes['design'] == 0){ echo "checked"; }?>>
              <label class="form-check-label" for="design">design</label>
            </div>

            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="efficacite" <?php if($colonnes['efficacite'] == 0){ echo "checked"; }?>>
              <label class="form-check-label" for="efficacite">efficacite</label>
            </div>

            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="commentaires" <?php if($colonnes['commentaires'] == 0){ echo "checked"; }?>>
              <label class="form-check-label" for="commentaires">commentaires</label>
            </div>


            </div>
            <div class="modal-footer">
              <button type="submit" id="edit-colonne" class="btn btn-primary">Sauvegarder</button>
            </div>

          </div>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th>#</th>
              <?php if($colonnes['mission'] == "0"){ ?>
              <th>Mission</th>
              <?php } ?>
              <?php if($colonnes['categorie'] == "0"){ ?>
              <th>Categorie</th>
              <?php } ?>
              <?php if($colonnes['nom_du_controle'] == "0"){ ?>
              <th>Nom du controle</th>
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
           
          <tbody>
            <?php foreach($categories as $categorie){ ?>
            <tr>
              <th colspan="11"><?php echo $categorie['categorie_nom']; ?></th>
            </tr>
            <?php foreach($controles as $controle){ 
              if($controle['categorie_nom'] == $categorie['categorie_nom']){ ?>
           <tr>
              <td><input class="controle-checkbox" type='checkbox' data-id="<?php echo $controle['id']; ?>"></td>
              <?php if($colonnes['mission'] == "0"){ ?>
              <td class="search-mission search-recherche"><?php echo $controle['mission_nom']; ?></td>
              <?php } ?>
              <?php if($colonnes['categorie'] == "0"){ ?>
              <td class="search-categorie search-recherche"><?php echo $controle['categorie_nom']; ?></td>
              <?php } ?>
              <?php if($colonnes['nom_du_controle'] == "0"){ ?>
              <td class="search-recherche"><?php echo $controle['nom_du_controle']; ?></td>
              <?php } ?>
              <?php if($colonnes['deadline'] == "0"){ ?>
              <td><?php echo $controle['deadline']; ?></td>
              <?php } ?>
              <?php if($colonnes['affecte_a'] == "0"){ ?>
              <td class="search-recherche"><?php 
               $email = "";
               if($controle["statut"] == 'Non debute'){
                 $email = $controle["email_utilisateur_realise_par"];
               } elseif($controle["statut"] == 'Documente'){
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
              $color_statut = "";
              if($controle["statut"] == 'Non debute'){
                $color_statut = "red";
              }
              elseif($controle["statut"] == 'Documente'){
                $color_statut = "orange";
              }elseif($controle["statut"] == 'Revu'){
                $color_statut = "yellowgreen";
              }else{
                $color_statut = "green";
              }    

             
              $color_risque = "";
              if($controle["niveau_de_risque"] == 'Eleve'){
                $color_risque = "red";
              }
              elseif($controle["niveau_de_risque"] == 'Moyen'){
                $color_risque = "orange";
              }else{
                $color_risque = "green";
              }    

              $color_design = "";
              if($controle["design"] == 'Non-effectif'){
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
              if($controle["efficacite"] == 'Non-effectif'){
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
              <td class='statut' style="color: <?php echo $color_statut ;?>;">
              <span class='search-statut span-statut search-recherche'><?php echo $controle['statut'] ;?></span>
                    <select class='statut-select' data-id='<?php echo $controle['id'] ;?>'> 
                    <option value='Non debute'>Non debute</option>
                    <option value='Documente'>Documente</option>
                    <option value='Revu'>Revu</option>
                    <option value='Sign-off'>Sign-off</option>
                    </select>
              
              </td>
              <?php } ?>

              <?php if($colonnes['niveau_de_risque'] == "0"){ ?>
              <td class='niv_risque' style="color: <?php echo $color_risque ;?>;">
              <span class='span_niv_risque search-recherche'><?php echo $controle['niveau_de_risque'] ;?></span>
                    <select class='niv-risque-select' data-id='<?php echo $controle['id'] ;?>'> 
                    <option value='Eleve'>Eleve</option>
                    <option value='Moyen'>Moyen</option>
                    <option value='Faible'>Faible</option>
                    </select>
              </td>
              <?php } ?>
              <?php if($colonnes['design'] == "0"){ ?>
                <td class='design' style="color: <?php echo $color_design ;?>;">
              <span class='span-design search-recherche'><?php echo $controle['design'] ;?></span>
                    <select class='design-select' data-id='<?php echo $controle['id'] ;?>'> 
                    <option value='Non-effectif'>Non-effectif</option>
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
                    <option value='Non-effectif'>Non-effectif</option>
                    <option value='Remarque mineure'>Remarque mineure</option>
                    <option value='Remarque majeure'>Remarque majeure</option>
                    <option value='Effectif'>Effectif</option>
              <?php } ?>
              <?php if($colonnes['commentaires'] == "0"){ ?>
              <td><?php echo getLastComment($pdo, $controle['id']); ?></td>
              <?php } ?>
            </tr>
            <?php } } ?>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="ajoutCategorie" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="index.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ajout d'une catégorie</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col-sm-9">
                        <label for="mission" class="form-label"><i>Qu'elle est la mission ?</i></label>
                        <select name="mission" class="form-select">
                        <option>Selectionner une mission :</option>
                        <?php foreach($missions as $mission){ ?>
                        <option value="<?php echo $mission['mission_id']; ?>"><?php echo $mission['mission_nom']; ?></option>
                        <?php } ?>
                        </select>
                        <div class="invalid-feedback">
                        Ce champ est obliagtoire.
                        </div>
                    </div>
                    <div class="col-sm-9">
                        <label for="categorie_nom" class="form-label">Nom de la catégorie</label>
                        <div class="input-group has-validation">
                        <span class="input-group-text"><span><img src="bootstrap-icons-1.4.0/file-text.svg"></span></span>
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

<div class="modal fade" id="SupCategorie" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="index.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Supprimer une catégorie</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col-sm-9">
                        <label for="mission" class="form-label"><i>Qu'elle est la mission ?</i></label>
                        <select name="mission" class="form-select">
                        <option>Selectionner une mission :</option>
                        <?php foreach($missions as $mission){ ?>
                        <option value="<?php echo $mission['mission_id']; ?>"><?php echo $mission['mission_nom']; ?></option>
                        <?php } ?>
                        </select>
                        <div class="invalid-feedback">
                        Ce champ est obliagtoire.
                        </div>
                    </div>
                    <div class="col-sm-9">
                        <label for="categorie_nom" class="form-label">Nom de la catégorie</label>
                        <div class="input-group has-validation">
                        <span class="input-group-text"><span><img src="bootstrap-icons-1.4.0/file-text.svg"></span></span>
                        <input type="text" name="categorie_nom" class="form-control" id="categorie_nom" placeholder="Nom de la catégorie" required>
                        <div class="invalid-feedback">
                        Ce champ est obliagtoire.
                        </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" name="supprimer_cat">Supprimer</button>
                </div>
            </form>
        </div>
    </div>
</div>
<footer class="my-5 pt-5 text-muted text-center text-small">
  <p class="mb-1">&copy; 2017–2021 MAZARS SAS</p>
  <ul class="list-inline">
    <li class="list-inline-item"><a href="#">Privacy</a></li>
    <li class="list-inline-item"><a href="#">Terms</a></li>
    <li class="list-inline-item"><a href="#">Support</a></li>
  </ul>
</footer>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"
        integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg=="
        crossorigin="anonymous"></script>
    <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
  
<script>

  // statut 
  var span_statut = document.querySelectorAll('.span-statut');

for(var i = 0; i < span_statut.length; i++){
  const on = span_statut[i];
    on.addEventListener('dblclick', () => {
      var td = on.closest('td');
      td.classList.add('active');
    })
}

var select_statut = document.querySelectorAll('.statut-select');

for(var i = 0; i < select_statut.length; i++){
  const on = select_statut[i];
    on.addEventListener('change', () => {
      var td = on.closest('td');
      var select_value = on.value;
      var id = on.getAttribute("data-id");
      var span = td.querySelector('span');

      

      $.ajax({
          url: "edit_statut.php",
          method: "POST",
          data: "id=" + id + "&value=" + select_value,
          success: function() {
            span.innerHTML = select_value;
            var color ="";
            
            switch (select_value) {
              case 'Non debute':
                color = "red";
                break;
              case 'Documente':
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
// fin statut

// niveau de risque 
var span_niv_risque = document.querySelectorAll('.span_niv_risque');

for(var i = 0; i < span_niv_risque.length; i++){
  const on = span_niv_risque[i];
    on.addEventListener('dblclick', () => {
      var td = on.closest('td');
      td.classList.add('active');
    })
}


var select_niv_risque = document.querySelectorAll('.niv-risque-select');

for(var i = 0; i < select_niv_risque.length; i++){
  const on = select_niv_risque[i];
    on.addEventListener('change', () => {
      var td = on.closest('td');
      var select_value = on.value;
      var id = on.getAttribute("data-id");
      var span = td.querySelector('span');

      

      $.ajax({
          url: "edit_niv_risque.php",
          method: "POST",
          data: "id=" + id + "&value=" + select_value,
          success: function() {
            span.innerHTML = select_value;
            var color ="";
            
            switch (select_value) {
              case 'Eleve':
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

// modif design
var span_design = document.querySelectorAll('.span-design');

for(var i = 0; i < span_design.length; i++){
  const on = span_design[i];
    on.addEventListener('dblclick', () => {
      var td = on.closest('td');
      td.classList.add('active');
    })
}


var select_design = document.querySelectorAll('.design-select');

for(var i = 0; i < select_design.length; i++){
  const on = select_design[i];
    on.addEventListener('change', () => {
      var td = on.closest('td');
      var select_value = on.value;
      var id = on.getAttribute("data-id");
      var span = td.querySelector('span');

      

      $.ajax({
          url: "edit_design.php",
          method: "POST",
          data: "id=" + id + "&value=" + select_value,
          success: function() {
            span.innerHTML = select_value;
            var color ="";
            
            switch (select_value) {
              case 'Non-effectif':
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


// modif efficacite
var span_efficacite = document.querySelectorAll('.span-efficacite');

for(var i = 0; i < span_efficacite.length; i++){
  const on = span_efficacite[i];
    on.addEventListener('dblclick', () => {
      var td = on.closest('td');
      td.classList.add('active');
    })
}


var select_efficacite = document.querySelectorAll('.efficacite-select');

for(var i = 0; i < select_efficacite.length; i++){
  const on = select_efficacite[i];
    on.addEventListener('change', () => {
      var td = on.closest('td');
      var select_value = on.value;
      var id = on.getAttribute("data-id");
      var span = td.querySelector('span');

      

      $.ajax({
          url: "edit_efficacite.php",
          method: "POST",
          data: "id=" + id + "&value=" + select_value,
          success: function() {
            span.innerHTML = select_value;
            var color ="";
            
            switch (select_value) {
              case 'Non-effectif':
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
//fin modifier colonne

// filtres
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
             /*filter_input[i].addEventListener('change', (e) => {
                 filterRow(e.target)
             });*/
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

// suppression controle checkbox

  var checkbox_all = document.querySelectorAll('.controle-checkbox');
  function id_checked() {
    var tab_id_checked = [];
    for (var i = 0; i < checkbox_all.length; i++) {
        const on = checkbox_all[i];
        var data_id = on.getAttribute("data-id");
        if (on.checked == true) {
            tab_id_checked.push(data_id)
        }
    }
    return tab_id_checked;
}

  var bouton_delete = document.getElementById('delete-checkbox');
  bouton_delete.addEventListener('click', () => {
    all_id_client_checked = id_checked();
    $.ajax({
            url: "delete_controle.php",
            method: "POST",
            data: "array_id=" + all_id_client_checked,
            success: function() {
                window.location.reload();
            }
        })
  })




// suppression controle checkbox

  </script>
  
  
    </body>
</html>


<?php

}else{
  header('Location:login.php');
} ?>