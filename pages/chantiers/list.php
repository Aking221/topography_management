<?php
include '../../includes/auth.php';
requireLogin();
include '../../includes/header.php';
include '../../includes/db.php';

// Récupérer tous les chantiers
$query = "SELECT c.id, c.code, c.chantier, p.pays, c.contact, c.active, c.creer_par, c.observation 
          FROM chantiers c 
          LEFT JOIN pays p ON c.id_pays = p.id";
$result = $conn->query($query);

if ($result === false) {
    die("Erreur lors de la récupération des données : " . $conn->error);
}

?>

<h2>Liste des chantiers</h2>

<table border="1">
    <tr>
        <th>Code</th>
        <th>Nom du Chantier</th>
        <th>Pays</th>
        <th>Contact</th>
        <th>Actif</th>
        <th>Créé par</th>
        <th>Observation</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row['code']); ?></td>
            <td><?php echo htmlspecialchars($row['chantier']); ?></td>
            <td><?php echo htmlspecialchars($row['pays']); ?></td>
            <td><?php echo htmlspecialchars($row['contact']); ?></td>
            <td><?php echo ($row['active'] == 1) ? 'Oui' : 'Non'; ?></td>
            <td><?php echo htmlspecialchars($row['creer_par']); ?></td>
            <td><?php echo htmlspecialchars($row['observation']); ?></td>
            <td>
                <a href="view.php?id=<?php echo $row['id']; ?>">Voir</a> |
                <a href="edit.php?id=<?php echo $row['id']; ?>">Modifier</a> |
                <a href="delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce chantier ?');">Supprimer</a>
            </td>
        </tr>
    <?php } ?>
</table>

<?php
include '../../includes/footer.php';
?>
