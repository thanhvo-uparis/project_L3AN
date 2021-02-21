<!DOCTYPE html>
<html>
    <head>
        <title>Documentation</title>
        <meta charset="utf-8"/>
        <link rel="stylesheet" type="text/css" href="document.css">
        <meta name="viewport" content="width=device-width , initial-scale=1.0"/> 
    </head>
    
    <body>
        <!--<scipt>
        function confirmation(){
            return confirm("etes vous sur de vouloir supprimer ce controle ?);
            }
        </scipt> -->
        <header>
        <a href="accueil.html"><img src="mazars-logo.png" alt="logo de mazar"></a>
        <nav>
            <ul>
                <li><a href="accueil.html">ACCUEIL</a></li>
                <li><a href="document.html">DOCUMENT</a></li>
                <li><a href="activite.html">ACTIVITE</a></li>
                <li><a href="nous_contacter.html">NOUS CONTACTER</a></li>
            </ul>
        </nav>
        </header>
        
        <main>
        <div class="filtre">
            <ol>
                <li>Année</li>
                <li>Statut</li>
                <li>Niveau de risque</li>
                <li>Réalisé par</li>
                <li>Etat avancement</li>
            </ol>
        </div>
            
            <br><br>
        <div class="tableau_documentation">
        <div class="barre_doutil">
                    <button type="button"><a href="formulaire.html">Ajouter controles</a></button>
                    <button type="button">Ajouter une catégories</button>
                    <button type="button">Supprimer une catégories</button>
                    <button type="button">Mofifer statut</button>
        </div class="tableau">
            
            <?php
            
            $db = new mysqli('localhost', 'root', '', 'bdd_projet-l3an1') or die("unable to connect");
            
            
            $sql = "select * from control order by categorie";
            $result = mysqli_query($db, $sql) or die ("bad query");
            
            echo "<table border='1' class='tab'>";
            echo "<tr>
                       <th>Nom de controle</th>
                       <th>DEADLINE</th>
                       <th>Realise par</th>
                       <th>Statut</th>
                       <th>Niveau de risque</th>
                       <th>Design</th>
                       <th>Efficacite</th>
                       <th>Categorie</th>
                       <th>Commentaires</th>
                       </tr>\n";
            
            while($row=mysqli_fetch_row($result)){
                echo"<tr>
                <td>{$row[1]}</td>
                <td>{$row[2]}</td>
                <td>{$row[3]}</td>";
                if($row[4] == 'Non debute'){
                echo "<td style=\"background :red;\">{$row[4]}</td>";
                    } elseif($row[4] == 'Documente'){
                    echo "<td style=\"background :orange;\">{$row[4]}</td>";
                    }elseif($row[4] == 'Revu'){
                    echo "<td style=\"background :yellowgreen;\">{$row[4]}</td>";
                }else{
                        echo "<td style=\"background :green;\">{$row[4]}</td>";
                }
                if($row[5] == 'Eleve'){
                echo "<td style=\"background :red;\">{$row[5]}</td>";
                    } elseif($row[5] == 'Moyen'){
                    echo "<td style=\"background :orange;\">{$row[5]}</td>";
                    }else{
                        echo "<td style=\"background :green;\">{$row[5]}</td>";
                }
                if($row[6] == 'Non-effectif'){
                echo "<td style=\"background :red;\">{$row[6]}</td>";
                    } elseif($row[6] == 'Remarque majeur'){
                    echo "<td style=\"background :orange;\">{$row[6]}</td>";
                    }elseif($row[6] == 'Remarque mineur'){
                    echo "<td style=\"background :yellowgreen;\">{$row[6]}</td>";
                }else{
                        echo "<td style=\"background :green;\">{$row[6]}</td>";
                }
                if($row[7] == 'Non-effectif'){
                echo "<td style=\"background :red;\">{$row[7]}</td>";
                    } elseif($row[7] == 'Remarque majeur'){
                    echo "<td style=\"background :orange;\">{$row[7]}</td>";
                    }elseif($row[7] == 'Remarque mineur'){
                    echo "<td style=\"background :yellowgreen;\">{$row[7]}</td>";
                }else{
                        echo "<td style=\"background :green;\">{$row[7]}</td>";
                }
                echo"<td>{$row[8]}</td>
                <td>{$row[9]}</td>
                <td><a href='supprimer.php?idl=$row[0]' class='bouton bouton-supprimer' onclick='return confirmation();'>supprimer</a></td>
                </tr>";
            }
            echo "</table>";
            ?>
            </div>
    </main>
   </body>
</html>