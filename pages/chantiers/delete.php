<?php
include '../../includes/auth.php';
requireLogin();
include '../../includes/db.php';

// Vérifier si l'ID du chantier est passé en paramètre
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Préparer la requête SQL pour supprimer le chantier
    $stmt = $conn->prepare("DELETE FROM chantiers WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<div class='alert success'>Chantier supprimé avec succès.</div>";
    } else {
        echo "<div class='alert error'>Erreur: " . $stmt->error . "</div>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<div class='alert error'>ID de chantier non spécifié.</div>";
}

// Rediriger vers la liste des chantiers après la suppression
header("Location: list.php");
exit();
?>
