<?php
include '../../includes/auth.php';
requireLogin();
include '../../includes/header.php';
include '../../includes/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT reforme_materiel.*, materiel_topo.code AS materiel_code FROM reforme_materiel 
                            LEFT JOIN materiel_topo ON reforme_materiel.id_materiel_topo = materiel_topo.id 
                            WHERE reforme_materiel.id = $id");
    $reforme = $result->fetch_assoc();
}
?>

<div class="home-content">
    <div class="box">
        <div class="title">Détails de la réforme</div>
        <table>
            <tr>
                <th>Matériel</th>
                <td><?php echo $reforme['materiel_code']; ?></td>
            </tr>
            <tr>
                <th>Date de réforme</th>
                <td><?php echo $reforme['date_reforme']; ?></td>
            </tr>
            <tr>
                <th>Raison</th>
                <td><?php echo $reforme['raison']; ?></td>
            </tr>
            <tr>
                <th>Destination</th>
                <td><?php echo $reforme['id_destination']; ?></td>
            </tr>
            <tr>
                <th>Observation</th>
                <td><?php echo $reforme['observation']; ?></td>
            </tr>
            <tr>
                <th>Créé par</th>
                <td><?php echo $reforme['creer_par']; ?></td>
            </tr>
        </table>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
