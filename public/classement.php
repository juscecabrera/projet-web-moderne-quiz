<?php
declare(strict_types=1);

require __DIR__ . '/../src/session.php';
require __DIR__ . '/../src/database.php';
require __DIR__ . '/../src/game.php';
require __DIR__ . '/../src/account-photo.php';
$userId = quiz_require_user();
$pseudo = $_SESSION['pseudo'];
$activePage = 'classement.php';
$hasPhoto = false;
$ownRank = null;

$themeId = filter_input(INPUT_GET, 'theme_id', FILTER_VALIDATE_INT);
$themes = $ranking = [];
$error = '';
try {
    $database = quiz_database();
    $hasPhoto = quiz_photo_for_user($database, $userId) !== null;
    $themes = array_column(quiz_themes($database), 'nom_categorie', 'id_categorie');
    if ($themeId === null) {
        $themeId = array_key_first($themes);
    }
    if ($themeId !== null && !isset($themes[$themeId])) {
        http_response_code(400);
        $error = 'Choisissez un thème proposé.';
    } elseif ($themeId !== null) {
        $ranking = quiz_ranking($database, $themeId);
        foreach ($ranking as $entry) {
            if ((int) $entry['id_utilisateur'] === $userId) {
                $ownRank = $entry;
                break;
            }
        }
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
    <title>Classement · Déclic</title>
    <link rel="stylesheet" href="assets/declic.css">
</head>
<body class="declic-layout">
    <?php require __DIR__ . '/../src/declic-navigation.php'; ?>
    <main id="contenu" class="game-page">
        <header class="game-banner ranking-banner">
            <img src="assets/images/accueil-classement.webp" alt="">
            <h1>Classement</h1>
            <p>Les points cumulés de chaque joueur par thème.</p>
        </header>
        <div class="game-content">
            <?php if ($themes): ?>
                <nav class="ranking-themes" aria-label="Thèmes du classement">
                    <?php foreach ($themes as $id => $name): ?>
                        <a href="classement.php?theme_id=<?= (int) $id ?>" <?= (int) $id === $themeId ? 'aria-current="page"' : '' ?>>
                            <?= quiz_escape($name) ?>
                        </a>
                    <?php endforeach; ?>
                </nav>
            <?php endif; ?>
            <?php if ($error !== ''): ?>
                <p class="account-message account-error" role="alert"><?= quiz_escape($error) ?></p>
            <?php else: ?>
                <h2><?= quiz_escape($themes[$themeId] ?? 'Thèmes') ?></h2>
                <?php if ($ownRank): ?>
                    <p class="ranking-position">Ton rang : <strong><?= (int) $ownRank['rang'] ?></strong> · <?= quiz_escape(quiz_game_points($ownRank['points'])) ?> pts</p>
                <?php endif; ?>
                <?php if (!$ranking): ?>
                    <p>Aucun joueur n’a encore terminé de quiz dans ce thème.</p>
                <?php else: ?>
                    <table class="ranking-table" aria-label="Classement du thème <?= quiz_escape($themes[$themeId]) ?>">
                        <thead><tr><th scope="col">Rang</th><th scope="col">Joueur</th><th scope="col">Points</th></tr></thead>
                        <tbody>
                            <?php foreach ($ranking as $entry): ?>
                                <tr class="<?= (int) $entry['rang'] === 1 ? 'ranking-leader' : '' ?>">
                                    <td><?= (int) $entry['rang'] ?></td>
                                    <th scope="row">
                                        <span class="declic-initial" aria-hidden="true"><?= quiz_escape(quiz_avatar_initial($entry['pseudo'])) ?></span>
                                        <?= quiz_escape($entry['pseudo']) ?>
                                    </th>
                                    <td><?= quiz_escape(quiz_game_points($entry['points'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <p class="ranking-note">Toutes les parties terminées dans ce thème comptent.</p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
