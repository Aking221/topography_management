<?php
include '../../includes/auth.php';
requireLogin();
include '../../includes/header.php';
include '../../includes/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM pays WHERE id = $id");
    $pays = $result->fetch_assoc();
}
?>

<div class="home-content">
    <div class="box">
        <div class="title">Détails du pays</div>
        <table>
            <tr>
                <th>Nom</th>
                <td><?php echo $pays['pays']; ?></td>
            </tr>
            <tr>
                <th>Créé par</th>
                <td><?php echo $pays['creer_par']; ?></td>
            </tr>
        </table>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>

