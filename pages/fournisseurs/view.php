<?php
include '../../includes/auth.php';
requireLogin();
include '../../includes/header.php';
include '../../includes/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM fournisseurs WHERE id = $id");
    $fournisseur = $result->fetch_assoc();
}
?>

<div class="home-content">
    <div class="box">
        <div class="title">Détails du fournisseur</div>
        <table>
            <tr>
                <th>Nom</th>
                <td><?php echo $fournisseur['fournisseur']; ?></td>
            </tr>
            <tr>
                <th>Code</th>
                <td><?php echo $fournisseur['code']; ?></td>
            </tr>
            <tr>
                <th>Contact</th>
                <td><?php echo $fournisseur['contact']; ?></td>
            </tr>
            <tr>
                <th>Actif</th>
                <td><?php echo ($fournisseur['active']) ? "Oui" : "Non"; ?></td>
            </tr>
            <tr>
                <th>Créé par</th>
                <td><?php echo $fournisseur['creer_par']; ?></td>
            </tr>
            <tr>
                <th>Observation</th>
                <td><?php echo $fournisseur['observation']; ?></td>
            </tr>
        </table>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>

