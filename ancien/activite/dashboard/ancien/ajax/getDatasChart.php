<?php
include '../application/bdd_connection.php';
// lấy các biến truyền từ client (script.js)
$mission_id = isset($_POST['mission_id']) ? (int)$_POST['mission_id'] : 0;
$category_id = isset($_POST['category_id']) ? (int)$_POST['category_id'] : 0;
if (!empty($mission_id) && empty($category_id)) {
    $query = $pdo->prepare("SELECT c.*, c1.nom_categorie as nom_categorie FROM controle as c INNER JOIN categorie_general as c1 ON c.categorie_id = c1.id WHERE mission_id = ?");
    $query->execute(array($mission_id));
    $results = $query->fetchAll();
    $datas = array();
    $cats_array = array();
    $category_html = '';
    // Tạo controller table
    $table_html = '';
    foreach ($results as $result) {
        if (!in_array($result['categorie_id'], $cats_array)) {
            $cats_array[] = $result['categorie_id'];
            $category_html .= '<div class="category-item-wrap"  data-id="' . $result['categorie_id'] . '">';
            $category_html .= $result['nom_categorie'];
            $category_html .= '</div>';
        }

        $table_html .= '<div class="controller-item-wrap">';
        $table_html .= $result['nom_du_controle'];
        $table_html .= '<div class="controller-item-value-wrap">';
        $table_html .= '<table>';
        $table_html .= '<thead>';
        $table_html .= '<tr>';
        $table_html .= '<td>Statut</td>';
        $table_html .= '<td>Niveau_de_risque</td>';
        $table_html .= '<td>Design</td>';
        $table_html .= '<td>Efficacite</td>';
        $table_html .= '<td>Lu</td>';
        $table_html .= '<td>Li_statut</td>';
        $table_html .= '</tr>';
        $table_html .= '</thead>';

        $table_html .= '<tbody>';
        $table_html .= '<tr>';
        $table_html .= '<td>' . $result['statut'] . '</td>';
        $table_html .= '<td>' . $result['niveau_de_risque'] . '</td>';
        $table_html .= '<td>' . $result['design'] . '</td>';
        $table_html .= '<td>' . $result['efficacite'] . '</td>';
        $table_html .= '<td>' . $result['lu'] . '</td>';
        $table_html .= '<td>' . $result['lu_statut'] . '</td>';
        $table_html .= '</tr>';
        $table_html .= '</tbody>';

        $table_html .= '</table>';
        $table_html .= '</div>';
        $table_html .= '</div>';
        // lấy mảng giá trị status để vẽ biểu đồ
        if (!isset($datas[$result['statut']])) {
            $datas[$result['statut']] = 1;
        } else {
            $datas[$result['statut']] += 1;
        }
    }

    // lấy mảng giá trị status để vẽ biểu đồ
    $labels = array_keys($datas);
    $values = array_values($datas);

    // trả về dữ liệu để javascript xử lý (script.js)
    echo json_encode(array('status' => true, 'labels' => $labels, 'values' => $values, 'table_html' => $table_html, 'category_html' => $category_html));
    die;
} elseif (empty($mission_id) && !empty($category_id)) {
    $query = $pdo->prepare("SELECT statut FROM controle WHERE categorie_id = ?");
    $query->execute(array($category_id));
    $results = $query->fetchAll();
    $datas = array();
    // get table controler name html
    foreach ($results as $result) {
        // lấy mảng giá trị status để vẽ biểu đồ
        if (!isset($datas[$result['statut']])) {
            $datas[$result['statut']] = 1;
        } else {
            $datas[$result['statut']] += 1;
        }
    }

    // lấy mảng giá trị status để vẽ biểu đồ
    $labels = array_keys($datas);
    $values = array_values($datas);

    // trả về dữ liệu để javascript xử lý (script.js)
    echo json_encode(array('status' => true, 'labels' => $labels, 'values' => $values));
    die;
} elseif (!empty($mission_id) && !empty($category_id)) {
    $query = $pdo->prepare("SELECT * FROM controle WHERE mission_id = ? AND categorie_id = ?");
    $query->execute(array($mission_id, $category_id));
    $results = $query->fetchAll();
    $datas = array();
    // get table controler name html
    $table_html = '';
    foreach ($results as $result) {
        $table_html .= '<div class="controller-item-wrap">';
        $table_html .= $result['nom_du_controle'];
        $table_html .= '<div class="controller-item-value-wrap">';
        $table_html .= '<table>';
        $table_html .= '<thead>';
        $table_html .= '<tr>';
        $table_html .= '<td>Statut</td>';
        $table_html .= '<td>Niveau_de_risque</td>';
        $table_html .= '<td>Design</td>';
        $table_html .= '<td>Efficacite</td>';
        $table_html .= '<td>Lu</td>';
        $table_html .= '<td>Li_statut</td>';
        $table_html .= '</tr>';
        $table_html .= '</thead>';

        $table_html .= '<tbody>';
        $table_html .= '<tr>';
        $table_html .= '<td>' . $result['statut'] . '</td>';
        $table_html .= '<td>' . $result['niveau_de_risque'] . '</td>';
        $table_html .= '<td>' . $result['design'] . '</td>';
        $table_html .= '<td>' . $result['efficacite'] . '</td>';
        $table_html .= '<td>' . $result['lu'] . '</td>';
        $table_html .= '<td>' . $result['lu_statut'] . '</td>';
        $table_html .= '</tr>';
        $table_html .= '</tbody>';

        $table_html .= '</table>';
        $table_html .= '</div>';
        $table_html .= '</div>';
        // lấy mảng giá trị status để vẽ biểu đồ
        if (!isset($datas[$result['statut']])) {
            $datas[$result['statut']] = 1;
        } else {
            $datas[$result['statut']] += 1;
        }
    }

    // lấy mảng giá trị status để vẽ biểu đồ
    $labels = array_keys($datas);
    $values = array_values($datas);
    // trả về dữ liệu để javascript xử lý (script.js)
    echo json_encode(array('status' => true, 'labels' => $labels, 'values' => $values, 'table_html' => $table_html));
    die;
} else {
    echo json_encode(array('status' => false));
    die;
}