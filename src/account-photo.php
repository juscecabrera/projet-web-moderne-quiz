<?php
declare(strict_types=1);

function quiz_photo_for_user(PDO $database, int $userId): ?string
{
    try {
        $statement = $database->prepare('SELECT image_webp FROM photos_profils WHERE id_utilisateur = ?');
        $statement->execute([$userId]);
        $photo = $statement->fetchColumn();
        return $photo === false ? null : $photo;
    } catch (PDOException $exception) {
        if (($exception->errorInfo[1] ?? null) === 1146) {
            return null;
        }
        throw $exception;
    }
}

function quiz_avatar_initial(string $pseudo): string
{
    return mb_strtoupper(mb_substr($pseudo, 0, 1, 'UTF-8'), 'UTF-8');
}

function quiz_uploaded_avatar(mixed $file): string
{
    if (!function_exists('imagewebp')) {
        throw new RuntimeException('La conversion des photos est indisponible sur ce serveur.');
    }
    if (!is_array($file) || ($file['error'] ?? null) !== UPLOAD_ERR_OK || $file['size'] > 3_000_000 ||
        !is_uploaded_file($file['tmp_name'])) {
        throw new DomainException('Choisis une image de 3 Mo maximum.');
    }
    $info = @getimagesize($file['tmp_name']);
    if (!$info || !in_array($info[2], [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP], true) ||
        max($info[0], $info[1]) > 3200) {
        throw new DomainException('Choisis une image JPEG, PNG ou WebP de 3 200 pixels maximum par côté.');
    }
    $source = @imagecreatefromstring(file_get_contents($file['tmp_name']));
    if (!$source) {
        throw new DomainException('Cette image ne peut pas être lue.');
    }
    $size = min($info[0], $info[1]);
    $photo = imagecreatetruecolor(256, 256);
    imagealphablending($photo, false);
    imagesavealpha($photo, true);
    imagecopyresampled($photo, $source, 0, 0, intdiv($info[0] - $size, 2), intdiv($info[1] - $size, 2), 256, 256, $size, $size);
    ob_start();
    try {
        imagewebp($photo, null, 82);
        $encoded = ob_get_contents();
    } finally {
        ob_end_clean();
    }
    if (!$encoded || strlen($encoded) > 65535) {
        throw new DomainException('Cette photo ne peut pas être enregistrée. Essaie une autre image.');
    }
    return $encoded;
}
