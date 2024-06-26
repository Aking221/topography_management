<?php
include '../../includes/db.php';
include '../../includes/auth.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start(); 
}

if (!isset($_SESSION["authentification"]) || !in_array($_SESSION['privilege'], ['admin'])) {
    $_SESSION['error'] = "Vous n'avez pas accès à cette section.";
    header("Location: ../dashboard.php"); // Redirection vers le tableau de bord
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete dependent records in transfert_materiel table
    $sql_transfert = "DELETE FROM transfert_materiel WHERE id_materiel_topo = $id";
    mysqli_query($conn, $sql_transfert) or die(mysqli_error($conn));

    // Delete the record in materiel_topo table
    $sql = "DELETE FROM materiel_topo WHERE id = $id";
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

    if ($result) {
        $_SESSION['message'] = "Le matériel a été supprimé avec succès.";
        header("Location: list.php");
    } else {
        $_SESSION['error'] = "Erreur lors de la suppression du matériel.";
        header("Location: list.php");
    }
}
?>
