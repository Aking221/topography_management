<?php
include '../../includes/db.php';

$sql = "SELECT last_num_devis FROM devis_counter ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$last_num_devis = $row['last_num_devis'];
$num = (int)substr($last_num_devis, 4);
$num++;
$new_num_devis = 'DEV-' . str_pad($num, 4, '0', STR_PAD_LEFT);

echo $new_num_devis;

$conn->close();
?>
