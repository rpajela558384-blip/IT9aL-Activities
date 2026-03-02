<link rel="stylesheet" href="styles.css">

<?php
require 'database.php';
 
 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item = $_POST['item'];
    $price = $_POST['price'];
    $qty = $_POST['qty'];
    $total = $price * $qty;
 
    if ($price < 0 || $qty < 1) {
        die("Price must be non-negative and quantity must be at least 1.");
    } else {

    $sql = "INSERT INTO transactions (item, price, qty, total)
            VALUES (:item, :price, :qty, :total)";
 
 
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':item' => $item,
        ':price' => $price,
        ':qty' => $qty,
        ':total' => $total,
    ]);}


 
 
    header("Location: Read.php");
}
?>