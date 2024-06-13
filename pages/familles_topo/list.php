<?php
include '../../includes/auth.php';
requireLogin();
include '../../includes/header.php';
include '../../includes/db.php';

$result = $conn->query("SELECT * FROM familles_topo");
?>

<div class="home-content">
    <div class="box">
        <div class="title">Liste des familles de matériels topographiques</div>
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Abréviation</th>
                    <th>Actif</th>
                    <th>Observation</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['materiel']; ?></td>
                    <td><?php echo $row['abv']; ?></td>
                    <td><?php echo ($row['active']) ? "Oui" : "Non"; ?></td>
                    <td><?php echo $row['observation']; ?></td>
                    <td>
                        <a href="view.php?id=<?php echo $row['id']; ?>">Voir</a>
                        <a href="edit.php?id=<?php echo $row['id']; ?>">Modifier</a>
                        <a href="delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette famille?');">Supprimer</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>

