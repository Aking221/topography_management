<?php
include '../../includes/db.php';
include '../../includes/auth.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["authentification"]) || $_SESSION['privilege'] !== 'admin') {
    $_SESSION['error'] = "Vous n'avez pas accès à cette section.";
    header("Location: ../dashboard.php"); // Redirection vers le tableau de bord
    exit();
}

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Intervenant non spécifié.";
    header("Location: list_intervenants.php");
    exit();
}

$id = $_GET['id'];
$sql = "DELETE FROM intervenants WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    $_SESSION['message'] = "Intervenant supprimé avec succès.";
} else {
    $_SESSION['error'] = "Erreur lors de la suppression de l'intervenant: " . $conn->error;
}

$conn->close();
header("Location: list_intervenants.php");
exit();
?>
