<?php
include '../../includes/auth.php';

include '../../includes/header.php';
include '../../includes/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT transfert_materiel.*, materiel_topo.code AS materiel_code, ch_prov.chantier AS chantier_provenance, ch_dest.chantier AS chantier_destination, utilisateurs.nom_complet AS receptionneur FROM transfert_materiel 
                            LEFT JOIN materiel_topo ON transfert_materiel.id_materiel_topo = materiel_topo.id
                            LEFT JOIN chantiers AS ch_prov ON transfert_materiel.id_provenance = ch_prov.id
                            LEFT JOIN chantiers AS ch_dest ON transfert_materiel.id_destination = ch_dest.id
                            LEFT JOIN utilisateurs ON transfert_materiel.receptionner = utilisateurs.id 
                            WHERE transfert_materiel.id = $id");
    $transfert = $result->fetch_assoc();
}
?>

<div class="home-content">
    <div class="box">
        <div class="title">Détails du transfert</div>
        <table>
            <tr>
                <th>Matériel</th>
                <td><?php echo $transfert['materiel_code']; ?></td>
            </tr>
            <tr>
                <th>Date de transfert</th>
                <td><?php echo $transfert['date_transfert']; ?></td>
            </tr>
            <tr>
                <th>Provenance</th>
                <td><?php echo $transfert['chantier_provenance']; ?></td>
            </tr>
            <tr>
                <th>Destination</th>
                <td><?php echo $transfert['chantier_destination']; ?></td>
            </tr>
            <tr>
                <th>Numéro BT</th>
                <td><?php echo $transfert['num_bt']; ?></td>
            </tr>
            <tr>
                <th>Bon de transfert</th>
                <td><?php echo $transfert['bon_transfert']; ?></td>
            </tr>
            <tr>
                <th>Réceptionné par</th>
                <td><?php echo $transfert['receptionneur']; ?></td>
            </tr>
            <tr>
                <th>Date de réception</th>
                <td><?php echo $transfert['date_reception']; ?></td>
            </tr>
            <tr>
                <th>Coût</th>
                <td><?php echo $transfert['cout']; ?></td>
            </tr>
            <tr>
                <th>Observation</th>
                <td><?php echo $transfert['observation']; ?></td>
            </tr>
            <tr>
                <th>Créé par</th>
                <td><?php echo $transfert['creer_par']; ?></td>
            </tr>
        </table>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>

