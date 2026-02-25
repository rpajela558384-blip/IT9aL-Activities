<?php
require __DIR__ . '/common.php';

$flash = '';
if (isset($_GET['registered'])) {
    $flash = get_flash();
}

$error = '';
$remembered = get_remembered_username();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);


    if ($username === '' || $password === '') {
        $error = 'Please enter both username and password.';
    } else {

        if (!isset($_SESSION['users'])) {
            $_SESSION['users'] = [];
        }


        if (isset($_SESSION['users'][$username])) {

            $hash = $_SESSION['users'][$username]['hash'];
            if (password_verify($password, $hash)) {

                $_SESSION['user'] = $username;


                remember_username($remember ? $username : null);


                header('Location: dashboard.php');
                exit;
            }
        }

        $error = 'Invalid username or password.';
    }


    $remembered = $username;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
</head>
<body>
  <h1>Login</h1>

  <?php if ($flash): ?>
    <div class="flash"><?= htmlspecialchars($flash) ?></div>
  <?php endif; ?>

  <?php if ($error): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  
  <form method="post" autocomplete="off">
    <label>Username
      <input type="text" name="username" required value="<?= htmlspecialchars($remembered) ?>"><br><br>
    </label>
    <label>Password
      <input type="password" name="password" required><br><br>
    </label>
    <label>
      <input type="checkbox" name="remember" <?= $remembered ? 'checked' : '' ?>>
      Remember me
    </label><br><br>
    <button>Login</button>
  </form>

  <p>Don’t have an account? <a href="register.php">Register</a></p>
</body>
</html>