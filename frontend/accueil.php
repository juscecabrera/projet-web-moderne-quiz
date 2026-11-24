<?php
declare(strict_types=1);

require __DIR__ . '/includes/session.php';
require __DIR__ . '/includes/database.php';
quiz_require_user();

$themes = [];
$error = '';
try {
    $themes = quiz_database()->query(
        "SELECT c.id_categorie, c.nom_categorie, c.description,
                COUNT(q.id_question) AS nombre_questions
         FROM categories c
         LEFT JOIN questions q ON q.id_categorie = c.id_categorie
             AND q.statut_validation = 'validee'
             AND EXISTS (
                 SELECT 1 FROM reponses r WHERE r.id_question = q.id_question
                 GROUP BY r.id_question HAVING COUNT(*) >= 2 AND SUM(r.est_correcte = 1) = 1
             )
         GROUP BY c.id_categorie
         ORDER BY c.nom_categorie"
    )->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Accueil · Quiz de culture générale</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/auth.css">
</head>
<body>
    <main class="account-panel theme-panel">
        <h1>Bonjour, <?= quiz_escape($_SESSION['pseudo']) ?></h1>
        <h2>Choisissez un thème</h2>
        <?php if ($error !== ''): ?>
            <p role="alert"><?= quiz_escape($error) ?></p>
        <?php elseif ($themes === []): ?>
            <p>Aucun thème disponible.</p>
        <?php else: ?>
            <ul class="categories">
                <?php foreach ($themes as $theme): ?>
                    <li>
                        <?php if ((int) $theme['nombre_questions'] > 0): ?>
                            <a class="category" href="partie.php?theme_id=<?= (int) $theme['id_categorie'] ?>">
                                <strong><?= quiz_escape($theme['nom_categorie']) ?></strong>
                                <span><?= quiz_escape($theme['description'] ?? '') ?></span>
                            </a>
                        <?php else: ?>
                            <div class="category">
                                <strong><?= quiz_escape($theme['nom_categorie']) ?></strong>
                                <span>Aucune question disponible</span>
                            </div>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <form action="deconnexion.php" method="post">
            <input type="hidden" name="csrf_token" value="<?= quiz_csrf_token() ?>">
            <button class="btn" type="submit">Se déconnecter</button>
        </form>
    </main>
</body>
</html>
