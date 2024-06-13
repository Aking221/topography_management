<?php
include '../../includes/auth.php';
requireLogin();
include '../../includes/header.php';
include '../../includes/db.php';

$result = $conn->query("SELECT reforme_materiel.*, materiel_topo.code AS materiel_code FROM reforme_materiel 
                        LEFT JOIN materiel_topo ON reforme_materiel.id_materiel_topo = materiel_topo.id");
?>

<div class="home-content">
    <div class="box">
        <div class="title">Liste des réformes</div>
        <table>
            <thead>
                <tr>
                    <th>Matériel</th>
                    <th>Date de réforme</th>
                    <th>Raison</th>
                    <th>Destination</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['materiel_code']; ?></td>
                    <td><?php echo $row['date_reforme']; ?></td>
                    <td><?php echo $row['raison']; ?></td>
                    <td><?php echo $row['id_destination']; ?></td>
                    <td>
                        <a href="view.php?id=<?php echo $row['id']; ?>">Voir</a>
                        <a href="edit.php?id=<?php echo $row['id']; ?>">Modifier</a>
                        <a href="delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réforme?');">Supprimer</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
