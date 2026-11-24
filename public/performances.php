<?php
declare(strict_types=1);

require __DIR__ . '/../src/session.php';
require __DIR__ . '/../src/database.php';
require __DIR__ . '/../src/game.php';
$userId = quiz_require_user();
$error = '';
$games = [];

try {
    $games = quiz_game_results(quiz_database(), $userId);
} catch (PDOException $exception) {
    error_log($exception->getMessage());
    http_response_code(503);
    $error = 'Les performances sont temporairement indisponibles.';
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mes performances · Quiz de culture générale</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <main class="account-panel theme-panel">
        <h1>Mes performances</h1>
        <?php if ($error !== ''): ?>
            <p role="alert"><?= quiz_escape($error) ?></p>
        <?php elseif (!$games): ?>
            <p>Tu n’as encore terminé aucune partie</p>
        <?php else: ?>
            <ul>
                <?php foreach ($games as $game): ?>
                    <li>
                        <a href="bilan.php?partie_id=<?= (int) $game['id_partie'] ?>">
                            <?= quiz_escape($game['theme'] ?? 'Partie') ?> · <?= quiz_escape($game['date_fin']) ?>
                        </a>
                        (<?= quiz_escape(quiz_game_points($game['score_total'])) ?> points)
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <p><a class="btn btn-primary" href="themes.php">Jouer</a></p>
        <p><a href="accueil.php">Retour à l’accueil</a></p>
    </main>
</body>
</html>
