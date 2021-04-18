<?php   
include '../connexion/bdd_connection.php';
$error = "";
   if(isset($_POST['connect'])){
      $Email = $_POST['Email'];
      $Password = $_POST['Mdp'];
      $Password = md5(md5(md5(md5($Password))));
      
          $query ="SELECT * FROM utilisateur WHERE email=? AND mot_de_passe=?";
          $resultSet=$pdo->prepare($query);
          $resultSet->execute([$Email,$Password]);
          $admin = $resultSet->fetch();
          $count = $resultSet->rowCount();
          
          if($count == 0)
          {
              $error = "<p>Mot de passe ou Email non valide !</p>";
          }
          else
          {
              $_SESSION['admin_nom']=$admin['nom'];
              $_SESSION['admin_prenom']=$admin['prenom'];
              $_SESSION['admin_email']=$admin['email'];
              $_SESSION['admin_privilege']=$admin['role_mission'];
              
              header('Location:../activite/activite_perso.php');
          }
  }
  //notifcations
?>
<!doctype html>
<html lang="fr">
  <head>
    <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <title>Connexion</title>
  <link href="https://fonts.googleapis.com/css?family=Karla:400,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.8.95/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/login.css">
  <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/dashboard/">

    

    
<link href="../bootstrap/bootstrap.min.css" rel="stylesheet">

</head>
<body>
   <main class="d-flex align-items-center min-vh-100 py-3 py-md-0">
    <div class="container">
      <div class="card login-card">
        <div class="row no-gutters">
          <div class="col-md-5">
            <img src="mazars.png" alt="login" class="login-card-img">
          </div>
          <div class="col-md-7">
            <div class="card-body">
           <form action="../connexion/login.php" method="post" accept-charset="utf-8">
      <?php echo $error; ?>
      <div class="form-group">
         <label for="email" class="control-label">Email</label>
         <input type="email" id="email" name="Email" class="form-control">
      </div>
      <div class="form-group">
         <label for="password" class="control-label">Mot de passe</label>
         <input type="password" id="password" name="Mdp" class="form-control">
      </div>
      <div class="form-group">
         <button type="submit" name="connect" class="btn btn-primary btn-block">Connexion</button>
      </div>
   </form>
</body>

</html>
