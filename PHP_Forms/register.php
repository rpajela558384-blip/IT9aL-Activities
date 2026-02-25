<?php

require __DIR__ . '/common.php';

$errors = [];
$username = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm'] ?? '';

    if (!require_minlength($username, 3) && !username !== '') {
        $errors[] = "Username must be at least 3 characters long.";
    } // username must be at least 3 characters

    if (!require_minlength($password, 6)) {
        $errors[] = "Password must be at least 6 characters long.";
    } // password must be at least 6 characters

    if (!is_email($email)) {
        $errors[] = "Invalid email address.";
    } // validate email format

    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    } // confirm password must match

    if ($username === '' || $email === '' || $password === '') {
        $errors[] = "All fields are required.";
    } // check for empty fields

    if ($username !== '' && isset($_SESSION['users'][$username])) {
        $errors[] = "Username already taken.";
    } // check for duplicate username

    if (!$errors) {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $_SESSION['users'][$username] = [
            'hash' => $hash,
            'email' => $email,
            'createdAt' => date('c'),
        ];

        set_flash("Registration successful! You can now log in.");
        header("Location: login.php");
        exit;
    }

}
?>


<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Sign Up</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
</head>
<body>
  <h1>Create an Account</h1>

  <?php foreach ($errors as $err): ?>
    <div class="error"><?= htmlspecialchars($err) ?></div> 
  <?php endforeach; ?>

  <form method="post" autocomplete="off" novalidate>
    
    <label>Username
      <input name="username" required minlength="3" value="<?= htmlspecialchars($username) ?>"> <br><br>
      
    </label>
    <label>Email
      <input type="email" name="email" required value="<?= htmlspecialchars($email) ?>"><br><br>
    </label>
    <label>Password
      <input type="password" name="password" required minlength="6"><br><br>
    </label>
    <label>Confirm Password
      <input type="password" name="confirm" required minlength="6"><br><br>
    </label>
    <button type="submit">Sign Up</button>
  </form>

  <p>Already have an account? <a href="login.php">Log in</a></p>
</body>
</html>
