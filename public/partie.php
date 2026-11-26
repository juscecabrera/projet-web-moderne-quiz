<?php
declare(strict_types=1);

require __DIR__ . '/../src/session.php';
require __DIR__ . '/../src/database.php';
require __DIR__ . '/../src/game.php';
require __DIR__ . '/../src/layout.php';
$userId = quiz_require_user();
$isPost = $_SERVER['REQUEST_METHOD'] === 'POST';
if ($isPost) {
    quiz_require_post_token();
}

$themeId = filter_input(INPUT_GET, 'theme_id', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1, 'max_range' => 4294967295],
]);
$error = '';
$theme = null;
$question = null;
$correction = null;
$reponses = [];
$url = 'partie.php?theme_id=' . $themeId;

if (!$themeId) {
    http_response_code(400);
    $error = 'Choisissez un thème valide depuis l’accueil.';
} else {
    try {
        $database = quiz_database();
        $theme = quiz_game_theme($database, $themeId);
        $game = $_SESSION['quiz_game'] ?? null;
        if ($game && $game['theme_id'] !== $themeId) {
            $game = null;
        }
        $action = $isPost ? quiz_input('action') : '';

        if (!$theme) {
            http_response_code(404);
            $error = 'Ce thème est introuvable.';
        } elseif ($action === 'start') {
            $level = filter_input(INPUT_POST, 'level', FILTER_VALIDATE_INT);
            if (!isset(QUIZ_LEVELS[$level])) {
                http_response_code(400);
                $error = 'Choisissez une difficulté depuis la page des thèmes.';
            } else {
                if ($game && ($game['level'] ?? null) === $level &&
                    count(quiz_game_answers($database, $game['id'])) < count($game['questions'])) {
                    quiz_redirect($url);
                }
                $questions = quiz_game_questions($database, $themeId, $level);
                if ($questions) {
                    quiz_game_start($database, $userId, $themeId, $level, $questions);
                    quiz_redirect($url);
                }
                http_response_code(404);
                $error = 'Aucune question disponible pour cette difficulté.';
            }
        } elseif (!$game) {
            if ($isPost) {
                http_response_code(409);
                $error = 'Cette partie n’est plus active. Retournez aux thèmes pour en lancer une autre.';
            } else {
                quiz_redirect('themes.php');
            }
        } else {
            $gameId = $game['id'];
            $total = count($game['questions']);
            $answers = quiz_game_answers($database, $gameId);
            $index = count($answers);
            $lastQuestion = $index > 0 ? (int) $answers[$index - 1] : null;
            $correctionUrl = $url . '&correction=' . $lastQuestion;
            $requestedCorrection = filter_input(INPUT_GET, 'correction', FILTER_VALIDATE_INT);
            $questionId = $game['questions'][$index] ?? null;

            if ($action === 'abandon') {
                if (filter_input(INPUT_POST, 'partie_id', FILTER_VALIDATE_INT) !== $gameId) {
                    http_response_code(409);
                    exit('Cette partie n’est plus active.');
                }
                quiz_game_abandon($database, $userId, $gameId);
                unset($_SESSION['quiz_game']);
                quiz_redirect('themes.php');
            }
            if ($action === 'answer') {
                $postedGame = filter_input(INPUT_POST, 'partie_id', FILTER_VALIDATE_INT);
                $postedQuestion = filter_input(INPUT_POST, 'question_id', FILTER_VALIDATE_INT);
                $answerId = filter_input(INPUT_POST, 'reponse_id', FILTER_VALIDATE_INT);
                if ($postedGame !== $gameId) {
                    http_response_code(409);
                    $error = 'Cette partie n’est plus active.';
                } elseif ($postedQuestion !== $questionId) {
                    quiz_redirect($lastQuestion === null ? $url : $correctionUrl);
                } elseif ($answerId && quiz_game_submit(
                    $database, $gameId, $userId, $questionId, $answerId, $index + 1 === $total
                )) {
                    quiz_redirect($url . '&correction=' . $questionId);
                } else {
                    http_response_code(422);
                    $error = 'Choisissez une réponse proposée pour cette question.';
                }
            }

            if ($lastQuestion !== null && $requestedCorrection === $lastQuestion) {
                $correction = quiz_game_details($database, $gameId, $lastQuestion)[0] ?? null;
            } elseif ($questionId === null) {
                quiz_redirect($correctionUrl);
            } else {
                $statement = $database->prepare(
                    'SELECT q.enonce, d.libelle FROM questions q
                     JOIN difficultes d ON d.id_difficulte = q.id_difficulte WHERE q.id_question = ?'
                );
                $statement->execute([$questionId]);
                $question = $statement->fetch(PDO::FETCH_ASSOC);
                $statement = $database->prepare(
                    'SELECT id_reponse, texte_reponse FROM reponses WHERE id_question = ? ORDER BY id_reponse'
                );
                $statement->execute([$questionId]);
                $reponses = $statement->fetchAll(PDO::FETCH_ASSOC);
                if (!$question || count($reponses) !== 4) {
                    http_response_code(409);
                    $question = null;
                    $error = 'Cette question n’est plus disponible.';
                }
            }
        }
    } catch (PDOException $exception) {
        error_log($exception->getMessage());
        http_response_code(503);
        $error = 'La partie est temporairement indisponible.';
        $question = null;
    }
}

$hasGame = isset($gameId, $index, $total);
$progress = $hasGame && $total > 0
    ? (int) round(100 * (($correction ? $index : $index + 1) / $total))
    : 0;
$levelLabel = QUIZ_LEVELS[$game['level'] ?? 0] ?? null;

quiz_layout_start($theme['nom_categorie'] ?? 'Partie', 'themes', true);
?>
<div class="page-head">
    <div>
        <p class="eyebrow"><?= $correction ? 'Correction' : 'Partie en cours' ?></p>
        <h1><?= quiz_escape($theme['nom_categorie'] ?? 'Partie') ?></h1>
    </div>
    <?php if ($levelLabel !== null && $hasGame): ?>
        <span class="badge badge--<?= (int) ($game['level'] ?? 0) ?>"><?= quiz_escape($levelLabel) ?></span>
    <?php endif; ?>
</div>

<?php quiz_alert($error); ?>

<?php if ($correction): ?>
    <?php if ($hasGame): ?>
        <div class="quiz-progress">
            <div class="quiz-progress__meta">
                <span>Question <?= $index ?> sur <?= $total ?></span>
                <span><?= $progress ?> %</span>
            </div>
            <div class="quiz-progress__bar" role="progressbar" aria-valuenow="<?= $progress ?>" aria-valuemin="0" aria-valuemax="100">
                <span style="width: <?= $progress ?>%"></span>
            </div>
        </div>
    <?php endif; ?>

    <section class="card correction <?= $correction['est_correcte'] ? 'correction--correct' : 'correction--wrong' ?>" aria-label="Correction">
        <div class="correction__status">
            <span class="correction__icon" aria-hidden="true">
                <?php if ($correction['est_correcte']): ?>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                <?php else: ?>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                <?php endif; ?>
            </span>
            <span role="status"><?= $correction['est_correcte'] ? 'Bonne réponse !' : 'Réponse incorrecte' ?></span>
        </div>

        <h2><?= quiz_escape($correction['enonce']) ?></h2>

        <div class="correction__lines">
            <div class="correction__line">
                <span>Votre choix</span>
                <span><?= quiz_escape($correction['choix'] ?? '') ?></span>
            </div>
            <div class="correction__line">
                <span>Réponse attendue</span>
                <span><?= quiz_escape($correction['bonne_reponse'] ?? '') ?></span>
            </div>
            <div class="correction__line">
                <span>Points gagnés</span>
                <span><?= quiz_escape(quiz_game_points($correction['points_obtenus'])) ?> <?= (float) $correction['points_obtenus'] > 1 ? 'pts' : 'pt' ?></span>
            </div>
        </div>

        <?php if ($correction['explication']): ?>
            <p class="correction__explanation"><?= quiz_escape($correction['explication']) ?></p>
        <?php endif; ?>
    </section>

    <div class="page-actions">
        <?php if ($index < $total): ?>
            <a class="btn btn-primary btn-lg" href="<?= quiz_escape($url) ?>">
                Question suivante
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M5 12h14"/><path d="m13 6 6 6-6 6"/>
                </svg>
            </a>
        <?php else: ?>
            <a class="btn btn-primary btn-lg" href="bilan.php?partie_id=<?= $gameId ?>">
                Voir mon bilan
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M5 12h14"/><path d="m13 6 6 6-6 6"/>
                </svg>
            </a>
        <?php endif; ?>
    </div>
<?php elseif ($question): ?>
    <?php if ($hasGame): ?>
        <div class="quiz-progress">
            <div class="quiz-progress__meta">
                <span>Question <?= $index + 1 ?> sur <?= $total ?></span>
                <span><?= $progress ?> %</span>
            </div>
            <div class="quiz-progress__bar" role="progressbar" aria-valuenow="<?= $progress ?>" aria-valuemin="0" aria-valuemax="100">
                <span style="width: <?= $progress ?>%"></span>
            </div>
        </div>
    <?php endif; ?>

    <section class="card question-card">
        <h2 id="question" class="question"><?= quiz_escape($question['enonce']) ?></h2>

        <form method="post" aria-labelledby="question" class="answer-form" data-answer-form>
            <input type="hidden" name="csrf_token" value="<?= quiz_csrf_token() ?>">
            <input type="hidden" name="action" value="answer">
            <input type="hidden" name="partie_id" value="<?= $gameId ?>">
            <input type="hidden" name="question_id" value="<?= $questionId ?>">
            <div class="answers">
                <?php foreach ($reponses as $position => $reponse): ?>
                    <label class="answer-option">
                        <input type="radio" name="reponse_id" value="<?= (int) $reponse['id_reponse'] ?>" required>
                        <span class="answer-option__letter" aria-hidden="true"><?= chr(65 + $position) ?></span>
                        <span><?= quiz_escape($reponse['texte_reponse']) ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
            <button class="btn btn-primary btn-lg btn-block" type="submit">Valider ma réponse</button>
        </form>
    </section>
<?php endif; ?>

<div class="page-actions">
    <?php if ($hasGame && ($question || $correction) && $index < $total): ?>
        <form method="post" class="abandon-form">
            <input type="hidden" name="csrf_token" value="<?= quiz_csrf_token() ?>">
            <input type="hidden" name="action" value="abandon">
            <input type="hidden" name="partie_id" value="<?= $gameId ?>">
            <button class="btn btn-danger" type="submit">Abandonner la partie</button>
        </form>
    <?php endif; ?>
    <a class="btn btn-ghost" href="themes.php">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M19 12H5"/><path d="m11 18-6-6 6-6"/>
        </svg>
        Retour aux thèmes
    </a>
</div>
<?php
quiz_layout_end();
