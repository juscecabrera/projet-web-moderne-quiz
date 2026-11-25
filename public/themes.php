<?php
declare(strict_types=1);

require __DIR__ . '/../src/session.php';
require __DIR__ . '/../src/database.php';
require __DIR__ . '/../src/account-photo.php';
require __DIR__ . '/../src/game.php';
$userId = quiz_require_user();

$pseudo = $_SESSION['pseudo'];
$activePage = 'themes.php';
$hasPhoto = false;
$available = [];
try {
    $database = quiz_database();
    $hasPhoto = quiz_photo_for_user($database, $userId) !== null;
    $levels = $database->query(
        'SELECT c.nom_categorie, c.id_categorie, q.id_difficulte
         FROM categories c JOIN questions q USING (id_categorie)
         WHERE q.id_difficulte IN (1, 2, 3) AND ' . QUIZ_PLAYABLE_QUESTION . '
         GROUP BY c.id_categorie, q.id_difficulte'
    );
    foreach ($levels as $level) {
        $available[$level['nom_categorie']]['id'] = (int) $level['id_categorie'];
        $available[$level['nom_categorie']]['levels'][] = (int) $level['id_difficulte'];
    }
} catch (PDOException $exception) {
    error_log($exception->getMessage());
}

?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thèmes · Déclic</title>
    <link rel="stylesheet" href="assets/declic.css">
</head>
<body class="declic-layout">
    <?php require __DIR__ . '/../src/declic-navigation.php'; ?>

    <main id="contenu" class="declic-themes">
        <header class="themes-hero">
            <h1>Choisis ton thème</h1>
            <p>Six univers, trois niveaux de difficulté.</p>
        </header>
        <div class="themes-grid">
            <?php foreach (QUIZ_THEME_ART as $theme => $image): ?>
                <article class="theme-card" tabindex="0" aria-labelledby="theme-<?= $image ?>">
                    <img src="assets/images/theme-<?= $image ?>.webp" alt="" width="440" height="205">
                    <h2 id="theme-<?= $image ?>"><?= $theme ?></h2>
                    <?php $catalogue = $available[$theme] ?? null; ?>
                    <?php if ($catalogue): ?>
                        <form method="post" action="partie.php?theme_id=<?= $catalogue['id'] ?>" class="theme-levels" aria-label="Difficulté · <?= quiz_escape($theme) ?>">
                            <input type="hidden" name="csrf_token" value="<?= quiz_csrf_token() ?>">
                            <input type="hidden" name="action" value="start">
                            <?php foreach (QUIZ_LEVELS as $level => $label): ?>
                                <label><input type="radio" name="level" value="<?= $level ?>" required <?= in_array($level, $catalogue['levels'], true) ? '' : 'disabled' ?>>
                                    <?= $label ?>
                                </label>
                            <?php endforeach; ?>
                            <button class="game-primary" type="submit">Lancer le quiz</button>
                        </form>
                    <?php else: ?>
                        <p class="theme-levels">Questions à venir</p>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>
