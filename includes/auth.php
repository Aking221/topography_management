<?php
session_start();

function requireLogin() {
    // Vérifier si l'utilisateur est déjà connecté
    if (!isset($_SESSION['user_id'])) {
        // Redirection vers l'index pour s'authentifier
        header('Location: ../index.php');
        exit();
    }
}
?>
