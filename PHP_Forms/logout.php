<?php
require __DIR__ . '/common.php';
unset($_SESSION['user']);
header('Location: login.php');
exit;