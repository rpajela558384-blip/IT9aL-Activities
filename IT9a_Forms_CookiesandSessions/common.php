<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['users'])) {
    $_SESSION['users'] = [];
}

function set_flash(string $msg): void { $_SESSION['flash'] = $msg; }

function get_flash(): string {
    $m = $_SESSION['flash'] ?? '';
    unset($_SESSION['flash']);
    return $m;
}

function is_email(string $email): bool {
    return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
}

function require_minlength(string $value, int $n): bool {
    return mb_strlen($value) >= $n;
}

function remember_username(string $username): void {
    if ($username) {
        setcookie('remember_username', $username, time() + 86400 * 30); // 30 days
    } else {
        setcookie('remember_username', '', time() - 3600); // delete cookie
    }
}

function get_remembered_username(): string {
    return $_COOKIE['remember_username'] ?? '';
}