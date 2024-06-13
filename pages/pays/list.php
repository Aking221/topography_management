<?php
include '../../includes/auth.php';
requireLogin();
include '../../includes/header.php';
include '../../includes/db.php';

$result = $conn->query("SELECT * FROM pays");
?>

<div class="home-content">
    <div class="box">
        <div class="title">Liste des pays</div>
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Créé par</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['pays']; ?></td>
                    <td><?php echo $row['creer_par']; ?></td>
                    <td>
                        <a href="view.php?id=<?php echo $row['id']; ?>">Voir</a>
                        <a href="edit.php?id=<?php echo $row['id']; ?>">Modifier</a>
                        <a href="delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce pays?');">Supprimer</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>

