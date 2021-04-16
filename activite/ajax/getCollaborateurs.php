<?php
include '../application/bdd_connection.php';
// récupère la variable transmise par le client (script.js)
$mission_id = isset($_POST['mission_id']) ? (int)$_POST['mission_id'] : 0;
if (!empty($mission_id)) {
    $all_emails = array();
    $query = $pdo->prepare("SELECT email_utilisateur FROM equipe WHERE id_mission = ?");
    $query->execute(array($mission_id));
    $results = $query->fetchAll();
    foreach ($results as $result) {
        $all_emails[$result['email_utilisateur']] = 0;
    }

    $query = $pdo->prepare("SELECT email_utilisateur_realise_par, email_utilisateur_revu_par, email_utilisateur_sign_off FROM controle WHERE mission_id = ?");
    $query->execute(array($mission_id));
    $results = $query->fetchAll();
    $datas = array();
    foreach ($results as $result) {
        if (!isset($datas[$result['email_utilisateur_realise_par']])) {
            $datas[$result['email_utilisateur_realise_par']] = 1;
        } else {
            $datas[$result['email_utilisateur_realise_par']] += 1;
        }

        if (!isset($datas[$result['email_utilisateur_revu_par']])) {
            $datas[$result['email_utilisateur_revu_par']] = 1;
        } else {
            $datas[$result['email_utilisateur_revu_par']] += 1;
        }

        if (!isset($datas[$result['email_utilisateur_sign_off']])) {
            $datas[$result['email_utilisateur_sign_off']] = 1;
        } else {
            $datas[$result['email_utilisateur_sign_off']] += 1;
        }
    }

    $datas = array_merge($all_emails, $datas);
    // Obtenenir la valeur du tableau: l'e-mail et le nombre total de tâches ($emails et $values sont 2 tableaux pour le graphique)
    $emails = array_keys($datas);
    $values = array_values($datas);

    $labels = array();
    foreach ($emails as $email) {
        $query = $pdo->prepare("SELECT nom, prenom FROM utilisateur WHERE email = ?");
        $query->execute(array($email));
        $user = $query->fetch();
        $labels[] = $user['nom'] . ' ' . $user['prenom'];
    }

    // Créer une table 
    $table_html = '';
    $table_html .= '<div class="user-wrap">';
    $table_html .= '<table>';
    $table_html .= '<thead>';
    $table_html .= '<tr>';
    $table_html .= '<td>Responsable</td>';
    $table_html .= '<td>Nombre de taches</td>';
    $table_html .= '</tr>';
    $table_html .= '</thead>';
    $table_html .= '<tbody>';
    foreach ($labels as $key => $label) {
        $table_html .= '<tr>';
        $table_html .= '<td>' . $label . '</td>';
        $table_html .= '<td>' . $values[$key] . '</td>';
        $table_html .= '</tr>';
    }

    $table_html .= '</tbody>';
    $table_html .= '</table>';
    $table_html .= '</div>';
    // renvoie les données à traiter par javascript  (script.js)
    echo json_encode(array('status' => true, 'labels' => $labels, 'values' => $values, 'table_html' => $table_html));
    die;
} else {
    echo json_encode(array('status' => false, 'table_html' => ''));
    die;
}