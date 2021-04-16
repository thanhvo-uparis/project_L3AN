<?php
include 'application/bdd_connection.php';
if(isset($_SESSION['admin_email']) && $_SESSION['admin_email'] !=''){

  
  $query=$pdo->prepare("SELECT * FROM set_colonne WHERE id = ?");
    $query->execute([1]);
    $colonnes=$query->fetch(); 
  
  if(isset($_POST['submitFichier'])){

  //reception fichier.
  if(is_uploaded_file($_FILES['fichier']['tmp_name'])){

    // echo $_FILES['fichier']['tmp_name'];
    // echo $_FILES['fichier']['name']
    $destination = uniqid('p_').$_FILES['fichier']['name'];
    move_uploaded_file($_FILES['fichier']['tmp_name'], 'medias/'.$destination);

    // var_dump($_POST);
    // exit();
    
    $query=$pdo->prepare("INSERT INTO fichier_controle (id_controle, nom_fichier, chemin_fichier, date_depot, email_utilisateur, commentaires_fichier, categorie_fichier) VALUES (?,?,?,?,?,?,?)");
    $query->execute([$_GET['id'],$_FILES['fichier']['name'], $destination, date('Y-m-d'), $_SESSION['admin_email'], $_POST['descriptions'], $_POST['categorie_fichier']]);
    
    
  }
  }
  $query=$pdo->prepare("SELECT fichier_controle.id as id_fichier, fichier_controle.categorie_fichier ,fichier_controle.commentaires_fichier, fichier_controle.id_controle as id_controle_fichier ,fichier_controle.nom_fichier, fichier_controle.chemin_fichier, fichier_controle.date_depot, fichier_controle.email_utilisateur as email_fichier, controle.id as id_controle, controle.nom_du_controle FROM fichier_controle INNER JOIN controle ON controle.id = fichier_controle.id_controle WHERE fichier_controle.id_controle = ?");
  $query->execute([$_GET['id']]);
  $fichiers=$query->fetchAll(); 
 
  if(isset($_GET['del'])){
    $id= $_GET['del'];
    $query=$pdo->prepare("SELECT * FROM fichier_controle WHERE id = ?");
    $query->execute([$id]);
    $fichier_unique=$query->fetch(); 
    $lienFichier = "medias/".$fichier_unique['chemin_fichier'];
    unlink($lienFichier);

    $query=$pdo->prepare("DELETE FROM fichier_controle WHERE id = ?");
    $query->execute([$id]);

    header('Location: telechargement.php?id='.$_GET['id']);
  }

  $a = 'Preuve';
  $b = 'Fiches de travailles';
  $c = 'Information produced by Entity';
?>
<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Téléchargment</title>


    <!-- Bootstrap core CSS -->
<link href="bootstrap.min.css" rel="stylesheet">

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

    
    <!-- Custom styles for this template -->
    <link href="controle.css" rel="stylesheet">
  </head>
  <body>
    
<header class="navbar navbar-info sticky-top bg-info flex-md-nowrap p-0 shadow">
  <a class="navbar-info col-md-3 col-lg-2 me-0 px-3" href="#"><img  style="height : 30px; width:150px;" src="mazars-logo.png"></a>
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
            <div class="accordion-item">
              <h2 class="accordion-header" id="flush-headingOne">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                  <img src="bootstrap-icons-1.4.0/graph-up.svg">Tableau de bord
                </button>
              </h2>
            </div>
          <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
          <div class="accordion-body">
            <button type="button" class="btn nav-link"><img src="bootstrap-icons-1.4.0/folder-plus.svg"><a href="mission.php"> Activité</a></button>
            <button type="button" class="btn nav-link"><img src="bootstrap-icons-1.4.0/folder-plus.svg"><a href="carnet_addresse.php"> Mon activité</a></button>
          </div>
        </div>
          <div class="accordion accordion-flush" id="accordionFlushExample">
            <div class="accordion-item disabled">
              <h2 class="accordion-header" id="flush-headingOne">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                  <img src="bootstrap-icons-1.4.0/person-lines-fill.svg"> Équipe
                </button>
              </h2>
              </div>
          <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
          <div class="accordion-body">
            <button type="button" class="btn nav-link"><img src="bootstrap-icons-1.4.0/folder-plus.svg"><a href="mission.php"> Mes missions</a></button>
            <button type="button" class="btn nav-link"><img src="bootstrap-icons-1.4.0/folder-plus.svg"><a href="carnet_addresse.php"> Carnet d'addesses</a></button>
          </div>
          </div>

          <div class="accordion accordion-flush" id="accordionFlushExample">
            <div class="accordion-item">
              <h2 class="accordion-header" id="flush-headingTree">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTree" aria-expanded="false" aria-controls="flush-collapseTree">
                  <img src="bootstrap-icons-1.4.0/folder-check.svg"> Documentation
                </button>
              </h2>
              </div>
          <div id="flush-collapseTree" class="accordion-collapse collapse" aria-labelledby="flush-headingTree" data-bs-parent="#accordionFlushExample">
          <div class="accordion-body">
            <button type="button" class="btn nav-link"><img src="bootstrap-icons-1.4.0/folder-plus.svg"><a href="index.php"> Mes contrôles</a></button>
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
            <button type="button" class="btn  nav-link"><img src="bootstrap-icons-1.4.0/folder-plus.svg"><a href="FAQ.html"> FAQ</a></button>
            <button type="button" class="btn  nav-link"><img src="bootstrap-icons-1.4.0/folder-plus.svg"><a href="FAQ.html"> Nous contacter</a></button>
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
            <button type="button" class="btn  nav-link" data-bs-toggle="modal" data-bs-target="#ajoutFichier"><img src="bootstrap-icons-1.4.0/folder-plus.svg"> <small><i>Ajouter un fichier</i></small></button>
          </li>
        </ul>
      </div>
    </nav>
    
    

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <br>
      <!-- Modal -->
      <div class="modal fade" id="ajoutFichier" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
          <form method="POST" enctype="multipart/form-data">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Ajout d'un fichier</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="input-group mb-3">
              <input type="file" class="form-control" name="fichier" id="inputGroupFile02">
            </div>
            </div>
                    <div style="padding-left : 10px;" class="col-sm-11">
                        <label for="mission" class="form-label"><i>Qu'elle est la catégorie ?</i></label>
                        <select class="form-select" name="categorie_fichier" required>
                            <option value="">Choissisez une catégorie</option>
                            <option value="Preuve">Preuve</option>
                            <option value="Fiches de travailles">Fiches de travailles</option>
                            <option value="Information produced by Entity">Information produced by Entity</option>
                        </select>
                        <div class="invalid-feedback">
                        Ce champ est obliagtoire.
                        </div>
                    </div>
            <div style="padding-left : 10px;" class="col-sm-11">
                    <label for="descriptions" class="form-label"><i>Description du téléchargement</i> </label>
                    <input type="text" class="form-control" name="descriptions" id="descriptions" placeholder="Ex : Fiche de synthése dossier n°1." required>
                    <div class="invalid-feedback">
                      Ce champ est obliagtoire.
                    </div>
                  </div>
                  <br>
            <div class="modal-footer">
                <button type="submit" name="submitFichier" class="btn btn-succes">Ajouter</button>
            </div>
            </form>

    </div>
  </div>
</div>
      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
                  <th>Controle</th>
                  <th>Nom fichier</th>
                  <th>Date de dépot</th>
                  <th>Déposer par</th>
                  <th>Descriptions</th>
                  <th>options</th>
           </tr>
          </thead>
          <tbody>
          <tr><td colspan="6"><h5 class="my-0"><?php echo $a; ?></h5></td></tr>
          <?php foreach($fichiers as $fichier){ ?>
          <?php if($fichier['categorie_fichier'] == $a ){ ?>
          <tr>
              <td><?php echo $fichier['nom_du_controle']; ?></td>
              <td><a href="medias/<?php echo $fichier['chemin_fichier'];?>"><?php echo $fichier['nom_fichier']; ?></a></td>
              <td><?php echo $fichier['date_depot']; ?></td>
              <td><?php echo $fichier['email_fichier']; ?></td>
              <td><?php echo $fichier['commentaires_fichier']; ?></td>
              <td><a id="delete_telechargement" href="telechargement.php?id=<?php echo $_GET['id']; ?>&del=<?php echo $fichier['id_fichier']; ?>"><img src="bootstrap-icons-1.4.0/trash.svg"></a></td>
            </tr>
            <?php } ?>
            <?php } ?>
            <tr><td colspan="6"><h5 class="my-0"><?php echo $b; ?></h5></td></tr>
            <?php foreach($fichiers as $fichier){ ?>
            <?php if($fichier['categorie_fichier'] == $b ){ ?>
            <tr>
              <td><?php echo $fichier['nom_du_controle']; ?></td>
              <td><a href="medias/<?php echo $fichier['chemin_fichier'];?>"><?php echo $fichier['nom_fichier']; ?></a></td>
              <td><?php echo $fichier['date_depot']; ?></td>
              <td><?php echo $fichier['email_fichier']; ?></td>
              <td><?php echo $fichier['commentaires_fichier']; ?></td>
              <td><a id="delete_telechargement" href="telechargement.php?id=<?php echo $_GET['id']; ?>&del=<?php echo $fichier['id_fichier']; ?>"><img src="bootstrap-icons-1.4.0/trash.svg"></a></td>
            </tr>
            <?php } ?>
            <?php } ?>
            <tr><td colspan="6"><h5 class="my-0"><?php echo $c; ?></h5></td></tr>
            <?php foreach($fichiers as $fichier){ ?>
            <?php if($fichier['categorie_fichier'] == $c ){ ?>
            <tr>
              <td><?php echo $fichier['nom_du_controle']; ?></td>
              <td><a href="medias/<?php echo $fichier['chemin_fichier'];?>"><?php echo $fichier['nom_fichier']; ?></a></td>
              <td><?php echo $fichier['date_depot']; ?></td>
              <td><?php echo $fichier['email_fichier']; ?></td>
              <td><?php echo $fichier['commentaires_fichier']; ?></td>
              <td><a id="delete_telechargement" href="telechargement.php?id=<?php echo $_GET['id']; ?>&del=<?php echo $fichier['id_fichier']; ?>"><img src="bootstrap-icons-1.4.0/trash.svg"></a></td>
            </tr>
            <?php } ?>
            <?php }?>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</div>


<footer class="my-5 pt-5 text-muted text-center text-small">
<p>&copy;Copyright 2021 - Mazars - France Inc. &middot;</p>
<p class="float"><a href="#">Retourner en haut</a></p>
</footer>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"
        integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg=="
        crossorigin="anonymous"></script>
    <script src="bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
    <script>

    //confirmation de la suppresion d'un téléchargement.
    var bouton_confirm_delete = document.getElementById('delete_telechargement');
    bouton_confirm_delete.addEventListener('click', (e) =>{
      if (confirm("Voulez-vous vraiment supprimer ?")) {
            // Clic sur OK
      } else {
            e.preventDefault();
      }
})
    </script>
    </body>
</html>


<?php
}else{
  header('Location:login.php');
} 
?>