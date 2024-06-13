<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "topographie2";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
} catch(Exception $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}
?>
