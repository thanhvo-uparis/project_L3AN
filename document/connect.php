<?php
    $nom_control = $_POST['nom_du_controle'];
    $deadline = $_POST['deadline'];
    $realise_par = $_POST['realise_par'];
    $statut = $_POST['statut'];
    $niv_risque = $_POST['niveau_de_risque'];
    $design = $_POST['design'];
    $efficacite = $_POST['efficacite'];
    $categorie = $_POST['categorie'];

     $connect = mysqli_connect("localhost", "root", "", "bdd_projet-l3an1");

    $sql = "insert into tableau_doc(nom_du_controle, deadline, realise_par, statut, niveau_de_risque, design, efficacite, categorie ) values ('$nom_control', '$deadline', '$realise_par', '$statut',  '$niv_risque', '$design', '$efficacite', '$categorie')";
    if($connect ->query($sql) === TRUE) {
        echo "réussi à l'ajoute à BDD";
    } else {
        echo "Error: "  .$sql . "<br>" . $connect -> error;
    }

    $connect -> close();
    header("Location: document.php");

?>
