<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: /pages/user_login.php");
        exit();
    }
}

function logout() {
    session_destroy();
    header("Location: /pages/user_login.php");
    exit();
}

// Vérifier si la requête contient le paramètre 'logout' pour déconnecter l'utilisateur
if (isset($_GET['logout'])) {
    logout();
}
?>
