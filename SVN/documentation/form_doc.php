<?php 
  //realise la connection avec la base de donnee et verifie si l'utilisateur est bien connecte
  include 'application/bdd_connection.php';
  if(isset($_SESSION['admin_email']) && $_SESSION['admin_email'] !=''){

  //Execution de requetes SQL grace a PHP.
  $query='SELECT * FROM categorie_general'; // requete SQL que l'on veut executer
  $resultSet = $pdo->query($query); // execution
  $categories = $resultSet->fetchAll();

  $query='SELECT * FROM mission';// requete SQL que l'on veut execute
  $resultSet = $pdo->query($query); // execution
  $missions = $resultSet->fetchAll();

  $query='SELECT * FROM utilisateur where role_mission="Junior"';// requete SQL que l'on veut execute
  $resultSet = $pdo->query($query); // execution
  $juniors = $resultSet->fetchAll();

  $query='SELECT * FROM utilisateur where role_mission="Senior"';// requete SQL que l'on veut execute
  $resultSet = $pdo->query($query); // execution
  $seniors = $resultSet->fetchAll();

  $query='SELECT * FROM utilisateur where role_mission="Associé" or role_mission="Senior Manager"';// requete SQL que l'on veut execute
  $resultSet = $pdo->query($query); // execution
  $associes = $resultSet->fetchAll();

  //Il s'agit de l'execution de la requete qui vat permettre d'ajouter un contrôle a la base de donnée et ainsi automatquement au tabkeau des controles.
  if(isset($_POST['ajouter'])){
  $query=$pdo->prepare("insert into controle (mission_id,categorie_id, nom_du_controle, deadline,email_utilisateur_realise_par, email_utilisateur_revu_par, email_utilisateur_sign_off, statut, niveau_de_risque, design, efficacite,lu,lu_statut) values (?,?,?,?,?,?,?,?,?,?,?,?,?)"); // requete d'ajout d'un controle.
  $query->execute([ $_POST['mission'],$_POST['categorie'],$_POST['nom_du_controle'],$_POST['deadline'],$_POST['email_utilisateur_realise_par'],$_POST['email_utilisateur_revu_par'],$_POST['email_utilisateur_sign_off'],$_POST['statut'],$_POST['niveau_de_risque'],$_POST['design'],$_POST['efficacite'],"0","0"]); // execution de la requete d'ajout.
  header('Location:index.php'); // redirection vers la page des controles.
  }

  //Fonction qui vat permettre de trier le contenu de la page en fonction de l'utilisateur pour savoir si il est présent dans la mission.
  function concernedByMission($pdo, $id, $email){
    $query=$pdo->prepare("SELECT * FROM equipe WHERE id_mission = ? AND email_utilisateur =?");//requete SQL qui permet de vérifier dans qu'elle mission est présent l'utilisateur.
    $query->execute([$id,$email]); // execution de la requete
    $results=$query->fetch(); // affichage
    if($results){ // vérification de l'affichage.
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
    </nav>


    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <main>
            <br>
            <div class="col-md-7 col-lg-8">
              <h4 class="mb-3">Saisissez les champs du controle :</h4>
                <div class="row g-3">
                    <form action="form_doc.php" method="POST">
                    <!--
                    Détermine dans qu'elle mission le controle doit etre realise.
                   -->
                  <div class="col-sm-6">
                    <label for="mission" class="form-label"><i>Qu'elle est la mission ?</i></label>
                    <select name="mission" id="mission" class="form-select" required>
                    <option value="">Selectionner une mission :</option>
                    <?php foreach($missions as $mission){ 
                      // va permmetre d'afficher a l'aide de la fonction concernedBYMission() que les missions dans lequel l'utilisateur est présent.
                      if(concernedByMission($pdo, $mission['mission_id'], $_SESSION['admin_email'])){?>
                        <option value="<?php echo $mission['mission_id']; ?>"><?php echo $mission['mission_nom']; ?></option>
                    <?php } } ?>
                    </select>
                    <div class="invalid-feedback">
                      Ce champ est obliagtoire.
                    </div>
                  </div>

                  <!--
                    Détermine dans qu'elle categorie ajouter le controle.
                   -->
                  <div class="col-sm-6">
                    <label for="categorie" class="form-label">Pour qu'elle catégorie ?</label>
                    <select name="categorie" id="categorie" class="form-select" required> 
                    <option value=" ">Selectionner une catégorie :</option>
                      
                    </select>
                    <div class="invalid-feedback">
                      Ce champ est obliagtoire.
                    </div>
                  </div>

                  <!-- 
                    Permet de determiner qu'elle est le nom du controle.
                  -->
                  <div class="col-12">
                    <label for="username" class="form-label">Nom du contrôle</label>
                    <div class="input-group has-validation">
                      <span class="input-group-text"><span><img src="bootstrap-icons-1.4.0/file-text.svg"></span></span>
                      <input type="text" name="nom_du_controle" class="form-control" id="username" placeholder="Nom du controle" required>
                    <div class="invalid-feedback">
                      Ce champ est obliagtoire.
                    </div>
                    </div>
                  </div>
                        
                  <!--
                      Vat permettre de déterminer qu'elle est la deadline maximum du controle.
                   -->
                  <div class="col-12">
                    <label for="deadline" class="form-label">Deadline du contrôle</label>
                    <input type="date" class="form-control" name="deadline" id="deadline" placeholder="jj/mm/aaaa" required>
                    <div class="invalid-feedback">
                      Ce champ est obliagtoire.
                    </div>
                  </div>
                        
                  <!-- 
                     selection des différents champs possible pour l'utilisateur qui est représentant du role = Junior
                   propre a la mission, ce filtre va etre realise avec l'Ajax plus bas
                    -->
                  <div class="col-sm-4">
                    <label for="email_utilisateur_realise_par" class="form-label">Realise par</label>
                    <select name="email_utilisateur_realise_par" class="form-select" id="realise_par" required>
                      <option value=" ">Choissisez un contact...</option>
                    </select>
                    <div class="invalid-feedback">
                    Ce champ est obliagtoire.
                    </div>
                    </div>
                        
                   <!-- 
                     selection des différents champs possible pour l'utilisateur qui est représentant du role = Senior
                   propre a la mission, ce filtre va etre realise avec l'Ajax plus bas
                    -->
                  <div class="col-sm-4">
                    <label for="email_utilisateur_revu_par" class="form-label">Revu par</label>
                    <select name="email_utilisateur_revu_par" class="form-select" id="revu_par" required>
                        <option value=" ">Choissisez un contact...</option>
                    </select>
                    <div class="invalid-feedback">
                        Ce champ est obliagtoire.
                    </div>
                </div>
                        
                   <!-- 
                     selection des différents champs possible pour l'utilisateur qui est représentant du role = Senior Manager ou Associé
                   propre a la mission, ce filtre va etre realise avec l'Ajax plus bas
                    -->
                  <div class="col-sm-4">
                    <label for="email_utilisateur_sign_off" class="form-label">Sign-off par</label>
                    <select name="email_utilisateur_sign_off" class="form-select" id="sign_off" required>
                        <option value=" ">Choissisez un contact...</option>
                        
                    </select>
                    <div class="invalid-feedback">
                        Ce champ est obliagtoire.
                    </div>
                  </div>
                        
                   <!-- selection des différents champs possible pour le statut de la mission, afin d'avoir un etat d'avancement initial -->
                  <div class="col-md-6">
                    <label for="statut" class="form-label">Statut</label>
                    <select class="form-select" id="statut" name="statut" required>
                        <option value=" "></option>
                        <option value="Non debute">Non debute</option>
                        <option value="Documente">Documente</option>
                        <option value="Revu">Revu</option>
                        <option value="Sign-off">Sign-off</option>
                    </select>
                    <div class="invalid-feedback">
                        Ce champ est obliagtoire.
                    </div>
                  </div>
      
                   <!-- selection des différents champs possible pour le niveau de risque -->
                  <div class="col-md-6">
                    <label for="niveau_de_risque" class="form-label">Niveau de risque</label>
                        <select name="niveau_de_risque" class="form-select" required>
                            <option value=" "></option>
                            <option value="Eleve">Eleve</option>
                            <option value="Moyen">Moyen</option>
                            <option value="Faible">Faible</option>
                        </select>
                    <div class="invalid-feedback">
                        Ce champ est obliagtoire.
                    </div>
                  </div>
                
                 <!-- selection des différents champs possible pour le design -->
                <div class="col-md-6">
                    <label for="design" class="form-label">Design :</label>
                    <select class="form-select" id="design"  name="design" required>
                        <option value=" "></option>
                        <option value="Non-effectif">Non-effectif</option>
                        <option value="Remarque mineure">Remarque mineure</option>
                        <option value="Remarque majeure">Remarque majeure</option>
                        <option value="Effectif">Effectif</option>
                    </select>
                    <div class="invalid-feedback">
                        Ce champ est obliagtoire.
                    </div>
                  </div>
                        
                  <!-- selection des différents champs possible pour l'efficacite -->
                  <div class="col-md-6">
                    <label for="efficacite" class="form-label">Efficacité</label>
                        <select name="efficacite" class="form-select" required>
                            <option value=" "></option>
                        <option value="Non-effectif">Non-effectif</option>
                        <option value="Remarque mineure">Remarque mineure</option>
                        <option value="Remarque majeure">Remarque majeure</option>
                        <option value="Effectif">Effectif</option>
                        </select>
                    <div class="invalid-feedback">
                        Ce champ est obliagtoire.
                    </div>
                    </div>
                </div>
                  <br><br>
                <input class="w-100 btn btn-primary btn-lg" type="submit" name="ajouter"/>
              </form>
          </div>
        </main>
      
        <footer class="my-5 pt-5 text-muted text-center text-small">
<p>&copy;Copyright 2021 - Mazars - France Inc. &middot;</p>
<p class="float"><a href="#">Retourner en haut</a></p>
</footer>
      </div>
    </main>
    <script src="bootstrap.bundle.min.js"></script>
    <script src="form-validation.js"></script>
      <!--<script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script><script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script><script src="dashboard.js"></script>-->
  
  <script>
  // generer select categorie et les personnes en fonction de la mission selectionnée ! 
var select_mission = document.getElementById('mission'); 
select_mission.addEventListener('change', (e) =>{ // realise la modification
  id_mission = e.currentTarget.value;
  $.ajax({
      url: "get_categoriefrommissionAll.php",
      dataType: 'json',
      method: "POST",
      data: "id_mission=" + id_mission,
      success: function(data) {
        var select_categorie = document.getElementById('categorie');// permet de recuperer l'ID en rapport au categorie
        var select_realise_par = document.getElementById('realise_par');// permet de recuperer l'ID des personnes junior
        var select_revu_par = document.getElementById('revu_par');// permet de recupere l'ID des personnes seniors en rapport a la mission
        var select_signoff = document.getElementById('sign_off');// permet de recuperer l'ID des personnes Senior manager ou Associé propre a la mission

        select_categorie.innerHTML = ''; // vat vider ce qui s'est passe avant 
        select_realise_par.innerHTML = '';// vat vider ce qui s'est passe avant 
        select_revu_par.innerHTML = '';// vat vider ce qui s'est passe avant 
        select_signoff.innerHTML = '';// vat vider ce qui s'est passe avant 

        //vat realise les différentes choix possible en fonction de l'id de la mission
        for(var i = 0; i < data[0].length; i++){
          select_categorie.innerHTML += '<option value="" disabled hidden selected>Choissisez une categorie</option><option value="'+data[0][i].id_categorie+'">'+data[0][i].nom_categorie+'</option>';
        }
        for(var j = 0; j < data[1].length; j++){
          select_realise_par.innerHTML += '<option value="" disabled hidden selected>Choissisez un contact</option><option value="'+data[1][j].email_utilisateur+'">'+data[1][j].nom+' '+data[1][j].prenom+'</option>';
        }
        for(var j = 0; j < data[2].length; j++){
          select_revu_par.innerHTML += '<option value="" disabled hidden selected>Choissisez un contact</option><option value="'+data[2][j].email_utilisateur+'">'+data[2][j].nom+' '+data[2][j].prenom+'</option>';
        }
        for(var j = 0; j < data[3].length; j++){
          select_signoff.innerHTML += '<option value="" disabled hidden selected>Choissisez un contact</option><option value="'+data[3][j].email_utilisateur+'">'+data[3][j].nom+' '+data[3][j].prenom+'</option>';
        }
        
      }
  })
})
// FIN generer select categorie en fonction de la mission selectionnée ! 
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"
        integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg=="
        crossorigin="anonymous"></script>
    <script src="bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
  </body>
</html>

<?php 
  }else{
    header('Location:login.php');// si déconnecte redirige vers la page de connexion.
  }
?>