<?php
require __DIR__ . '/common.php';

if (!isset($_SESSION['user'])) {

    header('Location: login.php');
    exit;
}

$user = $_SESSION['user'];
$remembered = get_remembered_username();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
</head>
<body>
  <h1>Welcome, <?= htmlspecialchars($user) ?>!</h1>
    <p>
      <a href="logout.php">Logout</a>
    </p>
</body>
</html>