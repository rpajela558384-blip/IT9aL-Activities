<link rel="stylesheet" href="styles.css">

<?php
require 'database.php';
$stmt = $pdo->query("SELECT * FROM transactions ORDER BY id DESC");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
 
 
<h2>Transaction List</h2>
<a href="index.php">Add New</a>
 
 
<table border="1" cellpadding="8">
<tr>
    <th>ID</th>
    <th>Item</th>
    <th>Price</th>
    <th>Qty</th>
    <th>Total</th>
    <th>Action</th>
</tr>
 
 
<?php foreach ($rows as $row): ?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= $row['item'] ?></td>
    <td><?= $row['price'] ?></td>
    <td><?= $row['qty'] ?></td>
    <td><?= $row['total'] ?></td>
    <td>
        <a href="Update.php?id=<?= $row['id'] ?>">Edit</a> |
        <a href="Delete.php?id=<?= $row['id'] ?>">Delete</a>
    </td>
</tr>
<?php endforeach; ?>
</table>