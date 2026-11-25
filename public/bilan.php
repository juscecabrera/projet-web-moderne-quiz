<?php
declare(strict_types=1);

require __DIR__ . '/../src/session.php';
require __DIR__ . '/../src/database.php';
require __DIR__ . '/../src/game.php';
require __DIR__ . '/../src/account-photo.php';
$userId = quiz_require_user();
$pseudo = $_SESSION['pseudo'];
$activePage = 'themes.php';
$hasPhoto = false;
$game = null;
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
        $hasPhoto = quiz_photo_for_user($database, $userId) !== null;
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
$art = QUIZ_THEME_ART[$game['theme'] ?? ''] ?? null;
$level = QUIZ_LEVELS[$game['niveau'] ?? 0] ?? null;
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bilan du quiz · Déclic</title>
    <link rel="stylesheet" href="assets/declic.css">
</head>
<body class="declic-layout">
    <?php require __DIR__ . '/../src/declic-navigation.php'; ?>
    <main id="contenu" class="game-page">
        <header class="game-banner">
            <img src="assets/images/<?= $art ? 'theme-' . $art : 'accueil-classement' ?>.webp" alt="">
            <h1>Bilan du quiz</h1>
            <?php if ($game): ?><p><?= quiz_escape($game['theme'] ?? 'Quiz') ?><?= $level ? ' · ' . $level : '' ?></p><?php endif; ?>
        </header>
        <div class="game-content game-summary">
            <?php if ($error !== ''): ?>
                <p class="account-message account-error" role="alert"><?= quiz_escape($error) ?></p>
                <a class="game-primary" href="themes.php">Retour aux thèmes</a>
            <?php else: ?>
                <?php $correct = (int) $game['nombre_bonnes_reponses']; ?>
                <div class="game-score">
                    <strong>+<?= quiz_escape(quiz_game_points($game['score_total'])) ?> pts</strong>
                    <p>Gagnés dans cette partie</p>
                </div>
                <div class="game-stats">
                    <p><strong><?= $correct ?></strong> <?= $correct === 1 ? 'bonne réponse' : 'bonnes réponses' ?></p>
                    <?php $errors = (int) $game['total'] - $correct; ?>
                    <p><strong><?= $errors ?></strong> <?= $errors === 1 ? 'erreur' : 'erreurs' ?></p>
                </div>
                <div class="game-summary-actions">
                    <a class="game-primary" href="themes.php">Rejouer</a>
                    <?php if ($game['theme_id'] !== null): ?>
                        <a class="game-secondary" href="classement.php?theme_id=<?= (int) $game['theme_id'] ?>">Voir le classement du thème</a>
                    <?php endif; ?>
                </div>
                <details class="game-review">
                    <summary>Revoir les réponses</summary>
                    <ol>
                        <?php foreach ($answers as $answer): ?>
                            <li>
                                <strong><?= quiz_escape($answer['enonce']) ?></strong>
                                <p>Ta réponse : <?= quiz_escape($answer['choix'] ?? '') ?><br>
                                    Bonne réponse : <?= quiz_escape($answer['bonne_reponse'] ?? '') ?></p>
                                <?php if ($answer['explication']): ?><p><?= quiz_escape($answer['explication']) ?></p><?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                </details>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
