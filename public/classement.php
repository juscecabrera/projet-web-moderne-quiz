<?php
declare(strict_types=1);

require __DIR__ . '/../src/session.php';
require __DIR__ . '/../src/database.php';
require __DIR__ . '/../src/game.php';
require __DIR__ . '/../src/layout.php';
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

quiz_layout_start('Classement', 'classement', true);
?>
<div class="page-head">
    <div>
        <p class="eyebrow">Compétition</p>
        <h1>Classement</h1>
        <p class="lead">Le meilleur taux de réussite par joueur, sur des parties complètes de 10 questions.</p>
    </div>
    <?php if ($themeId !== null && isset($themes[$themeId])): ?>
        <span class="chip chip--primary"><?= quiz_escape($themes[$themeId]) ?></span>
    <?php endif; ?>
</div>

<?php quiz_alert($error); ?>

<?php if ($themes): ?>
    <form method="get" class="card card--soft filter-bar">
        <div class="form-row">
            <div class="field">
                <label for="ranking-theme">Thème</label>
                <select id="ranking-theme" name="theme_id">
                    <?php foreach ($themes as $id => $name): ?>
                        <option value="<?= $id ?>" <?= $id === $themeId ? 'selected' : '' ?>><?= quiz_escape($name) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button class="btn btn-primary">Afficher le classement</button>
        </div>
    </form>
<?php endif; ?>

<?php if ($error !== ''): ?>
    <?php if (!$themes): ?>
        <div class="empty-state">
            <div class="empty-state__icon" aria-hidden="true">🏆</div>
            <h2>Classement indisponible</h2>
            <p>Le classement ne peut pas être chargé pour le moment.</p>
        </div>
    <?php endif; ?>
<?php elseif (!$ranking): ?>
    <div class="empty-state">
        <div class="empty-state__icon" aria-hidden="true">🏆</div>
        <h2>Pas encore de classement</h2>
        <p>Aucun joueur n’a terminé de quiz de 10 questions dans ce thème. Soyez le premier !</p>
        <a class="btn btn-primary" href="themes.php">Jouer maintenant</a>
    </div>
<?php else: ?>
    <div class="table-wrap">
        <table class="quiz-table">
            <thead>
                <tr>
                    <th scope="col">Rang</th>
                    <th scope="col">Joueur</th>
                    <th scope="col">Taux de réussite</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ranking as $entry): ?>
                    <?php
                    $rank = (int) $entry['rang'];
                    $pseudo = (string) $entry['pseudo'];
                    $initial = mb_strtoupper(mb_substr($pseudo, 0, 1));
                    ?>
                    <tr>
                        <td>
                            <span class="rank<?= $rank <= 3 ? ' rank--' . $rank : '' ?>"><?= $rank ?></span>
                        </td>
                        <td>
                            <span class="player">
                                <span class="player__avatar" aria-hidden="true"><?= quiz_escape($initial) ?></span>
                                <?= quiz_escape($pseudo) ?>
                            </span>
                        </td>
                        <td><?= quiz_success_rate((int) $entry['bonnes_reponses'], 10) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<div class="page-actions">
    <a class="btn btn-ghost" href="accueil.php">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M19 12H5"/><path d="m11 18-6-6 6-6"/>
        </svg>
        Retour à l’accueil
    </a>
</div>
<?php
quiz_layout_end();
