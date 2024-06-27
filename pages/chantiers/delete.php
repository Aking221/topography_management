<?php
include '../../includes/db.php';
include '../../includes/auth.php';

if (!isset($_SESSION["authentification"]) || $_SESSION['privilege'] !== 'admin') {
    $_SESSION['error'] = "Vous n'avez pas accès à cette section.";
    header("Location: ../dashboard.php");
    exit();
}

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "ID de commande manquant.";
    header("Location: list.php");
    exit();
}

$id = $_GET['id'];

$sql = "DELETE FROM commandes WHERE id = $id";
if ($conn->query($sql) === TRUE) {
    $_SESSION['message'] = "Commande supprimée avec succès.";
} else {
    $_SESSION['error'] = "Erreur lors de la suppression de la commande: " . $conn->error;
}

header("Location: list.php");
exit();
?>
