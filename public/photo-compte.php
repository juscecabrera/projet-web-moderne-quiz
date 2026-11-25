<?php
declare(strict_types=1);

require __DIR__ . '/../src/session.php';
require __DIR__ . '/../src/database.php';
require __DIR__ . '/../src/account-photo.php';
$userId = quiz_require_user();
$playerId = filter_input(INPUT_GET, 'joueur', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1, 'max_range' => 4294967295],
]);
if ($playerId === false) {
    http_response_code(400);
    exit;
}
try {
    $photo = quiz_photo_for_user(quiz_database(), $playerId ?? $userId);
} catch (PDOException $exception) {
    error_log($exception->getMessage());
    http_response_code(503);
    exit;
}
if ($photo === null) {
    http_response_code(404);
    exit;
}
header('Content-Type: image/webp');
header('Cache-Control: private, no-store');
header('X-Content-Type-Options: nosniff');
echo $photo;
