<?php
declare(strict_types=1);

require __DIR__ . '/../src/session.php';
require __DIR__ . '/../src/database.php';
require __DIR__ . '/../src/game.php';
require __DIR__ . '/../src/layout.php';
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

quiz_layout_start('Mes performances', 'performances', true);
?>
<div class="page-head">
    <div>
        <p class="eyebrow">Historique</p>
        <h1>Mes performances</h1>
        <p class="lead">Retrouvez vos parties terminées et comparez votre progression.</p>
    </div>
    <a class="btn btn-primary" href="themes.php">
        Jouer
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M5 12h14"/><path d="m13 6 6 6-6 6"/>
        </svg>
    </a>
</div>

<?php quiz_alert($error); ?>

<?php if ($error === '' && $games): ?>
    <form method="get" class="card card--soft filter-bar">
        <div class="form-row">
            <div class="field">
                <label for="history-theme">Filtrer par thème</label>
                <select id="history-theme" name="theme_id">
                    <option value="0">Tous les thèmes</option>
                    <?php foreach ($themes as $id => $name): ?>
                        <option value="<?= $id ?>" <?= $id === $themeId ? 'selected' : '' ?>><?= quiz_escape($name) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button class="btn btn-secondary">Filtrer</button>
        </div>
    </form>
<?php endif; ?>

<?php if ($error === '' && !$games): ?>
    <div class="empty-state">
        <div class="empty-state__icon" aria-hidden="true">📊</div>
        <h2>Aucune partie terminée</h2>
        <p>Lancez votre première partie pour voir apparaître ici votre historique et vos scores.</p>
        <a class="btn btn-primary" href="themes.php">Jouer maintenant</a>
    </div>
<?php elseif ($error === '' && !$filteredGames): ?>
    <div class="empty-state">
        <div class="empty-state__icon" aria-hidden="true">🔎</div>
        <h2>Aucun résultat</h2>
        <p>Vous n’avez terminé aucune partie dans ce thème pour le moment.</p>
        <a class="btn btn-secondary" href="performances.php">Voir toutes les parties</a>
    </div>
<?php elseif ($error === ''): ?>
    <ul class="history-list">
        <?php foreach ($filteredGames as $game): ?>
            <?php
            $date = $game['date_fin'] ? strtotime((string) $game['date_fin']) : false;
            $correct = (int) $game['nombre_bonnes_reponses'];
            $total = (int) $game['total'];
            ?>
            <li class="history-item">
                <div class="history-item__main">
                    <p class="history-item__theme"><?= quiz_escape($game['theme'] ?? 'Partie') ?></p>
                    <p class="history-item__date">
                        <?= $date ? date('d/m/Y · H:i', $date) : 'Date inconnue' ?>
                        · <?= $correct ?>/<?= $total ?> bonnes réponses
                    </p>
                </div>
                <div class="history-item__score">
                    <span class="chip <?= $total > 0 && $correct / $total >= 0.5 ? 'chip--success' : 'chip--danger' ?>">
                        <?= quiz_success_rate($correct, $total) ?>
                    </span>
                    <span class="history-item__rate"><?= $correct ?>/<?= $total ?></span>
                </div>
                <a class="btn btn-secondary btn-sm" href="bilan.php?partie_id=<?= (int) $game['id_partie'] ?>">
                    Voir le bilan
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
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
