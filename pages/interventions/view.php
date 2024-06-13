<?php
include '../../includes/auth.php';
requireLogin();
include '../../includes/header.php';
include '../../includes/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT interventions.*, materiel_topo.code AS materiel_code FROM interventions 
                            LEFT JOIN materiel_topo ON interventions.id_materiel_topo = materiel_topo.id 
                            WHERE interventions.id = $id");
    $intervention = $result->fetch_assoc();
}
?>

<div class="home-content">
    <div class="box">
        <div class="title">Détails de l'intervention</div>
        <table>
            <tr>
                <th>Type d'intervention</th>
                <td><?php echo $intervention['type_intervention']; ?></td>
            </tr>
            <tr>
                <th>Matériel</th>
                <td><?php echo $intervention['materiel_code']; ?></td>
            </tr>
            <tr>
                <th>Date d'intervention</th>
                <td><?php echo $intervention['date_intervention']; ?></td>
            </tr>
            <tr>
                <th>Intervenant</th>
                <td><?php echo $intervention['intervenant']; ?></td>
            </tr>
            <tr>
                <th>Sous-traitant</th>
                <td><?php echo $intervention['sous_traitant']; ?></td>
            </tr>
            <tr>
                <th>Nature de l'intervention</th>
                <td><?php echo $intervention['nature_intervention']; ?></td>
            </tr>
            <tr>
                <th>Référence</th>
                <td><?php echo $intervention['reference']; ?></td>
            </tr>
            <tr>
                <th>Tolérance</th>
                <td><?php echo $intervention['tolerance']; ?></td>
            </tr>
            <tr>
                <th>Durée de validité (en mois)</th>
                <td><?php echo $intervention['duree_validite']; ?></td>
            </tr>
            <tr>
                <th>Date de fin de validité</th>
                <td><?php echo $intervention['date_fin_validite']; ?></td>
            </tr>
            <tr>
                <th>Coût</th>
                <td><?php echo $intervention['cout']; ?></td>
            </tr>
            <tr>
                <th>Fiche</th>
                <td><?php echo $intervention['fiche']; ?></td>
            </tr>
            <tr>
                <th>Observation</th>
                <td><?php echo $intervention['observation']; ?></td>
            </tr>
        </table>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
