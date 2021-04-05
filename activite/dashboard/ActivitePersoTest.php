<?php
include 'application/bdd_connection.php';
if(isset($_SESSION['admin_email']) && $_SESSION['admin_email'] !=''){


//affichage des categorie generale
$query='SELECT * FROM categorie_general'; //requete SQL
$resultSet = $pdo->query($query);
$categories = $resultSet->fetchAll();

//affichage des categorie en fonction de leurs asscoiation a une mission specifique
$query='SELECT * FROM categorie_mission'; //requete SQL
$resultSet = $pdo->query($query);
$categories_missions = $resultSet->fetchAll();



//requete SQL qui vat permettre d'avoir les differentes mission lors de son execution.
$query='SELECT * FROM mission'; //requete SQL
$resultSet = $pdo->query($query);
$missions = $resultSet->fetchAll();

  //fonction qui vat permmettre a partir d'une requete SQL de retourner les mission dans lequel l'utilisateur a ete identifier.
  function concernedByMission($pdo, $id, $email){
    $query=$pdo->prepare("SELECT * FROM equipe WHERE id_mission = ? AND email_utilisateur =?"); //requete SQL
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
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Page ActivitéPerso</title>

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
    <link href="ActivitéPersoTest.css" rel="stylesheet">
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
            <button type="button" class="btn nav-link"><img src="bootstrap-icons-1.4.0/bar-chart-line.svg"><a href="Activité.php"> Activité</a></button>
            <button type="button" class="btn nav-link"><img src="bootstrap-icons-1.4.0/list-task.svg"><a href="ActivitéPerso.php"> Mon activité</a></button>
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
            <button type="button" class="btn nav-link"><img src="bootstrap-icons-1.4.0/clipboard-minus.svg"><a href="mission.php"> Mes missions</a></button>
            <button type="button" class="btn nav-link"><img src="bootstrap-icons-1.4.0/book-half.svg"><a href="carnet_addresse.php"> Carnet d'addesses</a></button>
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
            <button type="button" class="btn  nav-link"><img src="bootstrap-icons-1.4.0/folder-plus.svg"><a href="index.php"> FAQ</a></button>
            <button type="button" class="btn  nav-link"><img src="bootstrap-icons-1.4.0/folder-plus.svg"><a href="nous_contacter.html"> Nous contacter</a></button>
          </div>
        </div>
        </ul>
        <br>


        <ul class="nav flex-column mb-2">
          <li class="nav-item">
            <a class="nav-link" href="#">
            <img src="bootstrap-icons-1.4.0/briefcase.svg">
              Mes Missions : 
              <select name="mission" class="form-control form-control-white w-100 filter-select">
                <option>Selectionner une mission </option>
                <?php foreach($missions as $mission){ 
                  //verifie si l'utilisateur fait bien parti de la mission grace a la fonction concernedByMission()
                  if(concernedByMission($pdo, $mission['mission_id'], $_SESSION['admin_email'])){?>
                  <option value="<?php echo $mission['mission_nom']; ?>"><?php echo $mission['mission_nom']; ?></option>
                <?php } } ?>
              </select>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
            <img src="bootstrap-icons-1.4.0/bar-chart-steps.svg">
              Mes Catégories :
              <select name="categorie" class="form-control form-control-white w-100 filter-select">
                <option>Selectionner une categorie </option>
                <?php foreach($categories as $categorie){ ?>
                  <option value="<?php echo $categorie['nom_categorie']; ?>"><?php echo $categorie['nom_categorie']; ?></option>
                <?php } ?>
              </select>
            </a>
          </li>
        </ul>



      </div>
    </nav>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
          <div class="btn-group me-2">
            <button id="ITGC" type="button" class="btn btn-sm btn-outline-secondary">NACHET Haroune</button>
          </div>
          

        </div>
      </div>
     
                                 <!-- Tableau missions -->


                                 <!-- Graphique Statut -->



       <div id="Graph" class="col-md-6 offset-md-3 my-5">

         <div  class="card" id="graphcard">
            <div class="card-body pb-0 text-center"><h2 class="Chart-title">ITGC </h2><img src="bootstrap-icons-1.4.0/aspect-ratio-fill.svg"><hr></div>  
                <div class="card-body">
                    <canvas class="ChartStatut" id="myChart"></canvas>
                </div>
            <div  class="card" id="insidecard">
                  <div class="uptdateboutton">
                    <button class="btn btn-success" onclick="updateChart()">1</button>
                    <button class="btn btn-success" onclick="updateChart1()">2</button> 
                  </div> 
            </div>
          </div>
       
       
       <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
       <script type="text/javascript">

       //valeurs 1
          var newdatasets = [55,12,3];
          var newdatalabels = ['SG','BNP','AW'];  
       //valeurs 2  
          var newdatasets1 = [65,3];
          var newdatalabels1 = ['SG','AW']; 

           var ctx = document.getElementById('myChart').getContext('2d');
           var chart = new Chart(ctx, {

           // The type of chart we want to create
           type: 'doughnut',

            // The data for our dataset
           data: {
              labels: ['LCL','SG','BNP','AW'] ,
              datasets: [{
                 label: 'Statut',
                 backgroundColor: ['rgb(178, 34, 34)','rgb(210, 105, 30)','rgb(50, 138, 236)' ,'rgb(34, 139, 34)'],
                 borderWidth: 1,
                 data: [5,9,14,23], 
              }],


            },

            // Configuration options go here
            options: {
              scales: {
                  ticks: {
                    beginAtZero: true
                  }

              },
              legend: {
                display: true,

                labels: {
                  boxWidth: 10
            },
              },
              animation:{
                animateScale: false,
              }
            }
            });

            function updateChart() {
              chart.data.datasets[0].data = newdatasets;
              chart.data.labels[0].data = newdatalabels;
              chart.update();
            };

            function updateChart1() {
              chart.data.datasets[0].data = newdatasets1;
              chart.data.labels[0].data = newdatalabels1;
              chart.update();
            };

       </script>

             
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