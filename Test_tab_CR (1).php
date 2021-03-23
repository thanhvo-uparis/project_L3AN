
<?php

//Graphique <!-- Code avec JSON-->

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

     <!-- Code avec mysqli-->
<?php
/*
try
{
// On se connecte à MySQL
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
*/
?>

<!DOCTYPE html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>

<body>
<div id="GraphITGC" class="col-md-6 offset-md-3 my-5">
         <div class="card">
            <div class="card-body pb-0 text-center"><h2 class="Chart-title">Chart</h2><hr></div>           
            <canvas  class="ChartStatut" id="myChart" data-names="<?php echo htmlentities(json_encode($names, true)) ?>" data-count="<?php echo htmlentities(json_encode($count, true)) ?>"></canvas>
        </div>
       </div>
       
       <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
       <script type="text/javascript">
		var color = Chart.helpers.color;
		var chart_element = jQuery('#myChart');
		var names = chart_element.data('names');
		var count = chart_element.data('count');
		var barChartData = {
			labels: names,
			datasets: [{
				label: 'Dataset 1',
				backgroundColor: 'rgba(255, 99, 132, 0.5)',
				borderColor: 'rgb(255, 99, 132)',
				borderWidth: 1,
				data: count
			}]

		};

		window.onload = function() {
			var ctx = document.getElementById('myChart').getContext('2d');
			window.myBar = new Chart(ctx, {
				type: 'bar',
				data: barChartData,
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

</body>
</html>