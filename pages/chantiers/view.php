<?php
include '../../includes/auth.php';
requireLogin();
include '../../includes/header.php';
include '../../includes/db.php';

// Vérifier si l'ID du chantier est passé en paramètre
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Récupérer les détails du chantier
    $stmt = $conn->prepare("SELECT c.code, c.chantier, p.pays, c.contact, c.active, c.creer_par, c.observation 
                            FROM chantiers c 
                            LEFT JOIN pays p ON c.id_pays = p.id 
                            WHERE c.id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Vérifier si des résultats ont été trouvés
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "<div class='alert error'>Chantier non trouvé.</div>";
        exit();
    }

    // Fermer la requête
    $stmt->close();
    // Fermer la connexion à la base de données
    $conn->close();
} else {
    echo "<div class='alert error'>ID de chantier non spécifié.</div>";
    exit();
}
?>

<div class="home-content">
    <div class="box">
        <div class="title">Détails du chantier</div>
        <table border="1">
            <tr>
                <th>Code</th>
                <td><?php echo htmlspecialchars($row['code']); ?></td>
            </tr>
            <tr>
                <th>Nom du Chantier</th>
                <td><?php echo htmlspecialchars($row['chantier']); ?></td>
            </tr>
            <tr>
                <th>Pays</th>
                <td><?php echo htmlspecialchars($row['pays']); ?></td>
            </tr>
            <tr>
                <th>Contact</th>
                <td><?php echo htmlspecialchars($row['contact']); ?></td>
            </tr>
            <tr>
                <th>Actif</th>
                <td><?php echo ($row['active'] == 1) ? 'Oui' : 'Non'; ?></td>
            </tr>
            <tr>
                <th>Créé par</th>
                <td><?php echo htmlspecialchars($row['creer_par']); ?></td>
            </tr>
            <tr>
                <th>Observation</th>
                <td><?php echo htmlspecialchars($row['observation']); ?></td>
            </tr>
        </table>
        <br>
        <a href="list.php" class="btn">Retour à la liste des chantiers</a>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
