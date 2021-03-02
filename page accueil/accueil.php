<!DOCTYPE html>
<html lang="fr-FR">
<head>
	<title>Mazars France - Organisation indépendante d’audit, de conseil et de services comptables et fiscaux - France</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="./style_accueil.css">
</head>
<body>
     <body>
    <style type="text/css">
      #tete{
      background-color: #FFEFD5; 
      }
    </style>

    <div id="tete">
      <br><br>
       &emsp;&emsp;&emsp; <img src="https://www.mazars.fr/extension/ezmazars_rwdesign/design/mazars2020/images/mazars-logo.png" width="200">
       
      
     <div id="menu">
      <ul class="parents">
          <li><a href="">Présentation</a>
             <ul class="sous_menu">
             <li><a href="./presentation.html">Qui nous somme</a></li>
             <li><a href="">Notre équipe</a></li>
             </ul>
          </li>
         
         <li><a href="./document/document.html">Document</a></li>
         <li><a href="">Partenaire</a></li>
         <li><a href="contact.php">contact</a></li>
      </ul>
      </div> 
      <br>
     </div>
     <br> <!-- <img src="https://github.com/thanhvo-uparis/project_L3AN/blob/main/contact.png?raw=true" alt="img_background" width=100%> -->
     <br>
     
  <div id="grand_container">
    <div class="container">
     <div class="row">
        <div class="col-12">
          <form action="login.php" method="POST" role="form">
          <legend>Connexion</legend>

          <div class="form-group">
            <img src="./icon_username.png" width="20px" height="15px">
            <label for="">Email</label>
            <input type="email" class="form-control" id="" placeholder="Adresse e-mail" name="email">
          </div>

          <div class="form-group">
            <img src="./icon_motdepasse.png" width="20px" height="15px">
            <label for="">Mot de passe</label>
            <input type="password" class="form-control" name="mot_de_passe" id="" placeholder="Saisissez votre mot de passe">
          </div>

          <button type="submit" class="btn btn-primary">Se Connecter</button>
          </form>
          <form action="oublie_mdp.php" method="post">
             <div>
               <button type="submit">Oublié la mot de passe?</button>
             </div>
          </form>
          
        </div>
         
    </div>
  </div>
      
  </div> 
 <style type="text/css">
    #grand_container{
      width: 100%;
      height: 100%;
      border: 1px solid orange;
      background-image: url(background.png);
      background -size: cover; 
    }
   </style>>  
  
  <?php
   if(isset($_POST['email'], $_POST['mot_de_passe'])){
     $stmt = $bdd->prepage('select mot_de_passe from utilisateur where email = ?');
     $stmt->execute([$_POST['email']]);
     $mot_de_passe = $stmt->fetchColumn();
     echo "$mot_de_passe";
   } 

  ?>
	 
  
</body>
</html>