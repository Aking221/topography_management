<?php
include '../../includes/db.php';
include '../../includes/auth.php';

requireLogin();
if (!isset($_SESSION["authentification"]) || !in_array($_SESSION['privilege'], ['admin'])) {
    $_SESSION['error'] = "Vous n'avez pas accès à cette section.";
    header("Location: ../dashboard.php"); // Redirection vers le tableau de bord
    exit();
}


if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Aucun ID spécifié.";
    header("Location: list.php");
    exit();
}

$id = $_GET['id'];
$sql = "DELETE FROM fournisseurs WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    $_SESSION['message'] = "Fournisseur supprimé avec succès.";
} else {
    $_SESSION['error'] = "Erreur: " . $conn->error;
}

$conn->close();
header("Location: list.php");
exit();
?>
