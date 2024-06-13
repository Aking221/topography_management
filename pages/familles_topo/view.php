<?php
include '../../includes/auth.php';
requireLogin();
include '../../includes/header.php';
include '../../includes/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM familles_topo WHERE id = $id");
    $famille = $result->fetch_assoc();
}
?>

<div class="home-content">
    <div class="box">
        <div class="title">Détails de la famille de matériel topographique</div>
        <table>
            <tr>
                <th>Nom</th>
                <td><?php echo $famille['materiel']; ?></td>
            </tr>
            <tr>
                <th>Abréviation</th>
                <td><?php echo $famille['abv']; ?></td>
            </tr>
            <tr>
                <th>Actif</th>
                <td><?php echo ($famille['active']) ? "Oui" : "Non"; ?></td>
            </tr>
            <tr>
                <th>Observation</th>
                <td><?php echo $famille['observation']; ?></td>
            </tr>
        </table>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>

