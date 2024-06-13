<?php
include '../../includes/auth.php';
requireLogin();
include '../../includes/header.php';
include '../../includes/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT chantiers.*, pays.pays FROM chantiers LEFT JOIN pays ON chantiers.id_pays = pays.id WHERE chantiers.id = $id");
    $chantier = $result->fetch_assoc();
}
?>

<div class="home-content">
    <div class="box">
        <div class="title">Détails du chantier</div>
        <table>
            <tr>
                <th>Code</th>
                <td><?php echo $chantier['code']; ?></td>
            </tr>
            <tr>
                <th>Chantier</th>
                <td><?php echo $chantier['chantier']; ?></td>
            </tr>
            <tr>
                <th>Pays</th>
                <td><?php echo $chantier['pays']; ?></td>
            </tr>
            <tr>
                <th>Contact</th>
                <td><?php echo $chantier['contact']; ?></td>
            </tr>
            <tr>
                <th>Actif</th>
                <td><?php echo ($chantier['active']) ? "Oui" : "Non"; ?></td>
            </tr>
            <tr>
                <th>Créé par</th>
                <td><?php echo $chantier['creer_par']; ?></td>
            </tr>
            <tr>
                <th>Observation</th>
                <td><?php echo $chantier['observation']; ?></td>
            </tr>
        </table>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
