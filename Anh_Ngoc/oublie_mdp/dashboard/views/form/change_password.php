<?php
$code_value = (isset($_GET['code'])) ? $_GET['code'] : '';
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Changement de mot de passe</title>

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
            <span data-feather="bar-chart-2"></span>
            Tableau de bord
          </a>
            <ul class="dropdown-menu" aria-labelledby="Activité">
              <li><a class="dropdown-item" href="#">Home</a></li>
              <li><a class="dropdown-item" href="#">Se Connecter</a></li>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-bs-toggle="dropdown" aria-expanded="false">
            <span data-feather="users"></span>
            Votre profil 
          </a>
            <ul class="dropdown-menu" aria-labelledby="Équipe">
              <li><a class="dropdown-item" href="#">Information personnelle</a></li>
              <li><a class="dropdown-item" href="#">Carnet d'addesses</a></li>
            </ul>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="#">
              <span data-feather="file-text"></span>
              Mot de passe
            </a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-bs-toggle="dropdown" aria-expanded="false">
            <span data-feather="users"></span>
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
        <h1 class="h2">Récupération de mot de passe</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
          <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
            <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
          </div>
          <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
            <span data-feather="calendar"></span>
            This week
          </button>
        </div>
      </div>

      <p>Changement de la mot de passe de <?=$_SESSION['recup_mail'] ?></p>
<form method="post">
    <div class="form-group">
        <label for="exampleInputEmail1">Nouveau la mot de passe</label>
        <input type="password" class="form-control" name="change_mdp">
    </div>
    <div class="form-group">
        <label for="exampleInputEmail1">Confirmation de la mot de passe</label>
        <input type="password" class="form-control" name="change_mdpc">
    </div>
    <input type="hidden" name="code_value" value="<?php echo htmlspecialchars($code_value) ?>">
    <button type="submit" class="btn btn-primary" name="change_submit">Valider</button>
</form>

      <canvas class="my-4 w-100" id="myChart" width="300" height="80"></canvas>

    </main>
  </div>
</div>

    <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>

      <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
      <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous">
      </script><script src="dashboard.js"></script>
  </body>
</html>
