<?php
declare(strict_types=1);

require __DIR__ . '/../src/session.php';
require __DIR__ . '/../src/database.php';
require __DIR__ . '/../src/account-photo.php';
$userId = quiz_require_user();

$pseudo = $_SESSION['pseudo'];
$activePage = 'themes.php';
$hasPhoto = false;
try {
    $hasPhoto = quiz_photo_for_user(quiz_database(), $userId) !== null;
} catch (PDOException $exception) {
    error_log($exception->getMessage());
}

$themes = [
    'geographie' => 'Géographie',
    'histoire' => 'Histoire',
    'sciences' => 'Sciences',
    'sport' => 'Sport',
    'culture' => 'Culture générale',
    'arts' => 'Arts et divertissement',
];
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
            <?php foreach ($themes as $image => $theme): ?>
                <article class="theme-card" tabindex="0" aria-labelledby="theme-<?= $image ?>">
                    <img src="assets/images/theme-<?= $image ?>.webp" alt="" width="440" height="205">
                    <h2 id="theme-<?= $image ?>"><?= $theme ?></h2>
                    <ul class="theme-levels" aria-label="Niveaux de difficulté">
                        <li>Facile</li>
                        <li>Moyen</li>
                        <li>Difficile</li>
                    </ul>
                </article>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>
