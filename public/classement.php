<?php
declare(strict_types=1);

require __DIR__ . '/../src/session.php';
require __DIR__ . '/../src/database.php';
require __DIR__ . '/../src/game.php';
quiz_require_user();

$themeId = filter_input(INPUT_GET, 'theme_id', FILTER_VALIDATE_INT);
$themes = $ranking = [];
$error = '';
try {
    $database = quiz_database();
    $categories = $database->query('SELECT id_categorie, nom_categorie FROM categories ORDER BY nom_categorie');
    $themes = array_column($categories->fetchAll(PDO::FETCH_ASSOC), 'nom_categorie', 'id_categorie');
    if ($themeId === null) {
        $themeId = array_key_first($themes);
    }
    if ($themeId !== null && !isset($themes[$themeId])) {
        http_response_code(400);
        $error = 'Choisissez un thème proposé.';
    } elseif ($themeId !== null) {
        $ranking = quiz_ranking($database, $themeId);
    }
} catch (PDOException $exception) {
    error_log($exception->getMessage());
    http_response_code(503);
    $error = 'Le classement est temporairement indisponible.';
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Classement · Quiz de culture générale</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <main class="account-panel theme-panel">
        <h1>Classement</h1>
        <?php if ($themes): ?>
            <form method="get">
                <div>
                    <label for="ranking-theme">Thème</label>
                    <select id="ranking-theme" name="theme_id">
                        <?php foreach ($themes as $id => $name): ?>
                            <option value="<?= $id ?>" <?= $id === $themeId ? 'selected' : '' ?>><?= quiz_escape($name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button class="btn btn-primary">Afficher le classement</button>
            </form>
        <?php endif; ?>
        <?php if ($error !== ''): ?>
            <p role="alert"><?= quiz_escape($error) ?></p>
        <?php elseif (!$ranking): ?>
            <p>Aucun joueur n’a terminé de quiz de 10 questions dans ce thème.</p>
        <?php else: ?>
            <table class="quiz-table">
                <thead><tr><th scope="col">Rang</th><th scope="col">Pseudo</th><th scope="col">Taux de réussite</th></tr></thead>
                <tbody>
                    <?php foreach ($ranking as $entry): ?>
                        <tr>
                            <td><?= (int) $entry['rang'] ?></td>
                            <td><?= quiz_escape($entry['pseudo']) ?></td>
                            <td><?= quiz_success_rate((int) $entry['bonnes_reponses'], 10) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        <p><a href="accueil.php">Retour à l’accueil</a></p>
    </main>
</body>
</html>
