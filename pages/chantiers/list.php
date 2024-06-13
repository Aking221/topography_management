<?php
include '../../includes/auth.php';
requireLogin();
include '../../includes/header.php';
include '../../includes/db.php';

$result = $conn->query("SELECT chantiers.*, pays.pays FROM chantiers LEFT JOIN pays ON chantiers.id_pays = pays.id");
?>

<div class="home-content">
    <div class="box">
        <div class="title">Liste des chantiers</div>
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Chantier</th>
                    <th>Pays</th>
                    <th>Contact</th>
                    <th>Actif</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['code']; ?></td>
                    <td><?php echo $row['chantier']; ?></td>
                    <td><?php echo $row['pays']; ?></td>
                    <td><?php echo $row['contact']; ?></td>
                    <td><?php echo ($row['active']) ? "Oui" : "Non"; ?></td>
                    <td>
                        <a href="view.php?id=<?php echo $row['id']; ?>">Voir</a>
                        <a href="edit.php?id=<?php echo $row['id']; ?>">Modifier</a>
                        <a href="delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce chantier?');">Supprimer</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
