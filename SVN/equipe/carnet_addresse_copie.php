<?php 
    include 'application/bdd_connection.php';
    if(isset($_SESSION['admin_email']) && $_SESSION['admin_email'] !=''){
      
        $query='SELECT * FROM utilisateur order by nom ASC';
        $resultSet = $pdo->query($query);
        $contacts = $resultSet->fetchAll();
      
        if(isset($_POST['ajouter_personne'])){
          $query=$pdo->prepare("insert into utilisateur (nom, prenom, role_mission, email, numero_telephone, mot_de_passe) values(?,?,?,?,?,?)");
          $query->execute([$_POST['nom'],$_POST['prenom'],$_POST['role'],$_POST['email'],$_POST['num_tel'],"534c9a5b050c2b082b4c6b85766bdee4"]);
          header('Location:carnet_addresse.php');
        }

        if(isset($_POST['sup_personne'])){
          $query=$pdo->prepare("DELETE FROM utilisateur WHERE utilisateur.email= ?");
          $query->execute([$_POST['email']]);
          header('Location:carnet_addresse.php');
        }

    if(isset($_GET['email'])){
      $query=$pdo->prepare("SELECT * FROM utilisateur WHERE email= ?");
      $query->execute([$_GET['email']]);
      $info_contacts=$query->fetchAll();
    }
?>
<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ajout d'un contrôle</title>

    

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
<a class="navbar-info col-md-3 col-lg-2 me-0 px-3" href="#"><img style="height : 2àpx; width:150px;" src="mazars-logo.png"></a>
  <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <input class="form-control form-control-dark w-100 filter-input" type="text" placeholder="Recherche" name="recherche"  aria-label="Search">
  <li class="nav-item dropdown">
    <a class="nav-link" href="#" id="dropdown01" data-bs-toggle="dropdown" aria-expanded="false"><span id="notifs-count"></span><img src="bootstrap-icons-1.4.0/bell.svg">   </a>
    <ul id="notifs-wrapper" class="dropdown-menu" aria-labelledby="Notfications">
<?php
      include './notif/action.php';
      foreach ($notifsStatut as $notifStatut) {
         $classNotifs = '';
        if($notifStatut['lu_statut']){
          $classNotifs = 'notif-read';
        } 
?>
        <li class="<?php echo $classNotifs; ?>"><a class="dropdown-item-left" href="#"><small><i><i><br>Le statut à changé en <?php echo $notifStatut['statut']; ?> pour : <?php echo $notifStatut['nom_du_controle']; ?></small></a></li>
<?php
        //$query=$pdo->prepare("UPDATE controle set lu_statut = ? where id= ?");
       // $query->execute([1, $notifStatut['id']]);
      }
      foreach ($notifs as $notif) {

        $classNotifs = '';
        if($notif['lu']){
          $classNotifs = 'notif-read';
        } 
?>
        <li class="<?php echo $classNotifs; ?>"><a class="dropdown-item-left" href="#"><small ><i><?php echo $notif['deadline']; ?>, <i><br>Attention la deadline pour : <?php echo $notif['nom_du_controle']; ?></small><strong><small> arrive bientôt a échéance.</small></strong></a></li>
<?php
        //$query=$pdo->prepare("UPDATE controle set lu = ? where id= ?");
        //$query->execute([1, $notif['id']]);
      }
?>

  
    </ul>
  </li>
  <li>
    <div class="dropdown">
    <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle" id="dropdownUser2" data-bs-toggle="dropdown" aria-expanded="false">
      <strong><?php echo $_SESSION['admin_nom']; ?></strong>
    </a>
    <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser2">
      <li><a class="dropdown-item" href="#">Paramètres du comptes</a></li>
      <li><a class="dropdown-item" href="profil.php">Profil</a></li>
      <li><a class="dropdown-item" href="nous_contacter.php">Nous contacter</a></li>
      <li><hr class="dropdown-divider"></li>
      <li><a class="dropdown-item" href="logout.php">Déconnexion</a></li>
    </ul>
  </div>
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
            <button type="button" class="btn  nav-link"><img src="bootstrap-icons-1.4.0/folder-plus.svg"><a href="nous_contacter.php"> Nous contacter</a></button>
          </div>
        </div>
        </ul>

        
        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>Outils :</span>
            <a class="link-secondary" href="" aria-label="">
            <img src="bootstrap-icons-1.4.0/tools.svg">
            </a>
          </h6>
          <ul class="nav flex-column mb-2">
          <li class="nav-item">
          <?php if($_SESSION['admin_privilege'] == 'Senior Manager' || $_SESSION['admin_privilege'] == 'Associé'){ ?>
            <button type="button" class="btn  nav-link" data-bs-toggle="modal" data-bs-target="#ajoutPersonne"><img src="bootstrap-icons-1.4.0/folder-plus.svg"> <small><i>Ajouter une personne</i></small></button>
          </li>
          <li class="nav-item">
            <button type="button" class="btn  nav-link" data-bs-toggle="modal" data-bs-target="#sup_personne"><img src="bootstrap-icons-1.4.0/folder-plus.svg"> <small><i>Supprimer une personne</i></small></button>
          </li>
          <?php } ?>
          </ul>
      </div>
    </nav>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <br>
    <div class="row g-3">
      <div class="col-md-5 col-lg-4">
        <h4 class="d-flex justify-content-between align-items-center mb-3">
          <span class="text-muted">Mes collaborateurs</span>
        </h4>
        <ul class="list-group mb-3">

        <?php foreach($contacts as $contact) { ?>

          <li class="list-group-item d-flex justify-content-between lh-sm ">
            <div>
              <h6 class="my-0"><a style="text-decoration : none;" href="carnet_addresse.php?email=<?php echo $contact['email']; ?>"><?php echo $contact['nom']." ".$contact['prenom']; ?></a></h6>
              <small class="text-muted"></small>
            </div>
          </li>
        <?php } ?>

        </ul>
      <br>
      </div>
      <div class="col-md-7">
            <?php if(isset($_GET['email']) && !empty($_GET['email']) ){?>

          <h4 class="d-flex justify-content-between align-items-center mb-3">
              <span class="text-muted">Informations sur cette personne :</span>
            </h4>
          <ul class="list-unstyled">
          <?php foreach($info_contacts as $contact){ ?>
            <h5>
            <li style="text-decoration : none;">Nom : <?php echo $contact['nom']; ?></li>
            <li style="text-decoration : none;">Prénom : <?php echo $contact['prenom']; ?></li>
            <li style="text-decoration : none;">Courriel : <?php echo $contact['email']; ?></li>
            <li style="text-decoration : none;">Rôle initial : <?php echo $contact['role_mission']; ?></li>
            <li style="text-decoration : none;">Numéro de téléphone : <?php echo $contact['numero_telephone']; ?></li>
            </h5>
            <?php } ?>
          </ul>
<?php } ?>
    </main>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="ajoutPersonne" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog">
        <div class="modal-content">
            <form action="carnet_addresse.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ajouter une personne</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col-sm-9">
                        <label for="nom" class="form-label"><i>Nom</i></label>
                        <div class="input-group ">
                      <input type="text" class="form-control" id="nom" name="nom" placeholder="ex : Durand">
                      </div>
                    </div>
                    <div class="col-sm-9">
                    <label for="prenom" class="form-label"><i>Prenom</i></label>
                    <div class="input-group ">
                      <input type="text" class="form-control" id="prenom" name="prenom" placeholder="ex : Paul">
                      </div>
                    </div>
                    <div class="col-sm-9">
                    <label for="role" class="form-label"><i>Role :</i></label>
                    <div class="input-group">
                      <input type="text" class="form-control" id="role" name="role" placeholder="ex : Junior">
                      </div>
                    </div>
                    <div class="col-sm-9">
                    <label for="email" class="form-label"><i>Email</i></label>
                        <div class="input-group">
                      <input type="text" class="form-control" name ="email" id="email" placeholder="ex : exemple@mazars.fr">
                      </div>
                    </div>
                    <div class="col-sm-9">
                    <label for="num_tel" class="form-label"><i>Numero de telephone :</i></label>
                    <div class="input-group">
                      <input type="text" class="form-control" name="num_tel" id="num_tel" placeholder="ex : 0123456789">
                      </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" name="ajouter_personne">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="sup_personne" tabindex="-1" aria-labelledby="sup_personne" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="carnet_addresse.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Supprimer d'une personne</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col-sm-9">
                        <label for="email" class="form-label"><i>Qu'elle est le nom ?</i></label>
                        <select name="email" class="form-select">
                        <option>Selectionner un nom :</option>
                          <?php foreach($contacts as $contact){ ?>
                            <option value="<?php echo $contact['email']; ?>"><?php echo $contact['nom']." ".$contact['prenom']; ?></option>
                          <?php } ?>
                        </select>
                        <div class="invalid-feedback">
                          Ce champ est obliagtoire.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" name="sup_personne">Supprimer</button>
                </div>
            </form>
        </div>
    </div>
</div>



<footer class="my-5 pt-5 text-muted text-center text-small">

</footer>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"
        integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg=="
        crossorigin="anonymous"></script>
<script src="bootstrap.bundle.min.js"></script>
<script>
  //role
  var span_role = document.querySelectorAll('.span-role');

for(var i = 0; i < span_role.length; i++){
  const on = span_role[i];
    on.addEventListener('dblclick', () => {
      var td = on.closest('td');
      td.classList.add('active');
    })
}

var select_role = document.querySelectorAll('.role-select');

for(var i = 0; i < select_role.length; i++){
  const on = select_role[i];
    on.addEventListener('change', () => {
      var td = on.closest('td');
      var select_value = on.value;
      var id = on.getAttribute("data-id");
      var span = td.querySelector('span');

      

      $.ajax({
          url: "edit_role2.php",
          method: "POST",
          data: "id=" + id + "&value=" + select_value,
          success: function() {
            span.innerHTML = select_value;
            td.classList.remove('active');
          }
      })

      
    })
}
// fin role
  </body>
</html>

<?php

}else{
  header('Location:login.php');
} ?>