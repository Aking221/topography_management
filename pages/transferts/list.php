<?php
include '../../includes/auth.php';
requireLogin();
include '../../includes/header.php';
include '../../includes/db.php';

$result = $conn->query("SELECT transfert_materiel.*, materiel_topo.code AS materiel_code, ch_prov.chantier AS chantier_provenance, ch_dest.chantier AS chantier_destination, utilisateurs.nom_complet AS receptionneur FROM transfert_materiel 
                        LEFT JOIN materiel_topo ON transfert_materiel.id_materiel_topo = materiel_topo.id
                        LEFT JOIN chantiers AS ch_prov ON transfert_materiel.id_provenance = ch_prov.id
                        LEFT JOIN chantiers AS ch_dest ON transfert_materiel.id_destination = ch_dest.id
                        LEFT JOIN utilisateurs ON transfert_materiel.receptionner = utilisateurs.id");
?>

<div class="home-content">
    <div class="box">
        <div class="title">Liste des transferts</div>
        <table>
            <thead>
                <tr>
                    <th>Matériel</th>
                    <th>Date de transfert</th>
                    <th>Provenance</th>
                    <th>Destination</th>
                    <th>Réceptionné par</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['materiel_code']; ?></td>
                    <td><?php echo $row['date_transfert']; ?></td>
                    <td><?php echo $row['chantier_provenance']; ?></td>
                    <td><?php echo $row['chantier_destination']; ?></td>
                    <td><?php echo $row['receptionneur']; ?></td>
                    <td>
                        <a href="view.php?id=<?php echo $row['id']; ?>">Voir</a>
                        <a href="edit.php?id=<?php echo $row['id']; ?>">Modifier</a>
                        <a href="delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce transfert?');">Supprimer</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>

