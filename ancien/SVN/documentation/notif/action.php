<?php 
$todo = '';

if(isset($_POST['todo'])){
    $todo = $_POST['todo'];
}

switch ($todo) {
    case 'fetch':
        include '../application/bdd_connection.php';
        if(isset($_SESSION['admin_email']) && $_SESSION['admin_email'] !=''){
            
            
            $now = date('Y-m-d');
            $nowPlus5 = strtotime($now."+ 5 days");
            $nowPlus4 = strtotime($now."+ 4 days");
            $nowPlus3 = strtotime($now."+ 3 days");
            $nowPlus2 = strtotime($now."+ 2 days");
            $nowPlus1 = strtotime($now."+ 1 days");

            $nowPlus5 = date("Y-m-d",$nowPlus5);
            $nowPlus4 = date("Y-m-d",$nowPlus4);
            $nowPlus3 = date("Y-m-d",$nowPlus3);
            $nowPlus2 = date("Y-m-d",$nowPlus2);
            $nowPlus1 = date("Y-m-d",$nowPlus1);



            $query=$pdo->prepare("SELECT * FROM controle WHERE ((deadline <= ? AND deadline >= ?) OR (deadline <= ? AND deadline >= ?) OR (deadline <= ? AND deadline >= ?) OR (deadline <= ? AND deadline >= ?) OR (deadline <= ? AND deadline >= ?)) AND (email_utilisateur_realise_par = ? OR email_utilisateur_revu_par = ? OR email_utilisateur_sign_off = ?);");
            $query->execute([$nowPlus5, $now,$nowPlus4, $now, $nowPlus3, $now, $nowPlus2, $now, $nowPlus1, $now, $_SESSION['admin_email'],$_SESSION['admin_email'],$_SESSION['admin_email']]);
            $notifs=$query->fetchAll();
        
            $query=$pdo->prepare("SELECT * FROM controle WHERE (email_utilisateur_realise_par = ? OR email_utilisateur_revu_par = ? OR email_utilisateur_sign_off = ?);");
            $query->execute([$_SESSION['admin_email'],$_SESSION['admin_email'],$_SESSION['admin_email']]);
            $notifsStatut=$query->fetchAll();

            $html = '';
        
            foreach ($notifsStatut as $notifStatut) {
                $classNotifs = '';
                if($notifStatut['lu_statut']){
                  $classNotifs = 'notif-read';
                } 

                $html .= '<li class="'.$classNotifs.'"><a class="dropdown-item" href="#"><small><i><i><br>Le statut à changé pour '.$notifStatut["statut"].' pour : '.$notifStatut["nom_du_controle"].'</small></a></li>';

                $query=$pdo->prepare("UPDATE controle set lu_statut = ? where id= ?");
                $query->execute([1, $notifStatut['id']]);
            }
            foreach ($notifs as $notif) {
    
                $classNotifs = '';
                if($notif['lu']){
                    $classNotifs = 'notif-read';
                } 

                $html .= '<li class="'.$classNotifs.'"><a class="dropdown-item" href="#"><small ><i>'.$notif["deadline"].', <i><br>Attention la deadline pour : '.$notif["nom_du_controle"].'</small></a></li>';

                $query=$pdo->prepare("UPDATE controle set lu = ? where id= ?");
                $query->execute([1, $notif['id']]);
            }
            
            echo $html;
            exit();

    
        }else{}
        //deconnexion
    break;
    
    default:
        if(isset($_SESSION['admin_email']) && $_SESSION['admin_email'] !=''){

            $now = date('Y-m-d');
            $nowPlus5 = strtotime($now."+ 5 days");
            $nowPlus4 = strtotime($now."+ 4 days");
            $nowPlus3 = strtotime($now."+ 3 days");
            $nowPlus2 = strtotime($now."+ 2 days");
            $nowPlus1 = strtotime($now."+ 1 days");

            $nowPlus5 = date("Y-m-d",$nowPlus5);
            $nowPlus4 = date("Y-m-d",$nowPlus4);
            $nowPlus3 = date("Y-m-d",$nowPlus3);
            $nowPlus2 = date("Y-m-d",$nowPlus2);
            $nowPlus1 = date("Y-m-d",$nowPlus1);

            $query=$pdo->prepare("SELECT * FROM controle WHERE ((deadline <= ? AND deadline >= ?) OR (deadline <= ? AND deadline >= ?) OR (deadline <= ? AND deadline >= ?) OR (deadline <= ? AND deadline >= ?) OR (deadline <= ? AND deadline >= ?)) AND (email_utilisateur_realise_par = ? OR email_utilisateur_revu_par = ? OR email_utilisateur_sign_off = ?);");
            $query->execute([$nowPlus5, $now,$nowPlus4, $now, $nowPlus3, $now, $nowPlus2, $now, $nowPlus1, $now, $_SESSION['admin_email'],$_SESSION['admin_email'],$_SESSION['admin_email']]);
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