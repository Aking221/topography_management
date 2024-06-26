<?php
include '../../includes/db.php';
include '../../includes/auth.php';

if (!isset($_SESSION["authentification"]) || !in_array($_SESSION['privilege'], ['admin'])) {
    $_SESSION['error'] = "Vous n'avez pas accès à cette section.";
    header("Location: ../dashboard.php"); // Redirection vers le tableau de bord
    exit();
}


$id = $_GET['id'];

$sql = "DELETE FROM chantiers WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

header('Location: view.php');
exit();
?>
