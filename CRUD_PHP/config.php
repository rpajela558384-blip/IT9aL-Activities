<?php

const DB_HOST    = 'localhost';
const DB_NAME    = 'php_crud_activity';
const DB_USER    = 'root';
const DB_PASS    = '';
const DB_CHARSET = 'utf8mb4';

// ---- SESSION ----
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ---- DATABASE CONNECTION ----
function get_database_connection(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    }
    return $pdo;
}


function escape_html(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function is_post_request(): bool {
    return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}

// ---- AUTH ----
function get_authenticated_user(): ?array {
    if (empty($_SESSION['user_id'])) {
        return null;
    }
    static $cached_user = null;
    if ($cached_user === null) {
        $db = get_database_connection();
        $stmt = $db->prepare("SELECT id, email, role, status FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $cached_user = $stmt->fetch() ?: null;
    }
    return $cached_user;
}

function ensure_authenticated(): void {
    if (!get_authenticated_user()) {
        header('Location: /PHP/CRUD_PHP/login.php');
        exit;
    }
}