<link rel="stylesheet" href="styles.css">

<?php
require 'database.php';
 
 
$id = $_GET['id'];
 
 
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE id = :id");
$stmt->execute([':id' => $id]);
$data = $stmt->fetch();
 
 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item = $_POST['item'];
    $price = $_POST['price'];
    $qty = $_POST['qty'];
    $total = $price * $qty;
 
    if ($price < 0 || $qty < 1) {
    die("Price must be non-negative and quantity must be at least 1.");
    } else {
    $sql = "UPDATE transactions 
            SET item=:item, price=:price, qty=:qty, total=:total
            WHERE id=:id";
 
 
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':item' => $item,
        ':price' => $price,
        ':qty' => $qty,
        ':total' => $total,
        ':id' => $id
    ]);}
 
 
    header("Location: Read.php");
}
?>
 
 
<form method="post">
    Item: <input type="text" name="item" value="<?= $data['item'] ?>" required><br>
    Price: <input type="number" name="price" value="<?= $data['price'] ?>" min="0" step="0.01" required><br>
    Qty: <input type="number" name="qty" value="<?= $data['qty'] ?>" min="1" step="1" required><br>
    <button type="submit">Update</button>
</form>
