<?php
include 'application/bdd_connection.php';
include 'functions.php';
if (isset($_SESSION['admin_email']) && $_SESSION['admin_email'] != '') {
    $all_missions = concernedByMission($pdo, $_SESSION['admin_email']);
    $all_categories = concernedByCategorie($pdo, $_SESSION['admin_email']);
    ?>

    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Page Activité</title>
        <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/dashboard/">
        <!-- Bootstrap core CSS -->
        <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Custom styles for this template -->
        <link href="activite_perso.css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
        <script src="assets/js/script.js"></script>
    </head>
    <body>

    <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">Mazars</a>
        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false"
                aria-label="Toggle navigation">
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
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#flush-collapseOne" aria-expanded="false"
                                            aria-controls="flush-collapseOne">
                                        <img src="bootstrap-icons-1.4.0/graph-up.svg">Tableau de bord
                                    </button>
                                </h2>
                            </div>
                            <div id="flush-collapseOne" class="accordion-collapse collapse"
                                 aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    <button type="button" class="btn nav-link"><img
                                                src="bootstrap-icons-1.4.0/bar-chart-line.svg"><a href="activite.php">
                                            Activité</a></button>
                                    <button type="button" class="btn nav-link"><img
                                                src="bootstrap-icons-1.4.0/list-task.svg"><a href="activite_perso.php">
                                            Mon activité</a></button>
                                </div>
                            </div>
                            <div class="accordion accordion-flush" id="accordionFlushExample">
                                <div class="accordion-item disabled">
                                    <h2 class="accordion-header" id="flush-headingOne">
                                        <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo"
                                                aria-expanded="false" aria-controls="flush-collapseTwo">
                                            <img src="bootstrap-icons-1.4.0/person-lines-fill.svg"> Équipe
                                        </button>
                                    </h2>
                                </div>
                                <div id="flush-collapseTwo" class="accordion-collapse collapse"
                                     aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        <button type="button" class="btn nav-link"><img
                                                    src="bootstrap-icons-1.4.0/clipboard-minus.svg"><a
                                                    href="mission.php"> Mes missions</a></button>
                                        <button type="button" class="btn nav-link"><img
                                                    src="bootstrap-icons-1.4.0/book-half.svg"><a
                                                    href="carnet_addresse.php"> Carnet d'addesses</a></button>
                                    </div>
                                </div>

                                <div class="accordion accordion-flush" id="accordionFlushExample">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="flush-headingTree">
                                            <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#flush-collapseTree"
                                                    aria-expanded="false" aria-controls="flush-collapseTree">
                                                <img src="bootstrap-icons-1.4.0/folder-check.svg"> Documentation
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="flush-collapseTree" class="accordion-collapse collapse"
                                         aria-labelledby="flush-headingTree" data-bs-parent="#accordionFlushExample">
                                        <div class="accordion-body">
                                            <button type="button" class="btn nav-link"><img
                                                        src="bootstrap-icons-1.4.0/folder-plus.svg"><a href="index.php">
                                                    Mes contrôles</a></button>
                                        </div>
                                    </div>
                                    <div class="accordion accordion-flush" id="accordionFlushExample">
                                        <div class="accordion-item nav-item">
                                            <h2 class="accordion-header" id="flush-headingFor">
                                                <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#flush-collapseFor"
                                                        aria-expanded="false" aria-controls="flush-collapseFor">
                                                    <img src="bootstrap-icons-1.4.0/info.svg"> Aide
                                                </button>
                                            </h2>
                                        </div>
                                        <div id="flush-collapseFor" class="accordion-collapse collapse"
                                             aria-labelledby="flush-headingFor" data-bs-parent="#accordionFlushExample">
                                            <div class="accordion-body">
                                                <button type="button" class="btn  nav-link"><img
                                                            src="bootstrap-icons-1.4.0/folder-plus.svg"><a
                                                            href="index.php"> FAQ</a></button>
                                                <button type="button" class="btn  nav-link"><img
                                                            src="bootstrap-icons-1.4.0/folder-plus.svg"><a
                                                            href="nous_contacter.html"> Nous contacter</a></button>
                                            </div>
                                        </div>
                    </ul>

                    <ul class="nav flex-column mb-2">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <img src="bootstrap-icons-1.4.0/briefcase.svg">
                                <p style="color: white; font-size: 15px">Mes Missions</p>
                                <select name="mission"
                                        class="form-control form-control-white w-100 left-filter filter-select filter-select-mission"
                                        data-type="mission" data-element="cicle-chart">
                                    <option value="0">Selectionner une mission</option>
                                    <?php foreach ($all_missions as $mission) { ?>
                                        //verifie si l'utilisateur fait bien parti de la mission grace a la fonction concernedByMission()
                                        <option value="<?php echo $mission['mission_id']; ?>"><?php echo $mission['mission_nom']; ?></option>
                                    <?php } ?>
                                </select>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <img src="bootstrap-icons-1.4.0/bar-chart-steps.svg">
                                <p style="color: white; font-size: 15px">Mes Catégories</p>
                                <select name="mission"
                                        class="form-control form-control-white w-100 left-filter filter-select"
                                        data-type="category" data-element="cicle-chart">
                                    <option value="0">Selectionner une categorie</option>
                                    <?php foreach ($all_categories as $category) { ?>
                                        //verifie si l'utilisateur fait bien parti de la mission grace a la fonction concernedByMission()
                                        <option value="<?php echo $category['id']; ?>"><?php echo $category['nom_categorie']; ?></option>
                                    <?php } ?>
                                </select>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <img src="bootstrap-icons-1.4.0/briefcase.svg">
                                <p style="color: white; font-size: 15px">Collaborateurs</p>
                                <select name="mission"
                                        class="form-control form-control-white w-100 left-filter filter-select-mission-collaborateurs"
                                        data-element="bar-chart" data-type="collaborateurs">
                                    <option value="0">Selectionner une mission</option>
                                    <?php foreach ($all_missions as $mission) { ?>
                                        //verifie si l'utilisateur fait bien parti de la mission grace a la fonction concernedByMission()
                                        <option value="<?php echo $mission['mission_id']; ?>"><?php echo $mission['mission_nom']; ?></option>
                                    <?php } ?>
                                </select>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <img src="bootstrap-icons-1.4.0/briefcase.svg">
                                <p style="color: white; font-size: 15px">Recherche par nom du controle</p>
                                <input type="text" class="controller-name-input form-control left-filter"
                                       placeholder="Nom du controle" data-element="search-controller">
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
                            <button id="ITGC" type="button"
                                    class="btn btn-sm btn-outline-secondary"><?php echo $_SESSION['admin_email']; ?></button>
                        </div>
                    </div>
                </div>


                <!-- Tableau missions -->


                <!-- Graphique Statut -->
                <div class="result-wrap" style="width: 100%; text-align: center"
                     data-type="cicle-chart">
                    <div class="chart-table-html"></div>
                    <div class="chart-wrap">
                        <h3 class="title-chart">Chart</h3>
                        <canvas class="ChartStatut" id="chart-item"></canvas>
                    </div>
                    <div class="category-html"></div>
                </div>
                <div class="result-wrap" style="width: 100%; text-align: center"
                     data-type="bar-chart">
                    <div class="chart-wrap">
                        <h3 class="title-chart">Chart</h3>
                        <canvas id="bar-chart-item"></canvas>
                        <div class="user-wrap-wrap"></div>
                    </div>
                </div>
                <div class="controller-html result-wrap" data-type="search-controller"></div>
            </main>
        </div>
    </div>


    <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js"
            integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"
            integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous">
    </script>
    </body>
    </html>


    <?php

} else {
    header('Location:login.php');
} ?>