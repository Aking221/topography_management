<?php
include '../../includes/db.php';
include '../../includes/auth.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start(); 
}

if (!isset($_SESSION["authentification"]) || $_SESSION['privilege'] !== 'admin') {
    $_SESSION['error'] = "Vous n'avez pas accès à cette section.";
    header("Location: ../dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    $sql = "UPDATE transfert_materiel SET receptionner = 1, date_reception = NOW() WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $sqlUpdate = "UPDATE materiel_topo SET id_chantier = (SELECT id_destination FROM transfert_materiel WHERE id = ?) WHERE id = (SELECT id_materiel_topo FROM transfert_materiel WHERE id = ?)";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param("ii", $id, $id);
        $stmtUpdate->execute();

        $_SESSION['message'] = "Réception validée avec succès.";
    } else {
        $_SESSION['error'] = "Erreur lors de la validation de la réception.";
    }

    header("Location: list.php");
    exit();
}
?>
