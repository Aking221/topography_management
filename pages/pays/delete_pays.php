<?php
include '../../includes/db.php';
include '../../includes/auth.php';


if (!isset($_SESSION["authentification"]) || !in_array($_SESSION['privilege'], ['admin'])) {
    $_SESSION['error'] = "Vous n'avez pas accès à cette section.";
    header("Location: ../dashboard.php"); // Redirection vers le tableau de bord
    exit();
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // Check if any chantiers reference this pays
    $checkSql = "SELECT COUNT(*) as count FROM chantiers WHERE id_pays = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("i", $id);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['count'] == 0) {
        // No references found, safe to delete
        $sql = "DELETE FROM pays WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
    } else {
        // References found, handle the error
        $_SESSION['error'] = "Impossible de supprimer le pays car il est référencé par des chantiers.";
        header('Location: view.php');
        exit();
    }
}

header('Location: view.php');
exit();
?>
