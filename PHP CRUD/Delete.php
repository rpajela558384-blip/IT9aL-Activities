<?php
require 'database.php';
 
 
$id = $_GET['id'];
 
 
$stmt = $pdo->prepare("DELETE FROM transactions WHERE id = :id");
$stmt->execute([':id' => $id]);
 
 
header("Location: Read.php");
?>