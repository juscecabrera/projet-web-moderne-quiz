<?php
declare(strict_types=1);

require __DIR__ . '/../src/session.php';
require __DIR__ . '/../src/database.php';
require __DIR__ . '/../src/game.php';
require __DIR__ . '/../src/layout.php';
$userId = quiz_require_user();
$gameId = filter_input(INPUT_GET, 'partie_id', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1, 'max_range' => 4294967295],
]);
$error = '';

if (!$gameId) {
    http_response_code(400);
    $error = 'Identifiant de partie invalide.';
} else {
    try {
        $database = quiz_database();
        $game = quiz_game_results($database, $userId, $gameId)[0] ?? null;
        if ($game) {
            $answers = quiz_game_details($database, $gameId);
        } else {
            http_response_code(404);
            $error = 'Ce bilan est introuvable.';
        }
    } catch (PDOException $exception) {
        error_log($exception->getMessage());
        http_response_code(503);
        $error = 'Le bilan est temporairement indisponible.';
    }
}

$total = $game ? (int) $game['total'] : 0;
$correct = $game ? (int) $game['nombre_bonnes_reponses'] : 0;

quiz_layout_start('Bilan', 'performances', true);
?>
<div class="page-head">
    <div>
        <p class="eyebrow">Résultat</p>
        <h1>Bilan de la partie</h1>
    </div>
    <?php if ($game): ?>
        <span class="chip chip--primary"><?= quiz_escape($game['theme'] ?? 'Non renseigné') ?></span>
    <?php endif; ?>
</div>

<?php quiz_alert($error); ?>

<?php if ($error === '' && $game): ?>
    <section class="score-hero" aria-label="Score de la partie">
        <p class="score-hero__label">Score final</p>
        <p class="score-hero__value"><?= quiz_escape(quiz_game_points($game['score_total'])) ?> <small>pts</small></p>
        <p class="score-hero__sub"><?= $correct ?> bonne<?= $correct > 1 ? 's' : '' ?> réponse<?= $correct > 1 ? 's' : '' ?> sur <?= $total ?></p>
    </section>

    <dl class="stat-grid">
        <div class="stat">
            <dt>Bonnes réponses</dt>
            <dd><?= $correct ?> / <?= $total ?></dd>
        </div>
        <div class="stat">
            <dt>Erreurs</dt>
            <dd><?= $total - $correct ?></dd>
        </div>
        <div class="stat">
            <dt>Taux de réussite</dt>
            <dd><?= quiz_success_rate($correct, $total) ?></dd>
        </div>
    </dl>

    <div class="page-actions">
        <a class="btn btn-primary" href="themes.php">
            Rejouer
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M21 12a9 9 0 1 1-2.6-6.4"/><path d="M21 3v6h-6"/>
            </svg>
        </a>
        <?php if ($game['theme_id'] !== null): ?>
            <a class="btn btn-secondary" href="classement.php?theme_id=<?= (int) $game['theme_id'] ?>">Voir le classement</a>
        <?php endif; ?>
        <a class="btn btn-ghost" href="performances.php">Mes performances</a>
    </div>

    <h2 class="section-title">Correction détaillée</h2>
    <ol class="correction-list">
        <?php foreach ($answers as $position => $answer): ?>
            <li>
                <article class="correction <?= $answer['est_correcte'] ? 'correction--correct' : 'correction--wrong' ?>">
                    <div class="correction__status">
                        <span class="correction__icon" aria-hidden="true">
                            <?php if ($answer['est_correcte']): ?>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                            <?php else: ?>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                            <?php endif; ?>
                        </span>
                        <span>Question <?= $position + 1 ?></span>
                    </div>
                    <h3><?= quiz_escape($answer['enonce']) ?></h3>
                    <div class="correction__lines">
                        <div class="correction__line">
                            <span>Votre choix</span>
                            <span><?= quiz_escape($answer['choix'] ?? '') ?></span>
                        </div>
                        <div class="correction__line">
                            <span>Réponse attendue</span>
                            <span><?= quiz_escape($answer['bonne_reponse'] ?? '') ?></span>
                        </div>
                        <div class="correction__line">
                            <span>Points</span>
                            <span><?= quiz_escape(quiz_game_points($answer['points_obtenus'])) ?> <?= (float) $answer['points_obtenus'] > 1 ? 'pts' : 'pt' ?></span>
                        </div>
                    </div>
                    <?php if ($answer['explication']): ?>
                        <p class="correction__explanation"><?= quiz_escape($answer['explication']) ?></p>
                    <?php endif; ?>
                </article>
            </li>
        <?php endforeach; ?>
    </ol>
<?php elseif ($error !== ''): ?>
    <div class="empty-state">
        <div class="empty-state__icon" aria-hidden="true">🔎</div>
        <h2>Bilan introuvable</h2>
        <p>Ce bilan n’existe pas ou n’est plus accessible depuis votre compte.</p>
        <a class="btn btn-primary" href="themes.php">Lancer une partie</a>
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
