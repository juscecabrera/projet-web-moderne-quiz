<?php
declare(strict_types=1);

require __DIR__ . '/../src/session.php';
require __DIR__ . '/../src/database.php';
require __DIR__ . '/../src/game.php';
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
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Partie · Quiz de culture générale</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <main class="account-panel theme-panel">
        <h1><?= quiz_escape($theme['nom_categorie'] ?? 'Partie') ?></h1>
        <?php if ($error !== ''): ?>
            <p role="alert"><?= quiz_escape($error) ?></p>
        <?php endif; ?>
        <?php if ($correction): ?>
                <section aria-label="Correction">
                    <p>Question <?= $index ?> sur <?= $total ?></p>
                    <h2><?= quiz_escape($correction['enonce']) ?></h2>
                    <p role="status"><?= $correction['est_correcte'] ? 'Bonne réponse' : 'Réponse incorrecte' ?></p>
                    <p>Votre choix : <?= quiz_escape($correction['choix'] ?? '') ?></p>
                    <p>Réponse attendue : <?= quiz_escape($correction['bonne_reponse'] ?? '') ?></p>
                    <p>Points gagnés : <?= quiz_escape(quiz_game_points($correction['points_obtenus'])) ?></p>
                    <?php if ($correction['explication']): ?>
                        <p><?= quiz_escape($correction['explication']) ?></p>
                    <?php endif; ?>
                </section>
                <?php if ($index < $total): ?>
                    <a class="btn btn-primary" href="<?= quiz_escape($url) ?>">Question suivante</a>
                <?php else: ?>
                    <a class="btn btn-primary" href="bilan.php?partie_id=<?= $gameId ?>">Voir mon bilan</a>
                <?php endif; ?>
        <?php elseif ($question): ?>
            <p>Question <?= $index + 1 ?> sur <?= $total ?></p>
            <p>Difficulté : <?= quiz_escape(QUIZ_LEVELS[$game['level'] ?? 0] ?? $question['libelle']) ?></p>
            <h2 id="question"><?= quiz_escape($question['enonce']) ?></h2>
            <form method="post" aria-labelledby="question" data-answer-form>
                <input type="hidden" name="csrf_token" value="<?= quiz_csrf_token() ?>">
                <input type="hidden" name="action" value="answer">
                <input type="hidden" name="partie_id" value="<?= $gameId ?>">
                <input type="hidden" name="question_id" value="<?= $questionId ?>">
                <?php foreach ($reponses as $reponse): ?>
                    <label class="answer-option">
                        <input type="radio" name="reponse_id" value="<?= (int) $reponse['id_reponse'] ?>" required>
                        <?= quiz_escape($reponse['texte_reponse']) ?>
                    </label>
                <?php endforeach; ?>
                <button class="btn btn-primary" type="submit">Valider</button>
            </form>
        <?php endif; ?>
        <?php if (($question || $correction) && $index < $total): ?>
            <form method="post" class="abandon-form">
                <input type="hidden" name="csrf_token" value="<?= quiz_csrf_token() ?>">
                <input type="hidden" name="action" value="abandon">
                <input type="hidden" name="partie_id" value="<?= $gameId ?>">
                <button class="btn" type="submit">Abandonner la partie</button>
            </form>
        <?php endif; ?>
        <p><a href="themes.php">Retour aux thèmes</a></p>
    </main>
    <script src="assets/js/answer-form.js" defer></script>
</body>
</html>
