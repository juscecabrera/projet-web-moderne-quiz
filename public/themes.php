<?php
declare(strict_types=1);

require __DIR__ . '/../src/session.php';
require __DIR__ . '/../src/database.php';
require __DIR__ . '/../src/game.php';
require __DIR__ . '/../src/layout.php';
quiz_require_user();

$themes = [];
$error = '';
$limit = quiz_game_limit();
try {
    $database = quiz_database();
    $categories = quiz_themes($database);
    foreach ($categories as $category) {
        $themes[$category['id_categorie']] = $category + ['niveaux' => []];
    }
    $counts = $database->query(
        'SELECT q.id_categorie, q.id_difficulte, COUNT(*) AS total
         FROM questions q WHERE ' . QUIZ_PLAYABLE_QUESTION . '
         GROUP BY q.id_categorie, q.id_difficulte'
    );
    foreach ($counts as $count) {
        if (isset($themes[$count['id_categorie']])) {
            $themes[$count['id_categorie']]['niveaux'][$count['id_difficulte']] = (int) $count['total'];
        }
    }
} catch (PDOException $exception) {
    error_log($exception->getMessage());
    http_response_code(503);
    $error = 'Les thèmes sont temporairement indisponibles.';
}

$icons = ['Géographie' => '🌍', 'Histoire' => '📜', 'Sciences' => '🔬', 'Sport' => '🏅', 'Culture générale' => '🎭'];
$hasPlayableTheme = (bool) array_filter(array_column($themes, 'niveaux'));

quiz_layout_start('Thèmes', 'themes', true);
?>
<div class="page-head">
    <div>
        <p class="eyebrow">Jouer</p>
        <h1>Choisissez un thème</h1>
        <p class="lead">Jusqu’à <?= $limit ?> questions par partie. Une seule réponse possible par question.</p>
    </div>
</div>

<?php quiz_alert($error); ?>

<?php if ($error === '' && !$hasPlayableTheme): ?>
    <div class="empty-state">
        <div class="empty-state__icon" aria-hidden="true">🎯</div>
        <h2>Aucun thème disponible</h2>
        <p>Les questions arrivent bientôt. Revenez un peu plus tard pour lancer une partie.</p>
        <a class="btn btn-secondary" href="accueil.php">Retour à l’accueil</a>
    </div>
<?php else: ?>
    <div class="theme-grid">
        <?php foreach ($themes as $theme): ?>
            <?php
            $firstEnabled = null;
            foreach (QUIZ_LEVELS as $levelId => $level) {
                if (($theme['niveaux'][$levelId] ?? 0) > 0) {
                    $firstEnabled = $levelId;
                    break;
                }
            }
            ?>
            <article class="theme-card">
                <div class="theme-card__head">
                    <span class="theme-card__icon" aria-hidden="true"><?= $icons[$theme['nom_categorie']] ?? '🎯' ?></span>
                    <h2><?= quiz_escape($theme['nom_categorie']) ?></h2>
                </div>
                <p class="theme-card__desc">
                    <?= quiz_escape($theme['description'] ?? '') !== ''
                        ? quiz_escape($theme['description'])
                        : 'Testez vos connaissances sur ce thème.' ?>
                </p>

                <?php if ($theme['niveaux']): ?>
                    <form action="partie.php?theme_id=<?= (int) $theme['id_categorie'] ?>" method="post">
                        <input type="hidden" name="csrf_token" value="<?= quiz_csrf_token() ?>">
                        <input type="hidden" name="action" value="start">
                        <fieldset class="level-picker">
                            <legend>Difficulté</legend>
                            <div class="level-options">
                                <?php foreach (QUIZ_LEVELS as $levelId => $level): ?>
                                    <?php $count = $theme['niveaux'][$levelId] ?? 0; ?>
                                    <label class="level-option<?= $count === 0 ? ' is-disabled' : '' ?>">
                                        <input type="radio" name="level" value="<?= $levelId ?>"
                                            <?= $levelId === $firstEnabled ? 'required' : '' ?>
                                            <?= $count === 0 ? 'disabled' : '' ?>>
                                        <span class="level-option__body">
                                            <span class="level-option__name"><?= quiz_escape($level) ?></span>
                                            <span class="level-option__count">
                                                <?= $count === 0 ? 'Indisponible' : $count . ' question' . ($count > 1 ? 's' : '') ?>
                                            </span>
                                        </span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </fieldset>
                        <button class="btn btn-primary btn-block" type="submit">
                            Commencer
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M5 12h14"/><path d="m13 6 6 6-6 6"/>
                            </svg>
                        </button>
                    </form>
                <?php else: ?>
                    <div class="alert alert--info">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 16v-5"/><path d="M12 8h.01"/><circle cx="12" cy="12" r="10"/>
                        </svg>
                        <span>Aucune question disponible pour ce thème.</span>
                    </div>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
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
