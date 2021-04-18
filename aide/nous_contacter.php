<?php 
include '../connexion/bdd_connection.php';
if(isset($_SESSION['admin_email']) && $_SESSION['admin_email'] !=''){ 


  if(isset($_POST['envoyer']))
  {
     $Email = $_POST['email'];//Email saisie 
     $Sujet = $_POST['sujet'];//Sujet du mail
     $Msg = $_POST['message'];//Message du mail

     if(empty($Email) || empty($Sujet) || empty($Msg))//Verifie si les differents champs sont remplies
     {
         header('location:nous_contacter.php?error');//Affichae du message d'erreur
     }
     else
     {
        mail('benjaminbenharbon@gmail.com',$Sujet,$Msg);//Envoi du mail
        header('location:nous_contacter.php?success');//Affichage d'un message de succes
     }
  }
?>
<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nous contacter</title>

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

    
    <!-- Style de la page nous contacter -->
    <link href="../bootstrap/controle.css" rel="stylesheet">
  </head>
  <body>
    
  <!-- nav barre en haut de la page -->
<header class="navbar navbar-info sticky-top bg-info flex-md-nowrap p-0 shadow">
<a class="navbar-info col-md-3 col-lg-2 me-0 px-3" href="#"><img style="height : 2àpx; width:150px;" src="../logo/logoMazars.png"></a>
  <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <input class="form-control form-control-dark w-100 filter-input" type="text" placeholder="Recherche" name="recherche"  aria-label="Search">
  <li class="nav-item dropdown">
    <a class="nav-link" href="#" id="dropdown01" data-bs-toggle="dropdown" aria-expanded="false"><span id="notifs-count"></span><img src="../bootstrap/bell.svg">   </a>
    <ul id="notifs-wrapper" class="dropdown-menu" aria-labelledby="Notfications">
<?php
      //ajout des notifications par deadline et par changement de statuts.
      include '../notif/action.php';
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
  <li>
    <!-- Pole utilisateur -->
    <div class="dropdown">
    <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle" id="dropdownUser2" data-bs-toggle="dropdown" aria-expanded="false">
      <strong><?php echo $_SESSION['admin_nom']; ?></strong>
    </a>
    <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser2">
      <li><a class="dropdown-item" href="nous_contacter.php">Nous contacter</a></li>
      <li><hr class="dropdown-divider"></li>
      <li><a class="dropdown-item" href="../connexion/logout.php">Déconnexion</a></li>
    </ul>
  </div>
</li>
  </ul>
</header>

<!-- Barre de menu l'aterale avec les differentess fonctionnalites qui lui sont propre -->
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
            <button type="button" class="btn nav-link"><img src="../bootstrap/folder-plus.svg"><a href="../equipe/carnet_addresse.php"> Carnet d'adresses</a></button>
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
            <button type="button" class="btn nav-link"><img src="../bootstrap/folder-plus.svg"><a href="../documentation/documentation.php"> Mes contrôles</a></button>
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
            <button type="button" class="btn  nav-link"><img src="../bootstrap/folder-plus.svg"><a href="FAQ.php"> FAQ</a></button>
            <button type="button" class="btn  nav-link"><img src="../bootstrap/folder-plus.svg"><a href="nous_contacter.php"> Nous contacter</a></button>
          </div>
        </div>
        </ul>
      </div>
    </nav>
    
    <!-- contenu de la page -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <br>
        <div class="container">
            <div class="row">
                <div class="col-lg-6 m-auto">
                    <div class="card mt-5">
                        <div class="card-title">
                            <h2 class="text-center py-2"> Nous contacter </h2>
                            <hr>
                            <?php 
                              //GESTION DES ERREURS EN CAS DE NON REMPLISSAGE DES CHAMPS

                                $Msg = "";
                                if(isset($_GET['error']))
                                {
                                    $Msg = " Veuillez remplir tout les champs s'il vous plaît ";
                                    echo '<div class="alert alert-danger">'.$Msg.'</div>';
                                }
    
                                if(isset($_GET['success']))
                                {
                                    $Msg = " L'envoi du mail a été envoyé avec succés. ";
                                    echo '<div class="alert alert-success">'.$Msg.'</div>';
                                }
                            
                            ?>
                        </div>
                        <!-- Modal pour l'envoi du mail -->
                        <div class="card-body">
                            <form  method="POST">
                                <input type="email" name="email" placeholder="email" class="form-control mb-2">
                                <input type="text" name="sujet" placeholder="Ex : je ne comprends pas ..." class="form-control mb-2">
                                <textarea name="message" class="form-control mb-2" placeholder="Écrire votre requête"></textarea>
                                <button class="btn btn-success" name="envoyer"> Envoyer </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

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
          <script src="../notif/notif.js" type="text/javascript"></script>
      </body>
</html>
         
<?php
}else{
  header('Location:../connexion/login.php');//Redirection vers la page de connexion si l'utilisateur n'est pas connecte
} 
?>  