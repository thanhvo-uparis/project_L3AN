<?php
session_start();
//Tableaux
$connect = mysqli_connect("localhost", "root", "", "bdd_projet-l3an1");
$_SESSION['admin_email']="chouat.david@mazars.fr";
if (isset($_SESSION['admin_email']) && $_SESSION['admin_email'] != '')
{
    $all_missions = mysqli_query($connect, 'SELECT * FROM mission WHERE email_proprietaire="' . $_SESSION['admin_email'] . '"');
?>
	<h2>Your mission</h2>
  <div class="table-responsive">
    <table class="table table-striped table-sm">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>

        </tr>
      </thead>
      <?php
    while ($row = mysqli_fetch_array($all_missions))
    {
?>
      <tbody>
        <tr>
          <td><?php echo $row["mission_id"]; ?></td>
          <td><?php echo $row["mission_nom"]; ?></td>
               
        </tr> 
      </tbody>
      <?php
    }
?> 
    </table>
  </div>
	<?php
}
else
{
    header('Location:activite.php');
}

?>
