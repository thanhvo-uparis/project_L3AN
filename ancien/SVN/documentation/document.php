<?php 
            $db = new mysqli('localhost', 'root', '', 'bdd_projet-l3an1') or die("unable to connect");
            
            
            $sql = "select * from controle order by categorie";
            $result = mysqli_query($db, $sql) or die ("bad query");
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Documentation</title>
        <meta charset="utf-8"/>
        <link rel="stylesheet" type="text/css" href="document.css">
        <meta name="viewport" content="width=device-width , initial-scale=1.0"/> 
    </head>
    
    <body>
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
            
            <br>
        <div class="tableau_documentation">
        <div class="barre_doutil">
                    <button type="button"><a href="form_doc.php">Ajouter controles</a></button>
                    <button type="button"><a href="ajout_cat.php">Ajouter une catégorie</a></button>
                    <button type="button"><a href="supp_cat.php">Supprimer une categorie</a></button>
                    <button type="button"><a href="mod_statut.php">Modifer statut</a></button>
            </div></div>
        <div class="table">
            <form action="sup_several.php" method="post">
            <table border='1' class='tab'>
            <tr>
                       <th> </th>
                       <th>Nom de controle</th>
                       <th>Deadline</th>
                       <th>Affecté à</th>
                       <th>Statut</th>
                       <th>Niveau de risque</th>
                       <th>Design</th>
                       <th>Efficacite</th>
                       <th>Categorie</th>
                       <th>Commentaires</th>
            </tr>
            <?php    
            while($row=mysqli_fetch_array($result)){
                echo"<tr>
                <td><input type='checkbox' name='ids[]' value=".$row['id']."></td>
                <td>".$row["nom_du_controle"]."</td>
                <td>".$row["deadline"]."</td>";
                if($row["statut"] == 'Non debute'){
                echo "<td>".$row["email_utilisateur_realise_par"]."</td>";
                    } elseif($row["statut"] == 'Documente'){
                    echo "<td style=\"background :orange;\">".$row["statut"]."</td>";
                    }elseif($row["statut"] == 'Revu'){
                    echo "<td>".$row["email_utilisateur_revu_par"]."</td>";
                }else{
                        echo "<td>".$row["email_utilisateur_sign_off"]."</td>";
                }
                if($row["statut"] == 'Non debute'){
                echo "<td style=\"background :red;\">".$row["statut"]."</td>";
                    } elseif($row["statut"] == 'Documente'){
                    echo "<td style=\"background :orange;\">".$row["statut"]."</td>";
                    }elseif($row["statut"] == 'Revu'){
                    echo "<td style=\"background :yellowgreen;\">".$row["statut"]."</td>";
                }else{
                        echo "<td style=\"background :green;\">".$row["statut"]."</td>";
                }
                if($row["niveau_de_risque"] == 'Eleve'){
                echo "<td style=\"background :red;\">".$row["niveau_de_risque"]."</td>";
                    } elseif($row["niveau_de_risque"] == 'Moyen'){
                    echo "<td style=\"background :orange;\">".$row["niveau_de_risque"]."</td>";
                    }else{
                        echo "<td style=\"background :green;\">".$row["niveau_de_risque"]."</td>";
                }
                if($row["design"] == 'Non-effectif'){
                echo "<td style=\"background :red;\">".$row["design"]."</td>";
                    } elseif($row["design"] == 'Remarque majeur'){
                    echo "<td style=\"background :orange;\">".$row["design"]."</td>";
                    }elseif($row["design"] == 'Remarque mineur'){
                    echo "<td style=\"background :yellowgreen;\">".$row["design"]."</td>";
                }else{
                        echo "<td style=\"background :green;\">".$row["design"]."</td>";
                }
                if($row["efficacite"] == 'Non-effectif'){
                echo "<td style=\"background :red;\">".$row["efficacite"]."</td>";
                    } elseif($row["efficacite"] == 'Remarque majeur'){
                    echo "<td style=\"background :orange;\">".$row["efficacite"]."</td>";
                    }elseif($row["efficacite"] == 'Remarque mineur'){
                    echo "<td style=\"background :yellowgreen;\">".$row["efficacite"]."</td>";
                }else{
                        echo "<td style=\"background :green;\">{$row["efficacite"]}</td>";
                }
                echo"<td>".$row["categorie"]."</td>
                <td>".$row["commentaires"]."</td>
                <td><a href='supprimer.php?idl=".$row['id']."'>Supprimer</a></td>
                </tr>";
                
            }
            ?>
            </table>
                <input type="submit" name="delete" id="delete" value="supprime la selection">
                </form>
            </div> 
    </main>
   </body>
</html>