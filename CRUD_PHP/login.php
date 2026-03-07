<?php

require __DIR__ . '/config.php';

if (empty($_SESSION['user_id']) && !empty($_COOKIE['remember_user'])) {
    $cookie_user_id = (int) $_COOKIE['remember_user'];
    if ($cookie_user_id > 0) {
        $db = get_database_connection();
        $check = $db->prepare("SELECT id, status FROM users WHERE id = ?");
        $check->execute([$cookie_user_id]);
        $u = $check->fetch();
        if ($u && $u['status'] === 'active') {
            $_SESSION['user_id'] = (int)$u['id'];
            header('Location: /PHP/CRUD_PHP/employees.php');
            exit;
        } else {
            setcookie(
                'remember_user',
                '',
                [
                    'expires' => time() - 3600,
                    'path'    => '/PHP/CRUD_PHP',
                    'secure'  => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
                    'httponly'=> true,
                    'samesite'=> 'Lax',
                ]
            );
        }
    }
}

if (isset($_GET['register'])) {
    $page_mode = 'register';
} else {
    $page_mode = 'login';
}

$form_errors = [];

if (is_post_request()) {
    $db = get_database_connection();

    if (isset($_POST['action']) && $_POST['action'] === 'register') {
        if (isset($_POST['email'])) {
            $email = trim($_POST['email']);
        } else {
            $email = '';
        }
        if (isset($_POST['password'])) {
            $password = $_POST['password'];
        } else {
            $password = '';
        }
        if (isset($_POST['confirm'])) {
            $password_confirm = $_POST['confirm'];
        } else {
            $password_confirm = '';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $form_errors[] = 'Invalid email.';
        }
        if (strlen($password) < 6) {
            $form_errors[] = 'Password must be at least 6 characters.';
        }
        if ($password !== $password_confirm) {
            $form_errors[] = 'Passwords do not match.';
        }

        if (!$form_errors) {
            try {
                $password_hash = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $db->prepare("INSERT INTO users (email, password_hash, role, status) VALUES (?, ?, 'employee', 'active')");
                $stmt->execute([$email, $password_hash]);

                $_SESSION['user_id'] = (int)$db->lastInsertId();

                header('Location: /PHP/CRUD_PHP/employees.php');
                exit;
            } catch (PDOException $ex) {
                $mysql_code = $ex->errorInfo[1] ?? null;
                if ($mysql_code == 1062) {
                    $form_errors[] = 'Email already exists.';
                } else {
                    $form_errors[] = 'Database error: ' . escape_html($ex->getMessage());
                }
            }
        }
        $page_mode = 'register';
    } else {
        if (isset($_POST['email'])) {
            $email = trim($_POST['email']);
        } else {
            $email = '';
        }
        if (isset($_POST['password'])) {
            $password = $_POST['password'];
        } else {
            $password = '';
        }

        $stmt = $db->prepare("SELECT id, email, password_hash, status FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user_row = $stmt->fetch();

        $is_valid = $user_row
            && $user_row['status'] === 'active'
            && password_verify($password, $user_row['password_hash']);

        if (!$is_valid) {
            $form_errors[] = 'Invalid credentials or inactive account.';
        } else {
            $_SESSION['user_id'] = (int)$user_row['id'];

            $remember = isset($_POST['remember']) && $_POST['remember'] === '1';
            if ($remember) {
                setcookie(
                    'remember_user',
                    (string)$user_row['id'],
                    [
                        'expires'  => time() + 60*60*24*30,
                        'path'     => '/PHP/CRUD_PHP',
                        'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
                        'httponly' => true,
                        'samesite' => 'Lax',
                    ]
                );
            } else {
                setcookie(
                    'remember_user',
                    '',
                    [
                        'expires' => time() - 3600,
                        'path'    => '/PHP/CRUD_PHP',
                        'secure'  => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
                        'httponly'=> true,
                        'samesite'=> 'Lax',
                    ]
                );
            }

            header('Location: /PHP/CRUD_PHP/employees.php');
            exit;
        }
        $page_mode = 'login';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?= $page_mode === 'register' ? 'Register' : 'Login' ?> | Simple HRMS</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial; background:#f6f7fb; margin:0; padding:2rem;}
    .card{max-width:420px; margin:2rem auto; background:#fff; border:1px solid #e5e7eb; border-radius:10px; padding:1.25rem;}
    .row{margin-bottom:0.75rem}
    label{display:block; font-size:0.9rem; margin-bottom:0.25rem}
    input{width:95%; padding:0.55rem; border:1px solid #cbd5e1; border-radius:6px}
    button{padding:0.6rem 0.9rem; border:0; background:#2563eb; color:#fff; border-radius:6px; cursor:pointer}
    .link{color:#2563eb; text-decoration:none; font-size:0.9rem}
    .error{background:#fee2e2; color:#991b1b; padding:0.6rem; border-radius:6px; margin-bottom:0.75rem; font-size:0.9rem}
    .muted{font-size:0.9rem; color:#475569}
    .inline{display:flex; align-items:center; gap:.5rem}
  </style>
</head>
<body>
  <div class="card">
    <h2 style="margin-top:0"><?= $page_mode === 'register' ? 'Create an account' : 'Sign in' ?></h2>

    <?php if ($form_errors): ?>
      <div class="error">
        <?php foreach ($form_errors as $msg) echo '<div>'.escape_html($msg).'</div>'; ?>
      </div>
    <?php endif; ?>

    <?php if ($page_mode === 'register'): ?>
      <form method="post">
        <input type="hidden" name="action" value="register">
        <div class="row">
          <label>Email</label>
          <input type="email" name="email" required>
        </div>
        <div class="row">
          <label>Password</label>
          <input type="password" name="password" required minlength="6">
        </div>
        <div class="row">
          <label>Confirm Password</label>
          <input type="password" name="confirm" required minlength="6">
        </div>
        <button>Register</button>
      </form>
      <p class="muted" style="margin-top:0.75rem">
        Already have an account?
        <a class="link" href="/PHP/CRUD_PHP/login.php">Login</a>
      </p>
    <?php else: ?>
      <form method="post">
        <div class="row">
          <label>Email</label>
          <input type="email" name="email" required>
        </div>
        <div class="row">
          <label>Password</label>
          <input type="password" name="password" required>
        </div>
        <div class="row inline">
          <input type="checkbox" id="remember" name="remember" value="1">
          <label for="remember" style="margin:0">Remember me</label>
        </div>
        <button>Login</button>
      </form>
      <p class="muted" style="margin-top:0.75rem">
        No account?
        <a class="link" href="/PHP/CRUD_PHP/login.php?register=1">Create one</a>
      </p>
    <?php endif; ?>
  </div>
</body>
</html>