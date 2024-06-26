<?php
include '../../includes/auth.php';

include '../../includes/db.php';
if (!isset($_SESSION["authentification"]) || !in_array($_SESSION['privilege'], ['admin'])) {
    $_SESSION['error'] = "Vous n'avez pas accès à cette section.";
    header("Location: ../dashboard.php"); // Redirection vers le tableau de bord
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM transfert_materiel WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<div class='alert success'>Transfert supprimé avec succès.</div>";
    } else {
        echo "<div class='alert error'>Erreur: " . $stmt->error . "</div>";
    }

    $stmt->close();
    $conn->close();
    header("Location: list.php");
    exit();
}
?>
