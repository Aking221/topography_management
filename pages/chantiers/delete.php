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

$id = intval($_GET['id']);

// Prepare the statement to delete the record
$sql = $conn->prepare("DELETE FROM commandes WHERE id = ?");
$sql->bind_param("i", $id);

if ($sql->execute()) {
    $_SESSION['message'] = "Commande supprimée avec succès.";
} else {
    $_SESSION['error'] = "Erreur lors de la suppression de la commande: " . $conn->error;
}

$sql->close();
$conn->close();

header("Location: list.php");
exit();
?>
