<?php
include '../../includes/auth.php';
requireLogin();
include '../../includes/header.php';
include '../../includes/db.php';

$result = $conn->query("SELECT interventions.*, materiel_topo.code AS materiel_code FROM interventions 
                        LEFT JOIN materiel_topo ON interventions.id_materiel_topo = materiel_topo.id");
?>

<div class="home-content">
    <div class="box">
        <div class="title">Liste des interventions</div>
        <table>
            <thead>
                <tr>
                    <th>Type d'intervention</th>
                    <th>Matériel</th>
                    <th>Date d'intervention</th>
                    <th>Intervenant</th>
                    <th>Nature de l'intervention</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['type_intervention']; ?></td>
                    <td><?php echo $row['materiel_code']; ?></td>
                    <td><?php echo $row['date_intervention']; ?></td>
                    <td><?php echo $row['intervenant']; ?></td>
                    <td><?php echo $row['nature_intervention']; ?></td>
                    <td>
                        <a href="view.php?id=<?php echo $row['id']; ?>">Voir</a>
                        <a href="edit.php?id=<?php echo $row['id']; ?>">Modifier</a>
                        <a href="delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette intervention?');">Supprimer</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
