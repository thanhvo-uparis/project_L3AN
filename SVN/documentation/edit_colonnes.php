<?php
include 'application/bdd_connection.php';
      
     
    $query=$pdo->prepare("update set_colonne set mission = ?,categorie=?, nom_du_controle=?, deadline=?,affecte_a=?, statut=?,niveau_de_risque=?,design=?,efficacite=?,commentaires=? where id= ?");
    $query->execute([$_POST['mission'],$_POST['categorie'],$_POST['nom_du_controle'],$_POST['deadline'],$_POST['affecte_a'],$_POST['statut'],$_POST['niveau_de_risque'],$_POST['design'],$_POST['efficacite'],$_POST['commentaires'], 1]);

?>