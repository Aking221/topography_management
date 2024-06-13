<?php
include '../../includes/auth.php';
requireLogin();
include '../../includes/header.php';
include '../../includes/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT materiel_topo.*, familles_topo.materiel AS famille, chantiers.chantier AS chantier, fournisseurs.fournisseur AS fournisseur FROM materiel_topo 
                            LEFT JOIN familles_topo ON materiel_topo.id_famille_topo = familles_topo.id 
                            LEFT JOIN chantiers ON materiel_topo.id_chantier = chantiers.id 
                            LEFT JOIN fournisseurs ON materiel_topo.id_fournisseur = fournisseurs.id 
                            WHERE materiel_topo.id = $id");
    $materiel = $result->fetch_assoc();
}
?>

<div class="home-content">
    <div class="box">
        <div class="title">Détails du matériel topographique</div>
        <table>
            <tr>
                <th>Code</th>
                <td><?php echo $materiel['code']; ?></td>
            </tr>
            <tr>
                <th>Description</th>
                <td><?php echo $materiel['description']; ?></td>
            </tr>
            <tr>
                <th>Famille</th>
                <td><?php echo $materiel['famille']; ?></td>
            </tr>
            <tr>
                <th>Marque</th>
                <td><?php echo $materiel['marque']; ?></td>
            </tr>
            <tr>
                <th>Numéro de série</th>
                <td><?php echo $materiel['num_serie']; ?></td>
            </tr>
            <tr>
                <th>Date d'acquisition</th>
                <td><?php echo $materiel['date_acquisition']; ?></td>
            </tr>
            <tr>
                <th>Coût d'acquisition</th>
                <td><?php echo $materiel['cout_acquisition']; ?></td>
            </tr>
            <tr>
                <th>Fournisseur</th>
                <td><?php echo $materiel['fournisseur']; ?></td>
            </tr>
            <tr>
                <th>Numéro BC</th>
                <td><?php echo $materiel['num_bc']; ?></td>
            </tr>
            <tr>
                <th>Fiche BL</th>
                <td><?php echo $materiel['fiche_bl']; ?></td>
            </tr>
            <tr>
                <th>Date de mise en service</th>
                <td><?php echo $materiel['date_mise_service']; ?></td>
            </tr>
            <tr>
                <th>État</th>
                <td><?php echo $materiel['etat']; ?></td>
            </tr>
            <tr>
                <th>Chantier</th>
                <td><?php echo $materiel['chantier']; ?></td>
            </tr>
            <tr>
                <th>Date d'affectation</th>
                <td><?php echo $materiel['date_affectation']; ?></td>
            </tr>
            <tr>
                <th>Créé par</th>
                <td><?php echo $materiel['creer_par']; ?></td>
            </tr>
            <tr>
                <th>Observation</th>
                <td><?php echo $materiel['observation']; ?></td>
            </tr>
        </table>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>

