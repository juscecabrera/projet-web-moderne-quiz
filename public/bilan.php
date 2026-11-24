<?php
declare(strict_types=1);

require __DIR__ . '/../src/session.php';
require __DIR__ . '/../src/database.php';
require __DIR__ . '/../src/game.php';
$userId = quiz_require_user();
$gameId = filter_input(INPUT_GET, 'partie_id', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1, 'max_range' => 4294967295],
]);
$error = '';

if (!$gameId) {
    http_response_code(400);
    $error = 'Identifiant de partie invalide.';
} else {
    try {
        $database = quiz_database();
        $game = quiz_game_results($database, $userId, $gameId)[0] ?? null;
        if ($game) {
            $answers = quiz_game_details($database, $gameId);
        } else {
            http_response_code(404);
            $error = 'Ce bilan est introuvable.';
        }
    } catch (PDOException $exception) {
        error_log($exception->getMessage());
        http_response_code(503);
        $error = 'Le bilan est temporairement indisponible.';
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bilan · Quiz de culture générale</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <main class="account-panel theme-panel">
        <h1>Bilan de la partie</h1>
        <?php if ($error !== ''): ?>
            <p role="alert"><?= quiz_escape($error) ?></p>
        <?php else: ?>
            <p>Thème : <?= quiz_escape($game['theme'] ?? 'Non renseigné') ?></p>
            <p>Score : <strong><?= quiz_escape(quiz_game_points($game['score_total'])) ?></strong></p>
            <p>Bonnes réponses : <?= (int) $game['nombre_bonnes_reponses'] ?> sur <?= (int) $game['total'] ?></p>
            <p>Erreurs : <?= (int) $game['total'] - (int) $game['nombre_bonnes_reponses'] ?></p>
            <h2>Correction</h2>
            <ol>
                <?php foreach ($answers as $answer): ?>
                    <li>
                        <strong><?= quiz_escape($answer['enonce']) ?></strong>
                        <p><?= $answer['est_correcte'] ? 'Bonne réponse' : 'Réponse incorrecte' ?>
                            (<?= quiz_escape(quiz_game_points($answer['points_obtenus'])) ?>)</p>
                        <p>Votre choix : <?= quiz_escape($answer['choix'] ?? '') ?><br>
                            Réponse attendue : <?= quiz_escape($answer['bonne_reponse'] ?? '') ?></p>
                        <?php if ($answer['explication']): ?>
                            <p><?= quiz_escape($answer['explication']) ?></p>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ol>
        <?php endif; ?>
        <p><a class="btn btn-primary" href="themes.php">Rejouer</a></p>
        <p><a href="performances.php">Mes performances</a></p>
    </main>
</body>
</html>
