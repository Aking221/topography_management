<?php
include '../../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_famille = intval($_POST['id_famille']);

    // Get the abbreviation
    $query_abv = "SELECT abv FROM familles_topo WHERE id = $id_famille";
    $result_abv = mysqli_query($conn, $query_abv);
    $abv = mysqli_fetch_assoc($result_abv)['abv'];

    // Get the last code
    $query_last_code = "SELECT code FROM materiel_topo WHERE id_famille_topo = $id_famille ORDER BY id DESC LIMIT 1";
    $result_last_code = mysqli_query($conn, $query_last_code);
    $last_code = mysqli_num_rows($result_last_code) > 0 ? mysqli_fetch_assoc($result_last_code)['code'] : null;

    echo json_encode(['abv' => $abv, 'last_code' => $last_code]);
}
?>
