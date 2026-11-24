<?php
declare(strict_types=1);

require __DIR__ . '/../src/session.php';
require __DIR__ . '/../src/database.php';
require __DIR__ . '/../src/game.php';
$userId = quiz_require_user();
$error = '';
$games = [];
$themeId = filter_input(INPUT_GET, 'theme_id', FILTER_VALIDATE_INT);
$themes = [];

try {
    $database = quiz_database();
    $games = quiz_game_results($database, $userId);
    $themes = array_column(quiz_themes($database), 'nom_categorie', 'id_categorie');
    foreach ($games as $game) {
        if ($game['theme_id'] !== null) {
            $themes[$game['theme_id']] = $game['theme'];
        }
    }
    $filteredGames = $themeId ? array_filter($games, static fn(array $game): bool => (int) $game['theme_id'] === $themeId) : $games;
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
        <?php if ($games): ?>
            <form method="get">
                <div>
                    <label for="history-theme">Thème</label>
                    <select id="history-theme" name="theme_id">
                        <option value="0">Tous les thèmes</option>
                        <?php foreach ($themes as $id => $name): ?>
                            <option value="<?= $id ?>" <?= $id === $themeId ? 'selected' : '' ?>><?= quiz_escape($name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button class="btn btn-primary">Filtrer</button>
            </form>
        <?php endif; ?>
        <?php if ($error !== ''): ?>
            <p role="alert"><?= quiz_escape($error) ?></p>
        <?php elseif (!$games): ?>
            <p>Tu n’as encore terminé aucune partie</p>
        <?php elseif (!$filteredGames): ?>
            <p>Aucune partie terminée dans ce thème.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($filteredGames as $game): ?>
                    <li>
                        <a href="bilan.php?partie_id=<?= (int) $game['id_partie'] ?>">
                            <?= quiz_escape($game['theme'] ?? 'Partie') ?> · <?= quiz_escape($game['date_fin']) ?>
                        </a>
                        · <?= (int) $game['nombre_bonnes_reponses'] ?>/<?= (int) $game['total'] ?>
                        (<?= quiz_success_rate((int) $game['nombre_bonnes_reponses'], (int) $game['total']) ?>)
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <p><a class="btn btn-primary" href="themes.php">Jouer</a></p>
        <p><a href="accueil.php">Retour à l’accueil</a></p>
    </main>
</body>
</html>
