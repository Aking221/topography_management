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
    $sql = "DELETE FROM pays WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header('Location: view.php');
exit();
?>
