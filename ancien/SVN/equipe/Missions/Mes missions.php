<?php 
    //Chemin vers le fichier qui permet la connexion à la base de donnée en local 
    include 'application/bdd_connection.php';
    if(isset($_SESSION['admin_email']) && $_SESSION['admin_email'] !=''){

    //Requete qui séléctionne tout ce qui concerne 
    $query='SELECT * FROM equipe INNER JOIN mission ON equipe.id_mission = mission.mission_id INNER JOIN utilisateur ON equipe.email_utilisateur = utilisateur.email ORDER BY mission_nom';
    $resultSet = $pdo->query($query);
    $equipes = $resultSet->fetchAll(); 

    //Requete qui permet d'afficher les missions presentes dans la base de données 
    $query='SELECT * FROM mission';
    $resultSet = $pdo->query($query);
    $missions = $resultSet->fetchAll();

    //Requete qui permet de selectionner tout les utilisateurs présents dans la base de données 
    $query='SELECT * FROM utilisateur';
    $resultSet = $pdo->query($query);
    $utilisateurs = $resultSet->fetchAll();

    //Requete qui initialise le role d'associe et de senior manager dans la mission 
    $query='SELECT * FROM utilisateur where role_mission="Associé" or role_mission="Senior Manager"';
    $resultSet = $pdo->query($query);
    $associes = $resultSet->fetchAll();

    //Requete qui permet l'ajout d'une personne dans la base de données 
    if(isset($_POST['ajouter_personne'])){
      $query=$pdo->prepare("insert into equipe (email_utilisateur,role, id_mission) values(?,?,?)");
      $query->execute([$_POST['utilisateur'],$_POST['role'],$_POST['mission']]);
    }
    //Requete qui permet la suppression d'une personne d'une mission dans la base de données 
    if(isset($_POST['supprimer_personne'])){
      $query=$pdo->prepare("delete from equipe where id_mission= ? and email_utilisateur = ?");
      $query->execute([$_POST['mission'],$_POST['utilisateur']]);
    }
    //Requete qui permet l'ajout d'une mission dans la base de données
    if(isset($_POST['ajouter_mission'])){
      $query=$pdo->prepare("insert into mission (mission_nom, email_proprietaire) values(?,?)");
      $query->execute([$_POST['mission'],$_SESSION['admin_email']]);
    }

    //Lié avec une requete cette fonction permet de retourner les missions où l'utilisateru a été identitifié
    function concernedByMission($pdo, $id, $email){
      $query=$pdo->prepare("SELECT * FROM equipe WHERE id_mission = ? AND email_utilisateur =?");
      $query->execute([$id,$email]);
      $results=$query->fetch();
      if($results){
        return true;
      }else{
        return false;
      }
    }

    if(isset($_GET['mission'])){
      $query=$pdo->prepare("SELECT * FROM equipe INNER JOIN mission ON equipe.id_mission = mission.mission_id where mission_id=? order by role");
      $query->execute([$_GET['mission']]);
      $info_equipes=$query->fetchAll();
    }

    //Lié avec une requete cette fonction permet d'obtenir le nom d'une personne à travers son email 
    function getNameByEmail($pdo, $email){
      $query=$pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
      $query->execute([$email]);
      $utilisateur=$query->fetch();
      return $utilisateur['nom']." ".$utilisateur['prenom'];
  }

?>


<!doctype html>

<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.80.0">
    <title>mes missions</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/dashboard/">

    

    <!-- Chemin vers le bootstrap -->
  
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

    
    <!-- chemin vers le fichier css -->
    <link href="controle.css" rel="stylesheet">
  </head>
  <body>
    
  <header class="navbar navbar-info sticky-top bg-info flex-md-nowrap p-0 shadow">
  <!-- logo mazars-->
<a class="navbar-info col-md-3 col-lg-2 me-0 px-3" href="#"><img style="height : 30px; width:150px;" src="logoMazars.png"></a>
  <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <!-- barre de recherche en haut de la page-->
  <input class="form-control form-control-dark w-100 filter-input" type="text" placeholder="Recherche" name="recherche"  aria-label="Search">
  <li class="nav-item dropdown">
    <a class="nav-link" href="#" id="dropdown01" data-bs-toggle="dropdown" aria-expanded="false"><span id="notifs-count"></span><img src="bootstrap-icons-1.4.0/bell.svg">   </a>
    <ul id="notifs-wrapper" class="dropdown-menu" aria-labelledby="Notfications">
<?php
      //chemin vers le fichier concernant les notifications
      include './action.php';
      foreach ($notifsStatut as $notifStatut) {
         $classNotifs = '';
        if($notifStatut['lu_statut']){
          $classNotifs = 'notif-read';
        } 
?>
        <li class="<?php echo $classNotifs; ?>"><a class="dropdown-item" href="#"><small><i><i><br>Le statut à changé pour <?php echo $notifStatut['statut']; ?> pour : <?php echo $notifStatut['nom_du_controle']; ?></small></a></li>
<?php
        $query=$pdo->prepare("UPDATE controle set lu_statut = ? where id= ?");
        $query->execute([1, $notifStatut['id']]);
      }
      foreach ($notifs as $notif) {

        $classNotifs = '';
        if($notif['lu']){
          $classNotifs = 'notif-read';
        } 
?>
        <li class="<?php echo $classNotifs; ?>"><a class="dropdown-item" href="#"><small ><i><?php echo $notif['deadline']; ?>, <i><br>Attention la deadline pour : <?php echo $notif['nom_du_controle']; ?></small></a></li>
<?php
        $query=$pdo->prepare("UPDATE controle set lu = ? where id= ?");
        $query->execute([1, $notif['id']]);
      }
?>

  
    </ul>
  </li>
    <!-- option de déconnexion-->
    <li>
    <div class="dropdown">
    <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle" id="dropdownUser2" data-bs-toggle="dropdown" aria-expanded="false">
      <img src="https://github.com/mdo.png" alt="user" width="32" height="32" class="rounded-circle me-2">
      <strong><?php echo $_SESSION['admin_nom']; ?></strong>
    </a>
    <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser2">
      <li><a class="dropdown-item" href="#">Paramètres du comptes</a></li>
      <li><a class="dropdown-item" href="profil.php">Profil</a></li>
      <li><a class="dropdown-item" href="nous_contacter.php">Nous contacter</a></li>
      <li><hr class="dropdown-divider"></li>
      <li><a class="dropdown-item" href="logout.php">Sign out</a></li>
    </ul>
  </div>
</li>
  </ul>
</header>

<div class="container-fluid">
  <div class="row">
    <!-- decalage de la side bar vers la droite en changeant col-lg-3-->
    <nav id="sidebarMenu" class="col-md-3 col-lg-3 d-md-block bg-light sidebar collapse">
      <div class="position-sticky pt-3">
        <ul class="nav flex-column">
          <!-- Div pour le style du dropdown du tableau de bord-->
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
            <button type="button" class="btn nav-link"><img src="bootstrap-icons-1.4.0/folder-plus.svg"><a href="carnet_addresse.php"> Mon activité </a></button>
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


        <!-- L'ajout des privilèges par rapport au poste : junior, senior, associé, senior manager -->
        <?php if($_SESSION['admin_privilege'] == 'Senior Manager' || $_SESSION['admin_privilege'] == 'Associé'){ ?>
        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>Edits :</span>
            <a class="link-secondary" href="" aria-label="ajouter un controle">
            <img src="bootstrap-icons-1.4.0/tools.svg">
            </a>
          </h6>
          <ul class="nav flex-column mb-2">
          <li class="nav-item">
            <button type="button" class="btn  nav-link" data-bs-toggle="modal" data-bs-target="#ajoutPersonne"><img src="bootstrap-icons-1.4.0/folder-plus.svg"> <small><i>Ajouter une personne</i></small></button>
          </li>
          <li class="nav-item">
            <button type="button" class="btn  nav-link" data-bs-toggle="modal" data-bs-target="#SupPersonne"><img src="bootstrap-icons-1.4.0/folder-plus.svg"> <small><i>Supprimer une personne</i></small></button>
          </li>
          <li class="nav-item">
            <button type="button" class="btn  nav-link" data-bs-toggle="modal" data-bs-target="#ajoutMission"><img src="bootstrap-icons-1.4.0/folder-plus.svg"> <small><i>Ajouter une mission</i></small></button>
          </li>
          <?php } ?>
          </ul>
      </div>
    </nav>

        
        <!-- affichage de la partie mes missions-->


    <main class="col-md-9 ms-sm-auto col-lg-9 px-md-5">
    <br>
    <div class="row g-3">
      <div class="col-md-5 col-lg-3">
        <h6 class="d-flex justify-content-between align-items-center mb-4">
          
          <span class="text-muted">Mes missions </span>
        <ul class="list-group mb-3">

        </h4>
        <?php foreach($missions as $mission) {
          ?>

          <li class="list-group-item d-flex justify-content-between lh-sm ">
            <div>
              <h6 class="my-0"><a style="text-decoration : none;" href="mission.php?mission=<?php echo $mission['mission_id']; ?>"><?php echo $mission['mission_nom']?></a></h6>
              <small class="text-muted"></small>
            </div>
          </li>
        <?php } ?>

        </ul>
      <br>
      </div>

      <!-- affichage de la partie Personnes présentes dans cette mission : -->


      <div class="col-md-6">

            <?php if(isset($_GET['mission']) && !empty($_GET['mission']) ){?>

          <h6 class="d-flex justify-content-between align-items-center mb-4">
            
              <span class="text-muted">Personnes présentes dans cette mission : </span>
            </h4>
          <ul class="list-unstyled" >
          <?php foreach($info_equipes as $equipe){ ?>
            <h5>
            <!-- changement de style box -->
            <li class='role' style="border: 1px solid; border-radius: 5px; text-align: center; box-shadow:0 4px 10px 0 rgba(0,0,0,0.2),0 4px 20px 0 rgba(0,0,0,0.19);"><?php echo getNameByEmail($pdo,$equipe['email_utilisateur']);?>

            <!-- role en gras "badge bg-success"-->
            
            <div class="btn-group">
              <select type="button" class="btn btn-info dropdown-toggle" style="font-size: 10px;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $equipe['role'] ;?>> 
                <option value='Junior'>Junior</option>
                <option value='Senior'>Senior</option>
                <option value='Associé'>Associé</option>      
              </select> 
            </li>
            </h5>
            <?php } ?>
          </ul>
          <?php } ?>
      </div>
    </main>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="ajoutPersonne" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="mission.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ajout d'une personne</h5>
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
                    <label for="utilisateur" class="form-label"><i>Qu'elle est la personne ?</i></label>
                        <select name="utilisateur" class="form-select">
                        <option>Selectionner une personne :</option>
                        <?php foreach($utilisateurs as $utilisateur){ ?>
                        <option value="<?php echo $utilisateur['email']; ?>"><?php echo $utilisateur['nom']." ".$utilisateur['prenom']; ?></option>
                        <?php } ?>
                        </select>
                        <div class="invalid-feedback">
                        Ce champ est obliagtoire.
                        </div>
                    </div>
                    <div class="col-sm-9">
                    <label for="role" class="form-label">role</label>
                    <select class="form-select" id="role" name="role">
                        <option value="choix ?">Choissisez un role</option>
                        <option value="Junior">Junior</option>
                        <option value="Senior">Senior</option>
                        <option value="Associe">Associe</option>
                        <option value="Senior manager">Senior manager</option>
                    </select>
                    <div class="invalid-feedback">
                        Ce champ est obliagtoire.
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
<div class="modal fade" id="SupPersonne" tabindex="-1" aria-labelledby="SupPersonne" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="mission.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Supprimer d'une personne</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col-sm-9">
                        <label for="mission" class="form-label"><i>Dans qu'elle mission ?</i></label>
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
                    <label for="utilisateur" class="form-label"><i>Qu'elle est la personne ?</i></label>
                        <select name="utilisateur" class="form-select">
                          <option>Selectionner une personne :</option>
                          <?php foreach($utilisateurs as $utilisateur){ ?>
                            <option value="<?php echo $utilisateur['email']; ?>"><?php echo $utilisateur['nom']." ".$utilisateur['prenom']; ?></option>
                          <?php } ?>
                        </select>
                        <div class="invalid-feedback">
                          Ce champ est obliagtoire.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" name="supprimer_personne">Supprimer</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="ajoutMission" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="mission.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ajout d'une mission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col-sm-9">
                        <label for="mission" class="form-label"><i>Qu'elle est la mission ?</i></label>
                        <input type="text" class="form-control" name="mission" required>
                        <div class="invalid-feedback">
                        Ce champ est obliagtoire.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" name="ajouter_mission">Ajouter</button>
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

<!-- script javascript -->

<script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"

        integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg=="
        crossorigin="anonymous">    
</script>
<!-- fichier bootstrap.bundle.min.js-->
<script src="bootstrap.bundle.min.js"></script>
<script>


  //role
  var span_role = document.querySelectorAll('.span-role');

for(var i = 0; i < span_role.length; i++){
  const on = span_role[i];
    on.addEventListener('dblclick', () => {
      var li = on.closest('li');
      console.log(li)
      li.classList.add('active');
    })
}

var select_role = document.querySelectorAll('.role-select');

for(var i = 0; i < select_role.length; i++){
  const on = select_role[i];
    on.addEventListener('change', () => {
      var li = on.closest('li');
      var select_value = on.value;
      var id = on.getAttribute("data-id");
      var span = li.querySelector('span');

      

      $.ajax({
      //envoie du fichier edit_role2

          url: "edit_role2.php",
          method: "POST",
          data: "id=" + id + "&value=" + select_value,
          success: function() {
            span.innerHTML = select_value;
            li.classList.remove('active');
          }
      })
    })
}
</script>
// fin role
  </body>
</html>

<?php

}else{
  header('Location:login.php');
}

?>