<?php
declare(strict_types=1);

$https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
session_start([
    'use_strict_mode' => 1,
    'use_only_cookies' => 1,
    'cookie_httponly' => true,
    'cookie_secure' => $https,
    'cookie_samesite' => 'Lax',
]);

function quiz_redirect(string $page): never
{
    header('Location: ' . $page, true, 303);
    exit;
}

function quiz_require_user(): int
{
    if (!isset($_SESSION['user_id'])) {
        quiz_redirect('connexion.php');
    }

    return $_SESSION['user_id'];
}

function quiz_csrf_token(): string
{
    return $_SESSION['csrf_token'] ??= bin2hex(random_bytes(32));
}

function quiz_require_post_token(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Allow: POST', true, 405);
        exit;
    }

    $token = $_POST['csrf_token'] ?? null;
    if (!is_string($token) || !hash_equals(quiz_csrf_token(), $token)) {
        http_response_code(403);
        exit('Formulaire expiré. Rechargez la page et réessayez.');
    }
}

function quiz_input(string $name): string
{
    $value = $_POST[$name] ?? '';
    return is_string($value) ? $value : '';
}

function quiz_escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
