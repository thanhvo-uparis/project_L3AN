<?php
    
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

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Graphique Statuts</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<bod y>
    <h1 class="h1 pb-0 text-center" >Graphique a Bar représentant les Statuts</h1>
    
    <div class="col-md-6 offset-md-3 my-5">
        <div class="card">
            <canvas id="myChart"></canvas>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>

                    <!--Graphique première version-->

    <script type="text/javascript">
         var ctx = document.getElementById('myChart').getContext('2d');
    var chart = new Chart(ctx, {
    // Le type de Graphique 
    type: 'bar',

    // Les données du graphique
    data: {
        labels: <?php echo json_encode($json); ?>,
        datasets: [{
            label: 'ITGC',
            backgroundColor: ['rgb(50, 138, 236)','rgb(540, 18, 236)','rgb(504, 38, 26)'],
            borderColor: '#000000',
            borderWidth: 5,
            hoverBackgroundColor: 'rgb(55, 38, 26)',
            data: <?php echo json_encode($json1); ?>,
        }]
    },

    // Configuration des options 
    options: {
        
        legend: {
            display: true,
            position: 'top',
            labels: {
                boxWidth: 10
            },
            
        },
        scales: {
            xAxes: [{
                stacked: true
            }],

            yAxes: [{
                ticks: {
                beginAtZero: true
       
          }
        }]
      },
    }
    });
    </script>

</body>
</html>