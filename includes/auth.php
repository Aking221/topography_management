<?php
session_start();

function requireLogin() {
    if (!isset($_SESSION['authentification']) || (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 5))) {
        
        session_unset();     
        session_destroy();   
        header('Location: ../../index.php');
        exit();
    }
    $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
}
?>
