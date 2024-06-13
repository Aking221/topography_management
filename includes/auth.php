<?php
session_start();

function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../pages/user_login.php');
        exit();
    }
}
?>
