<?php
include '../../includes/auth.php';
requireLogin();
include '../../includes/db.php';

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
