<?php
require __DIR__ . '/config.php';

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

session_destroy();
header('Location: /PHP/CRUD_PHP/login.php');
exit;