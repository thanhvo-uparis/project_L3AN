<?php 
    //Chemin vers le fichier qui permet la connexion à la base de donnée en local 
    include 'application/bdd_connection.php';
    if(isset($_SESSION['admin_email']) && $_SESSION['admin_email'] !=''){

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

?>
<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ajout d'un contrôle</title>

    
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

    
    <!-- Custom styles for this template -->
    <link href="controle.css" rel="stylesheet">
  </head>
  <body>
        <!-- chemin vers le fichier css -->
<header class="navbar navbar-info sticky-top bg-info flex-md-nowrap p-0 shadow">
   <!-- logo mazars-->
<a class="navbar-info col-md-3 col-lg-2 me-0 px-3" href="#"><img style="height : 2àpx; width:150px;" src="logoMazars.png"></a>
  <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
    <!-- barre de recherche en haut de la page-->
  <input class="form-control form-control-dark w-100 filter-input" type="text" placeholder="Recherche" name="recherche"  aria-label="Search">
  <li class="nav-item dropdown">
    <a class="nav-link" href="#" id="dropdown01" data-bs-toggle="dropdown" aria-expanded="false">
      <span id="notifs-count"></span>
      <img src="bootstrap-icons-1.4.0/bell.svg">   </a>
    <ul id="notifs-wrapper" class="dropdown-menu" aria-labelledby="Notfications">
<?php
      //chemin vers le fichier concernant les notifications

      include './notif/action.php';
      foreach ($notifsStatut as $notifStatut) {
         $classNotifs = '';
        if($notifStatut['lu_statut']){
          $classNotifs = 'notif-read';
        } 
?>
        <li class="<?php echo $classNotifs; ?>"><a class="dropdown-item-left" href="#"><small><i><i><br>Le statut à changé en <?php echo $notifStatut['statut']; ?> pour : <?php echo $notifStatut['nom_du_controle']; ?></small></a></li>
<?php
      }
      foreach ($notifs as $notif) {

        $classNotifs = '';
        if($notif['lu']){
          $classNotifs = 'notif-read';
        } 
?>
        <li class="<?php echo $classNotifs; ?>"><a class="dropdown-item-left" href="#"><small ><i><?php echo $notif['deadline']; ?>, <i><br>Attention la deadline pour : <?php echo $notif['nom_du_controle']; ?></small><strong><small> arrive bientôt a échéance.</small></strong></a></li>
<?php
      }
?>

  
    </ul>
  </li>
      <!-- option de déconnexion-->
  <li>
    <div class="dropdown">
    <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle" id="dropdownUser2" data-bs-toggle="dropdown" aria-expanded="false">
      <strong><?php echo $_SESSION['admin_nom']; ?></strong>
    </a>
    <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser2">
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
      <!-- decalage de la side bar vers la droite en changeant col-lg-3-->
    <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
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
            <button type="button" class="btn nav-link"><img src="bootstrap-icons-1.4.0/folder-plus.svg"><a href="carnet_addresse.php"> Mon activité</a></button>
          </div>
        </div>
                          <!-- Div pour le style du dropdown la partie equipe-->
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
            <button type="button" class="btn nav-link"><img src="bootstrap-icons-1.4.0/folder-plus.svg"><a href="carnet_addresse.php"> Carnet d'adresses</a></button>
          </div>
          </div>
                    <!-- Div pour le style du dropdown du de la partie documentations -->
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


        <!-- L'ajout des privilèges par rapport au poste : junior, senior, associé, senior manager -->
        <?php if($_SESSION['admin_privilege'] == 'Senior Manager' || $_SESSION['admin_privilege'] == 'Associé'){ ?>
        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>Outils :</span>
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
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <br>
    <div class="row g-3">
      <div class="col-md-5 col-lg-4">
        <h4 class="d-flex justify-content-between align-items-center mb-3">
          <span class="text-muted">Mes missions</span>
        </h4>
        <ul class="list-group mb-3">

        <?php foreach($missions as $mission) {
          if(concernedByMission($pdo, $mission['mission_id'], $_SESSION['admin_email'])){ ?>

          <li class="list-group-item d-flex justify-content-between lh-sm ">
            <div>
              <h6 class="my-0"><a class="search-recherche" style="text-decoration : none;" href="mission.php?mission=<?php echo $mission['mission_id']; ?>"><?php echo $mission['mission_nom']?></a></h6>
              <small class="text-muted"></small>
            </div>
          </li>
        <?php }} ?>

        </ul>
      <br>
      </div>
            <!-- affichage de la partie Personnes présentes dans cette mission : -->
      <div class="col-md-7">
            <?php if(isset($_GET['mission']) && !empty($_GET['mission']) ){?>

          <h4 class="d-flex justify-content-between align-items-center mb-3">
              <span class="text-muted">Personnes présentes dans cette mission : </span>
            </h4>
          <ul class="list-unstyled">
          <?php foreach($info_equipes as $equipe){ ?>
            <h5>
           <li class='role' style="border: 1px solid; border-radius: 5px; text-align: center; box-shadow:0 4px 10px 0 rgba(0,0,0,0.2),0 4px 20px 0 rgba(0,0,0,0.19);"><?php echo getNameByEmail($pdo,$equipe['email_utilisateur']);?>
            <?php if($_SESSION['admin_privilege'] == 'Senior Manager' || $_SESSION['admin_privilege'] == 'Associé'){ ?>
            <span class="span-role"><strong><?php echo $equipe['role'] ;?></strong></span>
              <select class='role-select' data-id='<?php echo $equipe['id']; ?>'> 
                <option value='Junior'>Junior</option>
                <option value='Senior'>Senior</option>
                <option value='Associé'>Associé</option>      
              </select>
              <?php }else{ ?>
                <span><strong><?php echo $equipe['role'] ;?></strong></span>
              <?php } ?>
            </li>
            </h5>
            <?php } ?>
          </ul>
          <?php } ?>
      </div>
    </main>
  </div>
</div>

<!-- Les pop-up -->
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
                    <div class="col-sm-10">
                        <label for="mission" class="form-label"><i>Dans qu'elle mission ?</i></label>
                        <select name="mission" id="mission" class="form-select">
                        <option>Selectionner une mission :</option>
                          <?php foreach($missions as $mission){ 
                            if(concernedByMission($pdo, $mission['mission_id'], $_SESSION['admin_email'])){ ?>
                            <option value="<?php echo $mission['mission_id']; ?>"><?php echo $mission['mission_nom']; ?></option>
                          <?php } } ?>
                        </select>
                        <div class="invalid-feedback">
                          Ce champ est obliagtoire.
                        </div>
                    </div>
                  </div>
                    <div style="margin-left : 20px;" class="col-xxl-9">
                    <label for="personne" class="form-label"><i>Qu'elle est la personne ?</i></label>
                        <select name="utilisateur" id="personne" class="form-select" required>
                          <option>Selectionner une personne :</option>
                         
                        </select>
                        <div class="invalid-feedback">
                          Ce champ est obliagtoire.
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


// Création d'une fonction permettant de  generer  une selection des personnes a supprimé en fonction de la mission selectionnée ! 
var select_mission = document.getElementById('mission');
select_mission.addEventListener('change', (e) =>{
  id_mission = e.currentTarget.value;
  $.ajax({
      url: "get_personnefrommission.php",
      dataType: 'json',
      method: "POST",
      data: "id_mission=" + s,
      success: function(data) {
        var select_personne = document.getElementById('personne');
        
        select_personne.innerHTML = '';

        for(var i = 0; i < data[0].length; i++){
          select_personne.innerHTML += '<option value="" disabled hidden selected>Choissisez une personne</option><option value="'+data[0][i].email+'">'+data[0][i].nom+" "+data[0][i].prenom+'</option>';
        }        
      }
  })
})
</script>

<!-- Filtre de recherche visuel -->
<script>
    //Script pour le filitre de recherche principale dans la page mes missions
        var filter_input = document.querySelectorAll('.filter-input');

        var rows = document.querySelectorAll("li")
        console.log(rows);
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


</script>
<script src="./notif/notif.js" type="text/javascript"></script>
  </body>
</html>

<?php

}else{
  header('Location:login.php');
}

?>