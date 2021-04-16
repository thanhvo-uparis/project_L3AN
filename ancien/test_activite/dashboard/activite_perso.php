<?php
include 'application/bdd_connection.php';

if(isset($_SESSION['admin_email']) && $_SESSION['admin_email'] !=''){

  

  $query='SELECT * FROM equipe INNER JOIN mission ON equipe.id_mission = mission.mission_id INNER JOIN utilisateur ON equipe.email_utilisateur = utilisateur.email ORDER BY mission_nom';
  
  
  $resultSet = $pdo->query($query);
  $equipes = $resultSet->fetchAll();

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

  $query='SELECT * FROM mission';
   $resultSet = $pdo->query($query);
   $missions = $resultSet->fetchAll();

?>


<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Page Activité</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/dashboard/">

    

    <!-- Bootstrap core CSS -->
<link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">

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
    <link href="Activité.css" rel="stylesheet">
  </head>
  <body>
    
<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
  <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">Mazars</a>
  <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search">
  <ul class="navbar-nav px-3">
    <li class="nav-item text-nowrap">
      <a class="nav-link" href="#">Déconnexion</a>
    </li>
  </ul>
</header>


<div class="container-fluid">
  <div class="row">
    <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
      <div class="position-sticky pt-3">
      <ul class="nav flex-column">
      <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="bootstrap-icons-1.4.0/graph-up.svg">
            Tableau de bord
          </a>
            <ul class="dropdown-menu" aria-labelledby="Activité">
              <li><a class="dropdown-item active" href="#">Activité</a></li>
              <li><a class="dropdown-item" href="#">Mon activité</a></li>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="bootstrap-icons-1.4.0/person-lines-fill.svg">
            Équipe
          </a>
            <ul class="dropdown-menu" aria-labelledby="Équipe">
              <li><a class="dropdown-item" href="#">Mes missions</a></li>
              <li><a class="dropdown-item" href="../Equipe/carnet_addresse_copie.php">Carnet d'addesses</a></li>
            </ul>
          </li>
          <li class="nav-item">
            <a class="nav-link " href="../Documentation/index.php">
            <img src="bootstrap-icons-1.4.0/folder-check.svg">
              Documentation
            </a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="bootstrap-icons-1.4.0/info.svg">
            Aide
          </a>
            <ul class="dropdown-menu" aria-labelledby="Nous contacter">
              <li><a class="dropdown-item" href="#">FAQ</a></li>
              <li><a class="dropdown-item" href="#">Nous Contacter</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
          <div class="btn-group me-2">
            <button id="ITGC" type="button" class="btn btn-sm btn-outline-secondary">ITGC</button>
            <button id="C.R." type="button" class="btn btn-sm btn-outline-secondary">Controle répartition</button>
          </div>
          <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
            <span data-feather="calendar"></span>
            This week
          </button>
          

        </div>
      </div>
                       <!-- Fonctions Bouttons Graphiques -->
        <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
       
        <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead style="background-color: aqua;">
            <tr>
              <th>Vos Mission</th>
            </tr>
          </thead>
          <tbody>
          <tbody>
            <?php foreach($missions as $mission){ ?>
            <tr>
              <th colspan="11"><?php echo $mission['mission_nom']; ?></th>
            </tr>
            <?php foreach($equipes as $equipe){ ?>
             <?php if($equipe['id_mission'] == $mission['mission_id']){
               if(concernedByMission($pdo, $equipe['id_mission'], $_SESSION['admin_email'])){
               
               ?>
            <tr>
              <td><?php echo $equipe['mission_nom']; ?></td>
            </tr>
            <?php } } } } ?>
          </tbody>
        </table>
      </div>

    </main>
  </div>
</div>


      <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>

      <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
      <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous">
      </script><script src="GraphStatut.php"></script>
  </body>
</html>



<?php

}else{
  header('Location:login.php');
} ?>