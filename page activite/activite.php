<?php

$bdd = new PDO("mysql:host=localhost;dbname=bdd_projet-l3an1", "root", "");


$requete = $bdd-> query("SELECT statut,COUNT * FROM controle GROUP BY statut"); 

?>


<!DOCTYPE html>
<html>
    <head>
        <title>Documentation</title>
        <meta charset="utf-8"/>
        <link rel="stylesheet" type="text/css" href="document.css">
        <meta name="viewport" content="width=device-width , initial-scale=1.0"/> 
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="Activité.css">
    </head>
    
    <body>
        <form action="document.php" method="post"></form>
        <header>
             <img class="logo" src="logoMazars.png" alt="logo">
             <nav>
                 <ul class="nav_links">
                     <li><a href="">Accueil</a></li>
                     <li><a href="">Activité</a></li>
                     <li><a href="">Equipe</a></li>
                </ul>
             </nav>
             <a class="button" href="">Contact</a>
        </header>
        

        <main>

        
        <!-- tableau -->
        <div class="tableau">
            <table>
                <thead>
                <tr>
                    <th>Sign-off</th>
                    <th>Revu</th>
                    <th>Documente</th>
                    <th>Non debute</th>
                    <th></th>
                </tr>
                </thead>
                <?php 
                while($result = $requete->fetch())
                {
                ?>
                <tr>
                     <td><?php echo $result['statut']; ?></td>
                   
                </tr> 
                <?php 
                }
                ?> 
            </table>
        </div>
            </div>
        </main>
        
    </body>
</html>