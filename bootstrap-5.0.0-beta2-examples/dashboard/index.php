<?php
include 'application/bdd_connection.php';
$query='SELECT * FROM controle INNER JOIN mission ON controle.mission_id = mission.mission_id INNER JOIN categorie ON controle.categorie_id = categorie.categorie_id ORDER BY controle.mission_id';
  $resultSet = $pdo->query($query);
  $controles = $resultSet->fetchAll();

  $query='SELECT * FROM categorie';
  $resultSet = $pdo->query($query);
  $categories = $resultSet->fetchAll();

  $query='SELECT * FROM mission';
  $resultSet = $pdo->query($query);
  $missions = $resultSet->fetchAll();



  
  $query=$pdo->prepare("SELECT * FROM set_colonne WHERE id = ?");
    $query->execute([1]);
    $colonnes=$query->fetch();

  function getNameByEmail($pdo, $email){
    $query=$pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
    $query->execute([$email]);
    $utilisateur=$query->fetch();
    return $utilisateur['nom']." ".$utilisateur['prenom'];
}    
?>



<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.80.0">
    <title>Documentation</title>

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
    <link href="dashboard.css" rel="stylesheet">
  </head>
  <body>
    
<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
  <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">Mazars</a>
  <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <input class="form-control form-control-dark w-100" type="text" placeholder="Recherche" aria-label="Search">
  <li class="nav-item dropdown">
    <a class="nav-link" href="#" id="dropdown01" data-bs-toggle="dropdown" aria-expanded="false"><img src="bootstrap-icons-1.4.0/bell.svg">   </a>
    <ul class="dropdown-menu" aria-labelledby="Notfications">
      <li><a class="dropdown-item" href="#"><small><i>8 février 2020, Ben :<i><br>J'aime le foot</small></a></li>
      <li><a class="dropdown-item" href="#"><small><i>8 février 2020, Ben :<i><br>J'aime racontere ma vie</small></a></li>
      <li><a class="dropdown-item" href="#"><small><i>8 février 2020, Ben :<i><br>Je deteste Thaylor Swift</small></a></li>
    </ul>
  </li>
  <ul class="navbar-nav px-3">
    <li class="nav-item text-nowrap">
      <a class="nav-link" href="#">Deconnexion</a>
    </li>
  </ul>
</header>

<div class="container-fluid">
  <div class="row">
    <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
      <div class="position-sticky pt-3">
        <ul class="nav flex-column">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="bootstrap-icons-1.4.0/graph-up.svg">
            Tableau de bord
          </a>
            <ul class="dropdown-menu" aria-labelledby="Activité">
              <li><a class="dropdown-item" href="#">Activité</a></li>
              <li><a class="dropdown-item" href="#">Mon activité</a></li>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="bootstrap-icons-1.4.0/person-lines-fill.svg">
            Équipe
          </a>
            <ul class="dropdown-menu" aria-labelledby="Équipe">
              <li><a class="dropdown-item" href="#">Mes missions</a></li>
              <li><a class="dropdown-item" href="carnet_adresse.php">Carnet d'addesses</a></li>
            </ul>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="#">
            <img src="bootstrap-icons-1.4.0/folder-check.svg">
              Documentation
            </a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="bootstrap-icons-1.4.0/info.svg">
            Aide
          </a>
            <ul class="dropdown-menu" aria-labelledby="Nous contacter">
              <li><a class="dropdown-item" href="#">FAQ</a></li>
              <li><a class="dropdown-item" href="#">Nous Contacter</a></li>
            </ul>
          </li>
        </ul>

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
          <span>Outils :</span>
          <a class="link-secondary" href="" aria-label="ajouter un controle">
          <img src="bootstrap-icons-1.4.0/tools.svg">
          </a>
        </h6>
        <ul class="nav flex-column mb-2">
          <li class="nav-item">
            <a class="nav-link" href="form_doc.php">
            <img src="bootstrap-icons-1.4.0/file-earmark-plus.svg">
              Ajouter un contrôle
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="ajout_cat.php">
            <img src="bootstrap-icons-1.4.0/folder-plus.svg">
              Ajouter une catégorie
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="supp_cat.php">
            <img src="bootstrap-icons-1.4.0/folder-minus.svg">
              Supprimer une catégorie
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="form_doc.php">
            <img src="bootstrap-icons-1.4.0/file-earmark-minus.svg">
              Supprimer les contrôles
            </a>
          </li>
        </ul>
       

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
          <span>Filtres : </span>
          <a class="link-secondary" href="" aria-label="ajouter un controle">
          <img src="bootstrap-icons-1.4.0/filter.svg">
          </a>
        </h6>
        <ul class="nav flex-column mb-2">
          <li class="nav-item">
            <a class="nav-link" href="#">
            <img src="bootstrap-icons-1.4.0/briefcase.svg">
              Par Mission : 
              <select name="mission" class="form-control form-control-white w-100 filter-select">
                <option>Selectionner une mission :</option>
                <?php foreach($missions as $mission){ ?>
                  <option value="<?php echo $mission['mission_nom']; ?>"><?php echo $mission['mission_nom']; ?></option>
                <?php } ?>
              </select>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
            <img src="bootstrap-icons-1.4.0/bar-chart-steps.svg">
              Par Catégorie :
              <select name="categorie" class="form-control form-control-white w-100 filter-select">
                <option>Selectionner une categorie :</option>
                <?php foreach($categories as $categorie){ ?>
                  <option value="<?php echo $categorie['categorie_nom']; ?>"><?php echo $categorie['categorie_nom']; ?></option>
                <?php } ?>
              </select>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
            <img src="bootstrap-icons-1.4.0/ui-checks.svg">
              Par Statut :
              <select name="statut" class="form-control form-control-white w-100 filter-select">
                <option>Selectionner un statut :</option>
                <option>Non debute</option>
                <option>Documente</option>
                <option>Revu</option>
                <option>Sign-off</option>
              </select>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
            <img src="bootstrap-icons-1.4.0/calendar-date.svg">
              Par Deadline : 
              <input class="form-control form-control-dark w-100" type="date" placeholder="jj/mm/aaaa" aria-label="jj/mm/aaaa">
            </a>
          </li>
        </ul>
      </div>
    </nav>
    


    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <br>
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-colonnes">
           Modifier les colonnes
      </button>
      <!-- Modal -->
      <div class="modal fade" id="modal-colonnes" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">

            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Modifier les colonnes</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="mission" <?php if($colonnes['mission'] == 0){ echo "checked"; }?>>
              <label class="form-check-label" for="mission">mission</label>
            </div>

            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="categorie" <?php if($colonnes['categorie'] == 0){ echo "checked"; }?>>
              <label class="form-check-label" for="categorie">categorie</label>
            </div>

            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="nom_du_controle" <?php if($colonnes['nom_du_controle'] == 0){ echo "checked"; }?>>
              <label class="form-check-label" for="nom_du_controle">nom du controle</label>
            </div>

            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="deadline" <?php if($colonnes['deadline'] == 0){ echo "checked"; }?>>
              <label class="form-check-label" for="deadline">deadline</label>
            </div>

            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="affecte_a" <?php if($colonnes['affecte_a'] == 0){ echo "checked"; }?>>
              <label class="form-check-label" for="affecte_a">affecte a </label>
            </div>

            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="statut" <?php if($colonnes['statut'] == 0){ echo "checked"; }?>>
              <label class="form-check-label" for="statut">statut</label>
            </div>

            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="niveau_de_risque" <?php if($colonnes['niveau_de_risque'] == 0){ echo "checked"; }?>>
              <label class="form-check-label" for="niveau_de_risque">niveau de risque</label>
            </div>

            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="design" <?php if($colonnes['design'] == 0){ echo "checked"; }?>>
              <label class="form-check-label" for="design">design</label>
            </div>

            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="efficacite" <?php if($colonnes['efficacite'] == 0){ echo "checked"; }?>>
              <label class="form-check-label" for="efficacite">efficacite</label>
            </div>

            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="commentaires" <?php if($colonnes['commentaires'] == 0){ echo "checked"; }?>>
              <label class="form-check-label" for="commentaires">commentaires</label>
            </div>


            </div>
            <div class="modal-footer">
              <button type="submit" id="edit-colonne" class="btn btn-primary">Sauvegarder</button>
            </div>

          </div>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th>#</th>
              <?php if($colonnes['mission'] == "0"){ ?>
              <th>Mission</th>
              <?php } ?>
              <?php if($colonnes['categorie'] == "0"){ ?>
              <th>Categorie</th>
              <?php } ?>
              <?php if($colonnes['nom_du_controle'] == "0"){ ?>
              <th>Nom du controle</th>
              <?php } ?>
              <?php if($colonnes['deadline'] == "0"){ ?>
              <th>Deadline</th>
              <?php } ?>
              <?php if($colonnes['affecte_a'] == "0"){ ?>
              <th>Affecté a</th>
              <?php } ?>
              <?php if($colonnes['statut'] == "0"){ ?>
              <th>Statut</th>
              <?php } ?>
              <?php if($colonnes['niveau_de_risque'] == "0"){ ?>
              <th>Niveau de risque</th>
              <?php } ?>
              <?php if($colonnes['design'] == "0"){ ?>
              <th>Design</th>
              <?php } ?>
              <?php if($colonnes['efficacite'] == "0"){ ?>
              <th>Efficacité</th>
              <?php } ?>
              <?php if($colonnes['commentaires'] == "0"){ ?>
              <th>Commentaires</th>
              <?php } ?>
            </tr>
          </thead>
           
          <tbody>
            <?php foreach($categories as $categorie){ ?>
            <tr>
              <th colspan="11"><?php echo $categorie['categorie_nom']; ?></th>
            </tr>
            <?php foreach($controles as $controle){ 
              if($controle['categorie_nom'] == $categorie['categorie_nom']){ ?>
           <tr>
              <td><input type='checkbox' name='' value=""></td>
              <?php if($colonnes['mission'] == "0"){ ?>
              <td class="search-mission"><?php echo $controle['mission_nom']; ?></td>
              <?php } ?>
              <?php if($colonnes['categorie'] == "0"){ ?>
              <td class="search-categorie"><?php echo $controle['categorie_nom']; ?></td>
              <?php } ?>
              <?php if($colonnes['nom_du_controle'] == "0"){ ?>
              <td><?php echo $controle['nom_du_controle']; ?></td>
              <?php } ?>
              <?php if($colonnes['deadline'] == "0"){ ?>
              <td><?php echo $controle['deadline']; ?></td>
              <?php } ?>
              <?php if($colonnes['affecte_a'] == "0"){ ?>
              <td class="search-statut"><?php 
               $email = "";
               if($controle["statut"] == 'Non debute'){
                 $email = $controle["email_utilisateur_realise_par"];
               } elseif($controle["statut"] == 'Documente'){
                 $email = $controle["email_utilisateur_revu_par"];
               }elseif($controle["statut"] == 'Revu'){
                 $email = $controle["email_utilisateur_revu_par"];
               }else{
                 $email = $controle["email_utilisateur_sign_off"];
               }
              echo getNameByEmail($pdo, $email); ?></td>
<?php } ?>
<?php if($colonnes['statut'] == "0"){ ?>

              <?php 
              $color_statut = "";
              if($controle["statut"] == 'Non debute'){
                $color_statut = "red";
              }
              elseif($controle["statut"] == 'Documente'){
                $color_statut = "orange";
              }elseif($controle["statut"] == 'Revu'){
                $color_statut = "yellowgreen";
              }else{
                $color_statut = "green";
              }    

             
              $color_risque = "";
              if($controle["niveau_de_risque"] == 'Eleve'){
                $color_risque = "red";
              }
              elseif($controle["niveau_de_risque"] == 'Moyen'){
                $color_risque = "orange";
              }else{
                $color_risque = "green";
              }    

              $color_design = "";
              if($controle["design"] == 'Non-effectif'){
                $color_design = "red";
              }
              elseif($controle["design"] == 'Remarque mineure'){
                $color_design = "orange";
              }elseif($controle["design"] == 'Remarque majeure'){
                  $color_design = "yellow";
              }else{
                $color_design = "green";
              }   

              $color_efficacite = "";
              if($controle["efficacite"] == 'Non-effectif'){
                $color_efficacite = "red";
              }
              elseif($controle["efficacite"] == 'Remarque mineure'){
                $color_efficacite = "orange";
              }elseif($controle["efficacite"] == 'Remarque majeure'){
                  $color_efficacite = "yellow";
              }else{
                $color_efficacite = "green";
              }    
              ?>
              <td class='statut' style="background-color: <?php echo $color_statut ;?>;">
              <span class='span-statut'><?php echo $controle['statut'] ;?></span>
                    <select class='statut-select' data-id='<?php echo $controle['id'] ;?>'> 
                    <option value='Non debute'>Non debute</option>
                    <option value='Documente'>Documente</option>
                    <option value='Revu'>Revu</option>
                    <option value='Sign-off'>Sign-off</option>
                    </select>
              
              </td>
              <?php } ?>

              <?php if($colonnes['niveau_de_risque'] == "0"){ ?>
              <td style="background-color: <?php echo $color_risque ;?>;"><?php echo $controle['niveau_de_risque']; ?></td>
              <?php } ?>
              <?php if($colonnes['design'] == "0"){ ?>
              <td style="background-color: <?php echo $color_design ;?>;"><?php echo $controle['design']; ?></td>
              <?php } ?>
              <?php if($colonnes['efficacite'] == "0"){ ?>
              <td style="background-color: <?php echo $color_efficacite ;?>;"><?php echo $controle['efficacite']; ?></td>
              <?php } ?>
              <?php if($colonnes['commentaires'] == "0"){ ?>
              <td class="commentaire">
              <span class='span-commentaire'><?php echo $controle['commentaires']; ?></span>
              <div class="textarea-commentaire">
              <textarea  data-id='<?php echo $controle['id'] ;?>'></textarea>
              <button class="bouton-valider" type="button">Valider</button>
            </div>
              </td>
              <?php } ?>
            </tr>
            <?php } } ?>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</div>
<footer class="my-5 pt-5 text-muted text-center text-small">
  <p class="mb-1">&copy; 2017–2021 MAZARS SAS</p>
  <ul class="list-inline">
    <li class="list-inline-item"><a href="#">Privacy</a></li>
    <li class="list-inline-item"><a href="#">Terms</a></li>
    <li class="list-inline-item"><a href="#">Support</a></li>
  </ul>
</footer>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"
        integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg=="
        crossorigin="anonymous"></script>
    <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
  
<script>

  // statut 
  var span_statut = document.querySelectorAll('.span-statut');

for(var i = 0; i < span_statut.length; i++){
  const on = span_statut[i];
    on.addEventListener('dblclick', () => {
      var td = on.closest('td');
      td.classList.add('active');
    })
}

var select_statut = document.querySelectorAll('.statut-select');

for(var i = 0; i < select_statut.length; i++){
  const on = select_statut[i];
    on.addEventListener('change', () => {
      var td = on.closest('td');
      var select_value = on.value;
      var id = on.getAttribute("data-id");
      var span = td.querySelector('span');

      

      $.ajax({
          url: "edit_statut.php",
          method: "POST",
          data: "id=" + id + "&value=" + select_value,
          success: function() {
            span.innerHTML = select_value;
            var color ="";
            
            switch (select_value) {
              case 'Non debute':
                color = "red";
                break;
              case 'Documente':
                color = "orange";
              case 'Revu':
                color ="yellowgreen";
                break;
              default:
                color = "green";
            }
            td.style.backgroundColor = color;
            td.classList.remove('active');

          }
      })

      
    })
}
// fin statut

// commentaire
var span_commentaire = document.querySelectorAll('.span-commentaire');
var bouton_valider = document.querySelectorAll('.bouton-valider');
// var textarea = document.querySelectorAll('textarea-commentaire textarea');


for(var i = 0; i < span_commentaire.length; i++){
  const on = span_commentaire[i];
    on.addEventListener('dblclick', () => {
      var td = on.closest('td');
      td.classList.add('active');
    })
}


for(var i = 0; i < bouton_valider.length; i++){
  const on = bouton_valider[i];
    on.addEventListener('click', () => {
      var td = on.parentNode.closest('td');
      var textarea = td.querySelector('textarea');
      var textarea_value = textarea.value;
      var id = textarea.getAttribute("data-id");
      var span = td.querySelector('span');

      

      $.ajax({
          url: "edit_commentaire.php",
          method: "POST",
          data: "id=" + id + "&value=" + textarea_value,
          success: function() {
            span.innerHTML = textarea_value;
           
            td.classList.remove('active');

          }
      })

      
    })
}
//fin commentaire
//modifier colonnes
var submit_edit_colonne = document.getElementById('edit-colonne');
submit_edit_colonne.addEventListener('click', () =>{
  var mission_check = document.getElementById('mission');
  var categorie_check = document.getElementById('categorie');
  var nom_du_controle_check = document.getElementById('nom_du_controle');
  var deadline_check = document.getElementById('deadline');
  var affecte_a_check = document.getElementById('affecte_a');
  var statut_check = document.getElementById('statut');
  var niveau_de_risque_check = document.getElementById('niveau_de_risque');
  var design_check = document.getElementById('design');
  var efficacite_check = document.getElementById('efficacite');
  var commentaires_check = document.getElementById('commentaires');

  if (mission_check.checked == true) {
        var mission = 0;
    } else {
        var mission = 1;
    }
    if (categorie_check.checked == true) {
        var categorie = 0;
    } else {
        var categorie = 1;
    }
    if (nom_du_controle_check.checked == true) {
        var nom_du_controle = 0;
    } else {
        var nom_du_controle = 1;
    }
    if (deadline_check.checked == true) {
        var deadline = 0;
    } else {
        var deadline = 1;
    }
    if (affecte_a_check.checked == true) {
        var affecte_a = 0;
    } else {
        var affecte_a = 1;
    }
    if (statut_check.checked == true) {
        var statut = 0;
    } else {
        var statut = 1;
    }
    if (niveau_de_risque_check.checked == true) {
        var niveau_de_risque = 0;
    } else {
        var niveau_de_risque = 1;
    }
    if (design_check.checked == true) {
        var design = 0;
    } else {
        var design = 1;
    }
    if (efficacite_check.checked == true) {
        var efficacite = 0;
    } else {
        var efficacite = 1;
    }
    if (commentaires_check.checked == true) {
        var commentaires = 0;
    } else {
        var commentaires = 1;
    }

    $.ajax({
        url: "edit_colonnes.php",
        method: "POST",
        data: "mission=" + mission + "&categorie=" + categorie + "&nom_du_controle=" + nom_du_controle + "&deadline=" + deadline +
            "&affecte_a=" + affecte_a + "&statut=" + statut + "&niveau_de_risque=" + niveau_de_risque + "&design=" +
            design + "&efficacite=" + efficacite + "&commentaires=" + commentaires,
        success: function() {
            window.location.reload();
        }
    })


})
//fin modifier colonne



  </script>
  
  
    </body>
</html>
