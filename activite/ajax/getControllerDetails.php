<?php
include '../application/bdd_connection.php';
// récupère la variable transmise par le client (script.js)
$missions_id = isset($_POST['missions_id']) ? explode(',', $_POST['missions_id']) : array();
$whereIn = implode('","', $missions_id);
$value = isset($_POST['value']) ? $_POST['value'] : '';
if (!empty($missions_id) && $value !== '') {
    $query = $pdo->prepare('SELECT c.*, m.mission_nom FROM controle as c INNER JOIN mission as m ON c.mission_id = m.mission_id WHERE m.mission_id IN ("'.$whereIn.'") AND c.nom_du_controle LIKE ?');
    $query->execute(array('%' . $value . '%'));
    $results = $query->fetchAll();

    // créer une table lors de la recherche par controller name
    $table_html = '';
    $table_html .= '<div class="controller-wrap">';
    $table_html .= '<table>';
    $table_html .= '<thead>';
    $table_html .= '<tr>';
    $table_html .= '<td>Nom controle</td>';
    $table_html .= '<td>Nom mission</td>';
    $table_html .= '<td>Deadline</td>';
    $table_html .= '<td>Statut</td>';
    $table_html .= '<td>Niveau_de_risque</td>';
    $table_html .= '<td>Design</td>';
    $table_html .= '<td>Efficacite</td>';
    $table_html .= '<td>Lu</td>';
    $table_html .= '<td>Lu_statut</td>';
    $table_html .= '</tr>';
    $table_html .= '</thead>';
    $table_html .= '<tbody>';
    foreach ($results as $result) {
        $table_html .= '<tr>';
        $table_html .= '<td>' . $result['nom_du_controle'] . '</td>';
        $table_html .= '<td>' . $result['mission_nom'] . '</td>';
        $table_html .= '<td>' . $result['deadline'] . '</td>';
        $table_html .= '<td>' . $result['statut'] . '</td>';
        $table_html .= '<td>' . $result['niveau_de_risque'] . '</td>';
        $table_html .= '<td>' . $result['design'] . '</td>';
        $table_html .= '<td>' . $result['efficacite'] . '</td>';
        $table_html .= '<td>' . $result['lu'] . '</td>';
        $table_html .= '<td>' . $result['lu_statut'] . '</td>';
        $table_html .= '</tr>';
    }

    $table_html .= '</tbody>';
    $table_html .= '</table>';
    $table_html .= '</div>';

    // renvoie les données à traiter par javascript (script.js)
    echo json_encode(array('status' => true, 'table_html' => $table_html));
    die;
} else {
    echo json_encode(array('status' => false, 'table_html' => ''));
    die;
}