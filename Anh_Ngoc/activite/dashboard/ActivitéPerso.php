<?php

include 'application/bdd_connection.php';
if(isset($_SESSION['admin_email']) && $_SESSION['admin_email'] !=''){



//Tableaux

$connect = mysqli_connect("localhost", "root", "", "bdd_projet-l3an1");

$query = "SELECT * FROM mission, equipe WHERE mission_id = id_mission ";
$query1 = "SELECT * FROM utilisateur";
$query2 = "SELECT COUNT(*) FROM controle WHERE email_utilisateur_realise_par = email_utilisateur_revu_par OR email_utlisateur_sign_off";

$result = mysqli_query($connect,$query);
$result1 = mysqli_query($connect,$query1);
$result2 = mysqli_query($connect,$query2);


//Graphique

$dbhost = 'localhost';
$dbname = 'bdd_projet-l3an1';
$dbuser = 'root';
$dbpass = '';

try{

    $dbcon = new PDO("mysql:host={$dbhost};dbname={$dbname}",$dbuser,$dbpass);
    $dbcon->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

}catch(PDOException $ex){

    die($ex->getMessage());
}
$stmt = $dbcon->prepare("SELECT statut,COUNT(statut) AS `count` FROM controle GROUP BY statut");
$stmt->execute();
$count = ['COUNT(statut)'];
$json = [];
$json1 = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    extract($row);
    $json[] = $statut;
    $json1[] = $count;
}

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

            <script>
                $(document).ready(function(){
                  $('#ITGC').click(function(){
                    $('canvas').toggle('fast');
                    });
                  });

          </script>
                           <!-- Graphique Statut -->
       <div id="GraphITGC" class="col-md-6 offset-md-3 my-5">
         <div class="card">
            <div class="card-body pb-0 text-center"><h2 class="Chart-title">ITGC</h2><img src="bootstrap-icons-1.4.0/aspect-ratio-fill.svg"><hr></div>           
            <canvas style="display:none" class="ChartStatut" id="myChart"></canvas>
        </div>
       </div>
       
       <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
       <script type="text/javascript">
           var ctx = document.getElementById('myChart').getContext('2d');
           var chart = new Chart(ctx, {

           // The type of chart we want to create
           type: 'bar',

            // The data for our dataset
           data: {
              labels: <?php echo json_encode($json); ?>,
              datasets: [{
                 label: 'Statut',
                 backgroundColor: 'rgb(50, 138, 236)',
                 borderColor: '#000000',
                 borderWidth: 2,
                 data: <?php echo json_encode($json1); ?>,
              }],


            },

            // Configuration options go here
            options: {
              scales: {
                yAxes: [{
                  ticks: {
                    beginAtZero: true
                  }
                }]
              },
              legend: {
                display: true
              }
            }
            });
       </script>
      
       

      <!--
      <canvas class="my-4 w-100" id="myChart" width="700" height="80" style="display:block; height:60; width: 80px;"></canvas>
      -->
      
                           <!-- Tableau Statut -->

      <h2>Mes missions</h2>
      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th>#</th>

            </tr>
          </thead>
          <?php 
            while ($row = mysqli_fetch_array($result))
            {
          ?>
          <tbody>
            <tr>
              <td><?php echo $row["mission_nom"]; ?></td>
                   
            </tr> 
          </tbody>
          <?php 
            }
          ?> 
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