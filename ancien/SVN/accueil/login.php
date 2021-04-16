<?php   
include 'application/bdd_connection.php';
$error = "";
   if(isset($_POST['connect'])){
      $Email = $_POST['Email'];
      $Password = $_POST['Mdp'];
      $Password = md5(md5(md5(md5($Password))));
      
          $query ="SELECT * FROM utilisateur WHERE email=? AND mot_de_passe=?";
          $resultSet=$pdo->prepare($query);
          $resultSet->execute([$Email,$Password]);
          $admin = $resultSet->fetch();
          
          if($admin['email']==null)
          {
              $error = "<p>Mot de passe ou Email non valide !</p>";
          }
          else
          {
              $_SESSION['admin_nom']=$admin['nom'];
              $_SESSION['admin_prenom']=$admin['prenom'];
              $_SESSION['admin_email']=$admin['email'];
              $_SESSION['admin_privilege']=$admin['role_mission'];
              
              header('Location:mission.php');
          }
  }
  //notifcations
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.80.0">
    <title>Accueil</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/carousel/">

    

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
<link href="accueil.css" rel="stylesheet">

</head>
<body>
<main>
<header class="navbar navbar-light sticky-top bg-light flex-md-nowrap p-0 shadow">
  <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#"><img style="heigt : 100px; width:100px;" src="mazars-logo.png"></a>
  <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <form action="login.php" method="post" accept-charset="utf-8">
      <?php echo $error; ?>
  <div class="row">
  <div class="col">
    <input type="text" id="email" name="Email" class="form-control" placeholder="Email" aria-label="Email">
  </div>
  <div class="col">
    <input type="text" id="password" name="Mdp" class="form-control" placeholder="Mot de passe" aria-label="Mot de passe">
  </div>
  <div class="col">
         <button type="submit" name="connect" class="btn btn-primary btn-block">Connexion</button>
   </div>
</div>
  </ul>
</header>

  <div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
      <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
      <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>
    <div class="carousel-inner">
      <div class="carousel-item active" style="color : black;">
      <svg class="bd-placeholder-img" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false"><rect width="100%" height="100%" fill="#777"/><img src="https://www.mazars.fr/var/mazars/storage/images/media/contenus-locaux/france/3.-illustration-pictures/corpo2/4-1000x1000/52782469-1-fre-FR/4-1000x1000_section_card.png"></svg>

        <div class="container">
          <div class="carousel-caption text-start">
            <h1><i>#NaviguateTheFuture</h1>
            <p><a class="btn btn-lg btn-dark" href="#">En savoir plus</a></p>
          </div>
        </div>
      </div>
      <div class="carousel-item">
        <svg class="bd-placeholder-img" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false"><rect width="100%" height="100%" fill="#777"/><img src="https://www.lemondeduchiffre.fr/images/mazars-entreprise.jpg"></svg>

        <div class="container">
          <div class="carousel-caption">
            <h1>Le future de l'audit</h1>
            <p><a class="btn btn-lg btn-dark" href="https://www.mazars.fr/Accueil/Insights/Publications-et-evenements/Etudes/Le-futur-de-l-audit">Voire plus</a></p>
          </div>
        </div>
      </div>
      <div class="carousel-item">
        <svg class="bd-placeholder-img" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false"><rect width="100%" height="100%" fill="#777"/><img src="https://www.solutionsnature.com/wp-content/themes/solutionsnature/img/entreprise-top-page.jpg"></svg>

        <div class="container">
          <div class="carousel-caption text-end">
            <h1>« Une année singulière » : Mazars publie son rapport annuel 2019/2020</h1>
            <p><a class="btn btn-lg btn-primary" href="https://www.mazars.fr/Accueil/Insights/Publications-et-evenements/Publications-institutionnelles/Une-annee-singuliere-rapport-annuel-2019-2020">Voir plus</a></p>
          </div>
        </div>
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#myCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#myCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>


  <!-- Marketing messaging and featurettes
  ================================================== -->
  <!-- Wrap the rest of the page in another container to center all the content. -->

  <div class="container marketing">

    <!-- Three columns of text below the carousel -->
    <div class="row">
      <div class="col-lg-4">
        <svg class="bd-placeholder-img rounded-circle" width="140" height="140" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 140x140" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Nos publications</title><rect width="100%" height="100%" fill="#777"></svg>
        <h2>Nos publications</h2>
        <p>Retrouvez l'ensemble des études, avis d'experts, newsletters et publications institutionnelles édités par Mazars en France.</p>
        <p><a class="btn btn-success" href="https://www.mazars.fr/Accueil/Insights/Publications-et-evenements">View details &raquo;</a></p>
      </div><!-- /.col-lg-4 -->
      <div class="col-lg-4">
        <svg class="bd-placeholder-img rounded-circle" width="140" height="140" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 140x140" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#777"/><text x="50%" y="50%" fill="#777" dy=".3em">140x140</text></svg>

        <h2>Nos prochains événements</h2>
        <p>Tous les salons, conférences, petit-déjeuners organisés ou en partenariat avec Mazars en France sont annulés jusqu'à nouvel ordre.</p>
        <p><a class="btn btn-success" href="https://www.mazars.fr/Accueil/Insights/Publications-et-evenements/Tous-les-evenements-de-Mazars-en-France/Les-prochains-evenements-Mazars-en-France">View details &raquo;</a></p>
      </div><!-- /.col-lg-4 -->
      <div class="col-lg-4">
        <svg class="bd-placeholder-img rounded-circle" width="140" height="140" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 140x140" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#777"/><text x="50%" y="50%" fill="#777" dy=".3em">140x140</text></svg>

        <h2>Notre équipe</h2>
        <p>Vous trouverez ci-dessous la liste complète des nos associés ainsi que les autres contacts présents sur le site web</p>
        <p><a class="btn btn-success" href="https://www.mazars.fr/Accueil/Contacts/Notre-equipe">View details &raquo;</a></p>
      </div><!-- /.col-lg-4 -->
    </div><!-- /.row -->


    <!-- START THE FEATURETTES -->

    <hr class="featurette-divider">

    <div class="row featurette">
      <div class="col-md-6">
        <h2 class="featurette-heading" style="color : black;">Explorez le futur</h2>
        <p class="lead">Alors que la révolution digitale impacte en profondeur les modèles économiques traditionnels, l’audit est appelé à évoluer non seulement dans sa pratique mais aussi dans le rôle et la place que les commissaires aux comptes occupent au sein des entreprises</p>
        <p><a class="btn btn-success" href="https://www.mazars.fr/Accueil/Insights/Future-of-audit">View details &raquo;</a></p>
      </div>
      <div class="col-md-1">
        <svg class="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto" width="50%" height="50%" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 500x500" preserveAspectRatio="xMidYMid slice" focusable="false"><img style="height : 500px; width : 700px;"src='https://www.mazars.fr/var/mazars/storage/images/media/contenus-locaux/france/3.-illustration-pictures/corpo2/4-1000x1000/52782469-1-fre-FR/4-1000x1000_section_card.png'></svg>
      </div>
    </div>

    <hr class="featurette-divider">

    <!-- /END THE FEATURETTES -->

  </div><!-- /.container -->


  <!-- FOOTER -->
  <footer class="container">
    <p class="float-end"><a href="#">Retourner en haut</a></p>
    <p>&copy;Copyright 2021 - Mazars - France Inc. &middot;</p>
  </footer>
</main>


    <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>

      
  </body>

</body>

</html>
