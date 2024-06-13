<?php
include '../../includes/auth.php';
requireLogin();
include '../../includes/header.php';
include '../../includes/db.php';

$result = $conn->query("SELECT materiel_topo.*, familles_topo.materiel AS famille, chantiers.chantier AS chantier, fournisseurs.fournisseur AS fournisseur FROM materiel_topo 
                        LEFT JOIN familles_topo ON materiel_topo.id_famille_topo = familles_topo.id 
                        LEFT JOIN chantiers ON materiel_topo.id_chantier = chantiers.id 
                        LEFT JOIN fournisseurs ON materiel_topo.id_fournisseur = fournisseurs.id");
?>

<div class="home-content">
    <div class="box">
        <div class="title">Liste des matériels topographiques</div>
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Description</th>
                    <th>Famille</th>
                    <th>Marque</th>
                    <th>Numéro de série</th>
                    <th>Fournisseur</th>
                    <th>Chantier</th>
                    <th>État</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['code']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td><?php echo $row['famille']; ?></td>
                    <td><?php echo $row['marque']; ?></td>
                    <td><?php echo $row['num_serie']; ?></td>
                    <td><?php echo $row['fournisseur']; ?></td>
                    <td><?php echo $row['chantier']; ?></td>
                    <td><?php echo $row['etat']; ?></td>
                    <td>
                        <a href="view.php?id=<?php echo $row['id']; ?>">Voir</a>
                        <a href="edit.php?id=<?php echo $row['id']; ?>">Modifier</a>
                        <a href="delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce matériel?');">Supprimer</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>

