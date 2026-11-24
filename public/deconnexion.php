<?php
declare(strict_types=1);

require __DIR__ . '/../src/session.php';
quiz_require_post_token();

$cookie = session_get_cookie_params();
$_SESSION = [];
session_destroy();
setcookie(session_name(), '', [
    'expires' => time() - 3600,
    'path' => $cookie['path'],
    'domain' => $cookie['domain'],
    'secure' => $cookie['secure'],
    'httponly' => $cookie['httponly'],
    'samesite' => $cookie['samesite'],
]);

quiz_redirect('connexion.php');
