<?php
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

$query = "SELECT nom, prenom, email, COUNT(*) AS nombre_taches FROM utilisateur, controle WHERE email=email_utilisateur_realise_par GROUP BY email UNION ALL (SELECT nom, prenom, email, COUNT(*) AS nombre_taches FROM utilisateur, controle WHERE email=email_utilisateur_revu_par GROUP BY email) UNION ALL SELECT nom, prenom, email, COUNT(*) AS nombre_taches FROM utilisateur, controle WHERE email=email_utilisateur_sign_off GROUP BY email ";
$result = mysqli_query($bdd,$query);

?>
<?php
$array = array();
while($row = mysqli_fetch_array($result)){
    $array[$row['email']]['nom'] = $row['nom'];
    $array[$row['email']]['prenom'] = $row['prenom'];
    if (isset($array[$row['email']]['count'])) {
        $array[$row['email']]['count'] += (int)$row['nombre_taches'];
    } else {
        $array[$row['email']]['count'] = $row['nombre_taches'];
    }
}
?>

<table class="table">
    <thead>
        <tr>
        <th>nom</th>
        <th>prenom</th>
            
            <th>count</th>
        </tr>
    </thead>
    <tbody>
    <?php
foreach($array as $key => $item) {
    ?>
        <tr>
        <td><?php  echo $item['nom']; ?></td>
        <td><?php  echo $item['prenom']; ?></td>
        
            <td><?php echo  $item['count'];
?></td>
        </tr>
        <?php } ?>
        </tbody>
</table>