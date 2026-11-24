<?php
declare(strict_types=1);

require __DIR__ . '/../src/session.php';
require __DIR__ . '/../src/database.php';
require __DIR__ . '/../src/game.php';
quiz_require_user();

$themes = [];
$error = '';
$limit = quiz_game_limit();
try {
    $database = quiz_database();
    $categories = $database->query(
        "SELECT id_categorie, nom_categorie, description FROM categories
         WHERE nom_categorie IN ('Géographie', 'Sciences') ORDER BY nom_categorie"
    );
    foreach ($categories as $category) {
        $themes[$category['id_categorie']] = $category + ['niveaux' => []];
    }
    $counts = $database->query(
        'SELECT q.id_categorie, d.libelle, COUNT(*) AS total
         FROM questions q JOIN difficultes d ON d.id_difficulte = q.id_difficulte
         WHERE ' . QUIZ_PLAYABLE_QUESTION . '
         GROUP BY q.id_categorie, d.id_difficulte, d.libelle ORDER BY d.id_difficulte'
    );
    foreach ($counts as $count) {
        if (isset($themes[$count['id_categorie']])) {
            $themes[$count['id_categorie']]['niveaux'][$count['libelle']] = (int) $count['total'];
        }
    }
} catch (PDOException $exception) {
    error_log($exception->getMessage());
    http_response_code(503);
    $error = 'Les thèmes sont temporairement indisponibles.';
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thèmes · Quiz de culture générale</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <main class="account-panel theme-panel">
        <h1>Choisissez un thème</h1>
        <p>Jusqu’à <?= $limit ?> questions. Une seule réponse possible par question.</p>
        <?php if ($error !== ''): ?>
            <p role="alert"><?= quiz_escape($error) ?></p>
        <?php else: ?>
            <?php if (!array_filter(array_column($themes, 'niveaux'))): ?>
                <p>Aucun thème n’est disponible pour le moment.</p>
            <?php endif; ?>
            <ul class="categories">
                <?php foreach ($themes as $theme): ?>
                    <li>
                        <h2><?= quiz_escape($theme['nom_categorie']) ?></h2>
                        <?php if ($theme['niveaux']): ?>
                            <p><?= quiz_escape($theme['description'] ?? '') ?></p>
                            <ul>
                                <?php foreach ($theme['niveaux'] as $level => $count): ?>
                                    <li><?= quiz_escape($level) ?> · <?= $count ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <form action="partie.php?theme_id=<?= (int) $theme['id_categorie'] ?>" method="post">
                                <input type="hidden" name="csrf_token" value="<?= quiz_csrf_token() ?>">
                                <input type="hidden" name="action" value="start">
                                <button class="btn btn-primary" type="submit">Commencer <?= quiz_escape($theme['nom_categorie']) ?></button>
                            </form>
                        <?php else: ?>
                            <p>Aucune question disponible pour ce thème.</p>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <p><a href="accueil.php">Retour à l’accueil</a></p>
    </main>
</body>
</html>
