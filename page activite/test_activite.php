<body>

<?php
$connect = mysqli_connect("localhost", "root", "", "bdd_projet-l3an1");

$query = "SELECT statut, COUNT * FROM controle GROUP BY statut";
$result = mysqli_query($connect, $query);



while ($row = mysqli_fetch_array($result))
{
echo"<th>
<td>".$row["statut"]."</td>
</th>";
}
?>


</body>