<?php
include 'application/bdd_connection.php';
if(isset($_SESSION['admin_email']) && $_SESSION['admin_email'] !=''){

$query='SELECT * FROM mission';
$resultSet = $pdo->query($query);
$missions = $resultSet->fetchAll();

    function concernedByMission($pdo,  $email){
        $query=$pdo->prepare("SELECT t1.mission_nom, t1.mission_id FROM mission as t1 
        INNER JOIN equipe as t2 ON t1.mission_id = t2.id_mission 
        WHERE t2.email_utilisateur =?");
        $query->execute([$email]);
        $results=$query->fetchAll();
        return $results;
      }

      function all_NameControls($pdo, $id_mission, $email){
        $query=$pdo->prepare("SELECT t2.nom_du_controle, t2.id FROM equipe as t1 
        INNER JOIN controle as t2 on t1.id_mission= ? 
        WHERE t1.email_utilisateur = ?");
        $query->execute([$mission_id], [$email]);
        $results=$query->fetchAll();
        return $results;
     }
     
      function concernedByCategorie($pdo, $email){
        $query=$pdo->prepare("SELECT t3.nom_categorie FROM `equipe` as t1 
        INNER JOIN categorie_general as t3 
        INNER JOIN categorie_mission as t4 on t3.id=t4.id_categorie AND t1.id_mission=t4.id_mission WHERE t1.email_utilisateur  = ? 
        GROUP BY t3.nom_categorie" );
        $query->execute([$email]);
        $results=$query->fetchAll();
        return $results;
      }

      function concernedByCollaborator($pdo, $email){
         $query=$pdo->prepare("SELECT t1.mission_nom, t1.mission_id FROM mission as t1 
         INNER JOIN equipe as t2 ON t1.mission_id = t2.id_mission 
         WHERE t2.email_utilisateur =?");
         $query->execute([$email]);
         $results=$query->fetchAll();
         return $results;
      }
 /*
      $query='SELECT t3.nom_categorie FROM `equipe` as t1 INNER JOIN categorie_general as t3 INNER JOIN categorie_mission as t4 on t3.id=t4.id_categorie AND t1.id_mission=t4.id_mission WHERE t1.email_utilisateur = "arbouche.anas@mazars.fr"'
      $resultSet1 = $pdo->query($query);
      $controles1 = $resultSet1->fetchAll();
*/
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
    <link href="ActivitéPerso.css" rel="stylesheet">
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
                       
          <ul class="nav flex-column mb-2">
          <li class="nav-item">
            <a class="nav-link" href="#">
            <img src="bootstrap-icons-1.4.0/briefcase.svg">
              <p style="color: white; font-size: 15px">Mes Missions</p>
              <?php 
              $all_missions = concernedByMission($pdo, $_SESSION['admin_email']);
              ?>
              <select name="mission" class="form-control form-control-white w-100 filter-select filter-by-mission">
                <option>Selectionner une mission </option>
                <?php foreach($all_missions as $mission){ ?>
                  //verifie si l'utilisateur fait bien parti de la mission grace a la fonction concernedByMission()
                  <option value="mission-<?php echo $mission['mission_id']; ?>"><?php echo $mission['mission_nom']; ?></option>
                <?php } ?>
              </select>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="#">
            <img src="bootstrap-icons-1.4.0/bar-chart-steps.svg">
            <p style="color: white; font-size: 15px">Mes Catégories</p>
              <?php 
              $all_categories = concernedByCategorie($pdo, $_SESSION['admin_email']);
              ?>
              <select name="mission" class="form-control form-control-white w-100 filter-select filter-by-category">
                <option>Selectionner une categorie </option>
                <?php foreach($all_categories as $category){ ?>
                  //verifie si l'utilisateur fait bien parti de la mission grace a la fonction concernedByMission()
                  <option value="category-<?php echo $category['id']; ?>"><?php echo $category['nom_categorie']; ?></option>
                <?php } ?>
              </select>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="#">
            <img src="bootstrap-icons-1.4.0/briefcase.svg">
              <p style="color: white; font-size: 15px">Collaborateurs</p>
              <?php 
              $all_collaborators = concernedByCollaborator($pdo, $_SESSION['admin_email']);
              ?>
              <select name="mission" class="form-control form-control-white w-100 filter-select filter-by-mission">
                <option>Selectionner une mission </option>
                <?php foreach($all_collaborators as $collaborator){ ?>
                  //verifie si l'utilisateur fait bien parti de la mission grace a la fonction concernedByMission()
                  <option value="collaborator-<?php echo $collaborator['mission_id']; ?>"><?php echo $collaborator['mission_nom']; ?></option>
                <?php } ?>
              </select>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="#">
            <img src="bootstrap-icons-1.4.0/briefcase.svg">
              <p style="color: white; font-size: 15px">Recherche par nom du controle</p>
              <input type="text" placeholder="Nom du controle">
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
            <button id="ITGC" type="button" class="btn btn-sm btn-outline-secondary"><?php echo $_SESSION['admin_email']; ?></button>
          </div>


           

        </div>
      </div>
     
    
                                 <!-- Tableau missions -->


                                 <!-- Graphique Statut -->



       <div id="Graph" class="col-md-6 offset-md-3 my-5">

         <div  class="card" id="graphcard">  
                <div class="card-body">
<?php
function getDatas($mission_id) {
  $labels = array('SG','BNP','AW');
  return array('labels' => $labels, 'values' => array(55,12,3));
}
?>
                <?php foreach($all_missions as $mission) : ?>
                <?php
                
                $datas = getDatas($mission['mission_id']);
                $labels =$datas['labels']; 
                $values =$datas['values'];
                ?>
                <div id="mission-<?php echo $mission['mission_id'] ?>" class="chart-item" data-labels="<?php echo htmlentities(json_encode($labels)) ?>"  data-values="<?php echo htmlentities(json_encode($values)) ?>" style="display:none">
                <div class="card-body pb-0 text-center"><h2 class="Chart-title"><?php echo $mission['mission_nom'] ?></h2><img src="bootstrap-icons-1.4.0/aspect-ratio-fill.svg"><hr></div>
                <canvas class="ChartStatut" id="myChart-mission-<?php echo $mission['mission_id'] ?>"></canvas>
               
                </div>
                <?php endforeach; ?>
            </div>

          </div>
       
          <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
      
       <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
       <script type="text/javascript">
   function viewChart(chart_id, labels, values){
    var ctx = document.getElementById(chart_id).getContext('2d');
           var chart = new Chart(ctx, {

           // The type of chart we want to create
           type: 'doughnut',

            // The data for our dataset
           data: {
              labels: labels,
              datasets: [{
                 label: 'Statut',
                 backgroundColor: ['rgb(178, 34, 34)','rgb(210, 105, 30)','rgb(50, 138, 236)' ,'rgb(34, 139, 34)'],
                 borderWidth: 1,
                 data: values, 
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

            
   }

           
   jQuery(document).ready(function($){
         $('.filter-by-mission').on('change', function(){
var mission_id = $(this).val();
$('.chart-item').hide();
$('#' + mission_id).show();
var labels = $('#'+ mission_id).data('labels');
var values = $('#'+ mission_id).data('values');
viewChart('myChart-' + mission_id, labels, values);

         });
       });
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