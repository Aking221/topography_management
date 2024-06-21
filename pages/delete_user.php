<?php
require_once('../includes/db.php'); 
require_once('../includes/auth.php');

// Vérification sur la session authentification et privilège admin
if (!isset($_SESSION["authentification"]) || $_SESSION['privilege'] !== 'admin') {
    $_SESSION['error'] = "Vous n'avez pas accès à cette section.";
    header("Location: dashboard.php"); // Redirection vers le tableau de bord
    exit();
}

$userId = $_GET['id'];

// Suppression de l'utilisateur
$deleteUserQuery = "DELETE FROM utilisateurs WHERE id='$userId'";
$resultDelete = mysqli_query($conn, $deleteUserQuery) or die(mysqli_error($conn));

if ($resultDelete) {
    header("Location: listeutilisateurs.php?delete=ok");
} else {
    header("Location: listeutilisateurs.php?erreur=delete");
}
?>
