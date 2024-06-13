<?php
include '../includes/auth.php';
requireLogin();
include '../includes/header.php';
include '../includes/db.php';

// Récupération des statistiques
$sqlMateriel = "SELECT COUNT(*) as total FROM materiel_topo";
$resultMateriel = $conn->query($sqlMateriel);
$totalMateriel = $resultMateriel->fetch_assoc()['total'];

$sqlInterventions = "SELECT COUNT(*) as total FROM interventions";
$resultInterventions = $conn->query($sqlInterventions);
$totalInterventions = $resultInterventions->fetch_assoc()['total'];

$sqlTransferts = "SELECT COUNT(*) as total FROM transfert_materiel";
$resultTransferts = $conn->query($sqlTransferts);
$totalTransferts = $resultTransferts->fetch_assoc()['total'];

$sqlReformes = "SELECT COUNT(*) as total FROM reforme_materiel";
$resultReformes = $conn->query($sqlReformes);
$totalReformes = $resultReformes->fetch_assoc()['total'];

$conn->close();
?>

<div class="dashboard-content">
    <h1>Bienvenue sur le tableau de bord de la gestion de matériel topographique</h1>
    <p>Voici un aperçu des statistiques actuelles :</p>

    <!-- Section des statistiques -->
    <div class="stats">
        <div class="stat-item">
            <h2>Total des matériels</h2>
            <p><?php echo $totalMateriel; ?></p>
        </div>
        <div class="stat-item">
            <h2>Total des interventions</h2>
            <p><?php echo $totalInterventions; ?></p>
        </div>
        <div class="stat-item">
            <h2>Total des transferts</h2>
            <p><?php echo $totalTransferts; ?></p>
        </div>
        <div class="stat-item">
            <h2>Total des réformes</h2>
            <p><?php echo $totalReformes; ?></p>
        </div>
    </div>

    <!-- Section des actions rapides ou des liens importants -->
    <div class="quick-actions">
        <h2>Actions rapides</h2>
        <ul>
            <li><a href="../pages/materiel_topo/add.php">Ajouter un nouveau matériel</a></li>
            <li><a href="../pages/interventions/add.php">Ajouter une intervention</a></li>
            <li><a href="../pages/transferts/add.php">Ajouter un transfert</a></li>
            <li><a href="../pages/reformes/add.php">Ajouter une réforme</a></li>
            <li><a href="../pages/materiel_topo/list.php">Liste des matériels</a></li>
            <li><a href="../pages/interventions/list.php">Liste des interventions</a></li>
            <li><a href="../pages/transferts/list.php">Liste des transferts</a></li>
            <li><a href="../pages/reformes/list.php">Liste des réformes</a></li>
        </ul>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
