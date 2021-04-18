<?php 
$todo = '';

if(isset($_POST['todo'])){
    $todo = $_POST['todo'];
}

switch ($todo) {
    case 'fetch':
        /*
            Realise la connexion avec la base de données
        */
        include '../connexion/bdd_connection.php';
        /* 
            Verifie que l'utilisateur est bien connecte 
        */
            if(isset($_SESSION['admin_email']) && $_SESSION['admin_email'] !=''){

            
            $now = date('Y-m-d');//date actuel
            $nowPlus5 = strtotime($now."+ 5 days");//delais de 5 jours
            $nowPlus5 = date("Y-m-d",$nowPlus5);// verifie la date 

            //requete du traitement de la deadline
            $query=$pdo->prepare("SELECT * FROM controle WHERE (deadline <= ? AND deadline >= ?) AND (email_utilisateur_realise_par = ? OR email_utilisateur_revu_par = ? OR email_utilisateur_sign_off = ?);");
            $query->execute([$nowPlus5, $now, $_SESSION['admin_email'],$_SESSION['admin_email'],$_SESSION['admin_email']]);
            $notifs=$query->fetchAll();
        
            //requete sur le changement de statut
            $query=$pdo->prepare("SELECT * FROM controle WHERE (email_utilisateur_realise_par = ? OR email_utilisateur_revu_par = ? OR email_utilisateur_sign_off = ?);");
            $query->execute([$_SESSION['admin_email'],$_SESSION['admin_email'],$_SESSION['admin_email']]);
            $notifsStatut=$query->fetchAll();

            $html = '';
        
            foreach ($notifsStatut as $notifStatut) {
                $classNotifs = '';
                if($notifStatut['lu_statut']){
                  $classNotifs = 'notif-read';
                } 

                //Message pour la notification concernant le changement de statut
                $html .= '<li class="'.$classNotifs.'"><a class="dropdown-item" href="#"><small><i><i><br>Le statut a changé en '.$notifStatut["statut"].' pour : '.$notifStatut["nom_du_controle"].'</small></a></li>';

                //Verifie si la notification a ete lu ou non
                $query=$pdo->prepare("UPDATE controle set lu_statut = ? where id= ?");
                $query->execute([1, $notifStatut['id']]);
            }
            foreach ($notifs as $notif) {
    
                $classNotifs = '';
                if($notif['lu']){
                    $classNotifs = 'notif-read';
                } 

                //Message pour la notification concernant l'approche de la deadline
                $html .= '<li class="'.$classNotifs.'"><a class="dropdown-item" href="#"><small ><i>'.$notif["deadline"].', <i><br>Attention la deadline pour : '.$notif["nom_du_controle"].'</small></a></li>';

                //Verifie si la notification a ete lu ou non
                $query=$pdo->prepare("UPDATE controle set lu = ? where id= ?");
                $query->execute([1, $notif['id']]);
            }
            
            echo $html;
            exit();

    
        }else{}
        //deconnexion
    break;
    
    default:
        /* 
            Verifie que l'utilisateur est bien connecte 
        */
        if(isset($_SESSION['admin_email']) && $_SESSION['admin_email'] !=''){


            $now = date('Y-m-d');//date actuel
            $nowPlus5 = strtotime($now."+ 5 days");//delais de 5 jours
            $nowPlus5 = date("Y-m-d",$nowPlus5);// verifie la date 

            $query=$pdo->prepare("SELECT * FROM controle WHERE (deadline <= ? AND deadline >= ?) AND (email_utilisateur_realise_par = ? OR email_utilisateur_revu_par = ? OR email_utilisateur_sign_off = ?);");
            $query->execute([$nowPlus5, $now, $_SESSION['admin_email'],$_SESSION['admin_email'],$_SESSION['admin_email']]);
            $notifs=$query->fetchAll();
        
            $query=$pdo->prepare("SELECT * FROM controle WHERE deadline >= ? AND (email_utilisateur_realise_par = ? OR email_utilisateur_revu_par = ? OR email_utilisateur_sign_off = ?);");
            $query->execute([$now,$_SESSION['admin_email'],$_SESSION['admin_email'],$_SESSION['admin_email']]);
            $notifsStatut=$query->fetchAll();
        
            
        
        ?>
        
        <?php
        }else{}
        //deconnexion
    break;
}
//include 'application/bdd_connection.php';

?>