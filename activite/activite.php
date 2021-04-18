<?php
include '../connexion/bdd_connection.php';
if (isset($_SESSION['admin_email']) && $_SESSION['admin_email'] != '') {
//Tableaux

$connect = mysqli_connect("localhost", "root", "", "bdd_projet-l3an1");
$query = "SELECT statut,COUNT(*) FROM controle GROUP BY statut";
$result = mysqli_query($connect,$query);

//Graphique ITGC

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

//Graphique Contrôles répartition

$stmt1 = $dbcon->prepare("SELECT nom, prenom, email, COUNT(*) AS nombre_taches FROM utilisateur, controle WHERE email=email_utilisateur_realise_par GROUP BY email UNION ALL (SELECT nom, prenom, email, COUNT(*) AS nombre_taches FROM utilisateur, controle WHERE email=email_utilisateur_revu_par GROUP BY email) UNION ALL SELECT nom, prenom, email, COUNT(*) AS nombre_taches FROM utilisateur, controle WHERE email=email_utilisateur_sign_off GROUP BY email ");
$stmt1->execute();

$items = array();
while ($row = $stmt1->fetch(PDO::FETCH_ASSOC)) {

   $items[$row['email']]['nom'] = $row['nom'];
    $items[$row['email']]['prenom'] = $row['prenom'];
    if (isset($items[$row['email']]['count'])) {
        $items[$row['email']]['count'] += (int)$row['nombre_taches'];
    } else {
        $items[$row['email']]['count'] = $row['nombre_taches'];
    }
}

$names = array();
$count = array();
foreach ($items as $item) {
	$names[] = $item['prenom'] . ' ' . $item['nom'];
	$count[] = $item['count'];
}
?>


<?php
try
{
$bdd = mysqli_connect('localhost', 'root', '', 'bdd_projet-l3an1');
}
catch(Exception $e)
{

die('Erreur : '.$e->getMessage());
}

$reponse = $bdd->query('SELECT email, nom, prenom FROM utilisateur');

$query2 = "SELECT nom, prenom, email, COUNT(*) AS nombre_taches FROM utilisateur, controle WHERE email=email_utilisateur_realise_par GROUP BY email UNION ALL (SELECT nom, prenom, email, COUNT(*) AS nombre_taches FROM utilisateur, controle WHERE email=email_utilisateur_revu_par GROUP BY email) UNION ALL SELECT nom, prenom, email, COUNT(*) AS nombre_taches FROM utilisateur, controle WHERE email=email_utilisateur_sign_off GROUP BY email ";
$result2 = mysqli_query($bdd,$query2);

?>
<?php
$array = array();
while($row = mysqli_fetch_array($result2)){
    $array[$row['email']]['nom'] = $row['nom'];
    $array[$row['email']]['prenom'] = $row['prenom'];
    if (isset($array[$row['email']]['count'])) {
        $array[$row['email']]['count'] += (int)$row['nombre_taches'];
    } else {
        $array[$row['email']]['count'] = $row['nombre_taches'];
    }
}
?>


<!doctype html>
    <html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Page Activité</title>
        <!-- Bootstrap core CSS -->
        <link href="../bootstrap/bootstrap.min.css" rel="stylesheet">
        <!-- Custom styles for this template -->
        <link href="activite_perso.css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
        <script src="script.js"></script>
    </head>
    <body>

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
            <div class="dropdown">
            <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle" id="dropdownUser2" data-bs-toggle="dropdown" aria-expanded="false">
            <strong><?php echo $_SESSION['admin_nom']; ?></strong>
            </a>
            <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser2">
            <li><a class="dropdown-item" href="../aide/nous_contacter.php">Nous contacter</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="../connexion/logout.php">Déconnexion</a></li>
            </ul>
        </div>
        </li>
        </ul>
</header>

                   <!--barre des tâches latérale-->
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
             <button type="button" class="btn nav-link"><img src="../bootstrap/list-task.svg"><a href="../activite/activite_perso.php"> Mon activité</a></button>
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
            <button type="button" class="btn nav-link"><img src="../bootstrap/clipboard-minus.svg"><a href="../equipe/mission.php"> Mes missions</a></button>
            <button type="button" class="btn nav-link"><img src="../bootstrap/book-half.svg"><a href="../equipe/carnet_addresse.php"> Carnet d'addesses</a></button>
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
            <button type="button" class="btn  nav-link"><img src="../bootstrap/folder-plus.svg"><a href="../aide/FAQ.php"> FAQ</a></button>
            <button type="button" class="btn  nav-link"><img src="../bootstrap/folder-plus.svg"><a href="../aide/nous_contacter.php"> Nous contacter</a></button>
          </div>
        </div>
        </ul>

      </div>
    </nav>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
          <div class="btn-group me-2">
            <button id="ITGC" type="button" class="btn btn-sm btn-outline-secondary">ITGC</button>
            <button id="CR" type="button" class="btn btn-sm btn-outline-secondary">Controle répartition</button>
          </div>


        </div>
      </div>


                          <!-- Fonctions qui permet de cacher et afficher les gaphiques -->

        <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

            <script>
                $(document).ready(function(){
                  $('#ITGC').click(function(){
                    $('#myChart').toggle('fast');
                    });
                  });

           </script>

        <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

        <script>
            $(document).ready(function(){
              $('#CR').click(function(){
                $('#myChart2').toggle('fast');
                });
              });

        </script>
    <div class="graphiques">

    
                           <!-- Graphique affichant les Statuts et leurs valeurs -->

       <div id="GraphITGC" class="col-md-6 offset-md-3 my-5">
         <div class="card">
            <div class="card-body pb-0 text-center"><h2 class="Chart-title">ITGC</h2><img src="../bootstrap/aspect-ratio-fill.svg"><hr></div>
            <canvas style="display:none" class="ChartStatut" id="myChart"></canvas>
        </div>
       </div>

       <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
       <script type="text/javascript">
           var ctx = document.getElementById('myChart').getContext('2d');
           var chart = new Chart(ctx, {

           // Le type du graphique 
           type: 'doughnut',

           // Les données du graphique
           data: {
              labels: <?php echo json_encode($json); ?>,
              datasets: [{
                 label: 'Statut',
                 backgroundColor: ['rgb(178, 34, 34)','rgb(210, 105, 30)','rgb(50, 138, 236)' ,'rgb(34, 139, 34)'],
                 borderWidth: 1,
                 data: <?php echo json_encode($json1); ?>,
              }],


            },

            // Options 
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
       </script>


                         <!-- Graphique affichant la répartition des contrôles par utilisateurs -->

        <div id="GraphCR" class="col-md-6 offset-md-3 my-5">
         <div class="card">
            <div class="card-body pb-0 text-center"><h2 class="Chart-title">CR</h2><img src="../bootstrap/aspect-ratio-fill.svg"><hr></div>
            <canvas  style="display:none" class="ChartStatut" id="myChart2" data-names="<?php echo htmlentities(json_encode($names, true)) ?>" data-count="<?php echo htmlentities(json_encode($count, true)) ?>"></canvas>
        </div>
       </div>

       <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
       <script type="text/javascript">

       // Les données du graphique
            var color = Chart.helpers.color;
            var chart_element = jQuery('#myChart2');
            var names = chart_element.data('names');
            var count = chart_element.data('count');
            var barChartData = {
              labels: names,
              datasets: [{
                label: 'Name',
                backgroundColor: ["rgba(255, 99, 132, 0.2)","rgba(255, 159, 64, 0.2)","rgba(255, 205, 86, 0.2)","rgba(75, 192, 192, 0.2)","rgba(54, 162, 235, 0.2)","rgba(153, 102, 255, 0.2)","rgba(201, 203, 207, 0.2)","rgba(24, 231, 66, 0.2)"],
                borderColor:["rgb(255, 99, 132)","rgb(255, 159, 64)","rgb(255, 205, 86)","rgb(75, 192, 192)","rgb(54, 162, 235)","rgb(153, 102, 255)","rgb(201, 203, 207)","rgba(24, 231, 66)"],
                borderWidth: 1,
                data: count
              }]

            };

            window.onload = function() {
              var ctx = document.getElementById('myChart2').getContext('2d');
              window.myBar = new Chart(ctx, {

                // Le type du graphique 
                type: 'bar',
                data: barChartData,

                // Options 
                options: {
                  responsive: true,
                  plugins: {
                    legend: {
                      position: 'top',
                    },
                    title: {
                      display: true,
                      text: 'Chart.js Bar Chart'
                    }
                  },
                  scales: {
                    yAxes: [{
                      ticks: {
                        beginAtZero: true
                      }
                    }]
                  }
                }
              });

            };
       </script>

    </div>

                           <!-- Tableau Statut -->

      <h2>ITGC</h2>
      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th>Statuts</th>
              <th>Valeurs</th>

            </tr>
          </thead>
          <?php
            while ($row = mysqli_fetch_array($result))
            {
          ?>
          <tbody>
            <tr>
              <td><?php echo $row["statut"]; ?></td>
              <td><?php echo $row["COUNT(*)"]; ?></td>

            </tr>
          </tbody>
          <?php
            }
          ?>
        </table>
      </div>


                         <!-- Tableau Taches par utilisateur -->

      <h2>Contrôles répartition</h2>
      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>

              <th>Responsable</th>
              <th>Nombre de taches</th>

            </tr>
          </thead>
          <?php
             foreach($array as $key => $item) {
          ?>
          <tbody>
            <tr>
              <td><?php echo $item["nom"]." ".$item["prenom"]; ?></td>
              <td><?php echo  $item['count'];?></td>

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

        <footer class="my-5 pt-5 text-muted text-center text-small">
          <p>&copy;Copyright 2021 - Mazars - France Inc. &middot;</p>
          <p class="float"><a href="#">Retourner en haut</a></p>
        </footer>
      <script src="../bootstrap/bootstrap.bundle.min.js"></script>

      <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
      <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous">
      </script><script src="GraphStatut.php"></script>
      <script src="../notif/notif.js"></script>
  </body>
</html>
<?php

} else {
    header('Location:../connexion/login.php');
} ?>