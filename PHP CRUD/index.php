<link rel="stylesheet" href="styles.css">

<h2>Add Transaction</h2>
 
 
<form method="post" action="Create.php">
    Item: <input type="text" name="item" required><br>
    Price: <input type="number" name="price" min="0" step="0.01" required><br>
    Quantity: <input type="number" name="qty" min="1" step="1" required><br>
    <button type="submit">Save</button>
</form>
 
 
<a href="Read.php">View Transactions</a>