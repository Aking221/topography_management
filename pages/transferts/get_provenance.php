<?php
include '../../includes/db.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $sql = "SELECT c.id as id_chantier, c.chantier as provenance 
            FROM materiel_topo m 
            LEFT JOIN chantiers c ON m.id_chantier = c.id 
            WHERE m.id = '$id'";
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    $data = mysqli_fetch_assoc($result);
    echo json_encode($data);
}
?>
