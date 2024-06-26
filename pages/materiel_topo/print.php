<?php
include '../../includes/db.php';
include '../../includes/auth.php';

if (!isset($_SESSION["authentification"]) || !in_array($_SESSION['privilege'], ['admin', 'utilisateur', 'invite'])) {
    $_SESSION['error'] = "Vous n'avez pas accès à cette section.";
    header("Location: ../dashboard.php");
    exit();
}

$id = $_GET['id'];
$sql = "SELECT m.*, f.fournisseur, c.chantier, u.nom_complet as creer_par 
        FROM materiel_topo m
        LEFT JOIN fournisseurs f ON m.id_fournisseur = f.id
        LEFT JOIN chantiers c ON m.id_chantier = c.id
        LEFT JOIN utilisateurs u ON m.creer_par = u.id
        WHERE m.id='$id'";
$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
$materiel = mysqli_fetch_assoc($result);

if (!$materiel) {
    echo "Matériel non trouvé.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiche de Matériel</title>
    <link rel="stylesheet" href="../../vendors/bootstrap/dist/css/bootstrap.min.css">
    <style>
        body {
            padding: 20px;
        }
        .header, .footer {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            width: 200px;
        }
        .header h1 {
            margin-top: 10px;
        }
        .table-info, .table-movements {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
            border: 1px solid #ddd;
        }
        .table-info th, .table-info td, .table-movements th, .table-movements td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .table-info th, .table-movements th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<div class="header">
    <img src="../../logo CSE.png" alt="Logo">
    <h1>Fiche de Matériel</h1>
</div>

<table class="table-info">
    <tr>
        <th>Code</th>
        <td><?php echo $materiel['code']; ?></td>
        <th>Date acquisition</th>
        <td><?php echo $materiel['date_acquisition']; ?></td>
    </tr>
    <tr>
        <th>Description</th>
        <td><?php echo $materiel['description']; ?></td>
        <th>Fournisseur</th>
        <td><?php echo $materiel['fournisseur']; ?></td>
    </tr>
    <tr>
        <th>Numéro de série</th>
        <td><?php echo $materiel['num_serie']; ?></td>
        <th>Numéro BC</th>
        <td><?php echo $materiel['num_bc']; ?></td>
    </tr>
    <tr>
        <th>Marque</th>
        <td><?php echo $materiel['marque']; ?></td>
        <th>Numéro BL</th>
        <td><?php echo isset($materiel['num_bl']) ? $materiel['num_bl'] : 'N/A'; ?></td>
    </tr>
    <tr>
        <th>Chantier</th>
        <td><?php echo $materiel['chantier']; ?></td>
        <th>État</th>
        <td><?php echo $materiel['etat']; ?></td>
    </tr>
    <tr>
        <th>Date de mise en service</th>
        <td><?php echo $materiel['date_mise_service']; ?></td>
        <th>Coût</th>
        <td><?php echo $materiel['cout_acquisition']; ?></td>
    </tr>
    <tr>
        <th>Date d'affectation</th>
        <td><?php echo $materiel['date_affectation']; ?></td>
        <th>Observations</th>
        <td><?php echo isset($materiel['observation']) ? $materiel['observation'] : 'N/A'; ?></td>
    </tr>
</table>

<div class="footer">
    <p>&copy; 2024 All Rights Reserved. Direction des Systèmes d'Information CSE</p>
</div>

<script>
    window.onload = function() {
        window.print();
        setTimeout(function(){
            window.history.back();
        }, 1000);
    }
</script>

</body>
</html>
