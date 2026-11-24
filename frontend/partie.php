<?php
declare(strict_types=1);

require __DIR__ . '/includes/session.php';
require __DIR__ . '/includes/database.php';
require __DIR__ . '/includes/game.php';
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

        if (!$theme) {
            http_response_code(404);
            $error = 'Ce thème est introuvable.';
        } elseif ($isPost && quiz_input('action') === 'start' && !$game) {
            $questions = quiz_game_questions($database, $themeId);
            if ($questions) {
                quiz_game_start($database, $userId, $themeId, $questions);
                quiz_redirect($url);
            }
            http_response_code(404);
            $error = 'Aucune question disponible pour ce thème.';
        } elseif (!$game) {
            if ($isPost) {
                http_response_code(409);
                $error = 'Cette partie n’est plus active. Retournez aux thèmes pour en lancer une autre.';
            } elseif (!quiz_game_questions($database, $themeId)) {
                http_response_code(404);
                $error = 'Aucune question disponible pour ce thème.';
            }
        } else {
            $gameId = $game['id'];
            $total = count($game['questions']);
            $answers = quiz_game_answers($database, $gameId);
            $index = count($answers);
            if ($index === $total) {
                unset($_SESSION['quiz_game']);
                quiz_redirect('bilan.php?partie_id=' . $gameId);
            }
            $questionId = $game['questions'][$index];

            if ($isPost && quiz_input('action') === 'answer') {
                $postedGame = filter_input(INPUT_POST, 'partie_id', FILTER_VALIDATE_INT);
                $postedQuestion = filter_input(INPUT_POST, 'question_id', FILTER_VALIDATE_INT);
                $answerId = filter_input(INPUT_POST, 'reponse_id', FILTER_VALIDATE_INT);
                if ($postedGame !== $gameId) {
                    http_response_code(409);
                    $error = 'Cette partie n’est plus active.';
                } elseif ($postedQuestion !== $questionId) {
                    quiz_redirect($url);
                } elseif ($answerId && quiz_game_submit(
                    $database, $gameId, $userId, $questionId, $answerId, $index + 1 === $total
                )) {
                    quiz_redirect($url);
                } else {
                    http_response_code(422);
                    $error = 'Choisissez une réponse proposée pour cette question.';
                }
            }

            $statement = $database->prepare(
                'SELECT enonce FROM questions WHERE id_question = ?'
            );
            $statement->execute([$questionId]);
            $question = $statement->fetchColumn();
            $statement = $database->prepare(
                'SELECT id_reponse, texte_reponse FROM reponses WHERE id_question = ? ORDER BY id_reponse'
            );
            $statement->execute([$questionId]);
            $reponses = $statement->fetchAll(PDO::FETCH_ASSOC);
            if (!$question || !$reponses) {
                http_response_code(409);
                $question = null;
                $error = 'Cette question n’est plus disponible.';
            }
            if ($index > 0) {
                $correction = quiz_game_details($database, $gameId, (int) $answers[$index - 1])[0] ?? null;
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
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/auth.css">
</head>
<body>
    <main class="account-panel theme-panel">
        <h1><?= quiz_escape($theme['nom_categorie'] ?? 'Partie') ?></h1>
        <?php if ($error !== ''): ?>
            <p role="alert"><?= quiz_escape($error) ?></p>
        <?php endif; ?>
        <?php if ($question): ?>
            <?php if ($correction): ?>
                <section aria-label="Correction de la question précédente">
                    <h2><?= $correction['est_correcte'] ? 'Bonne réponse' : 'Réponse incorrecte' ?></h2>
                    <p>Réponse attendue : <?= quiz_escape($correction['bonne_reponse'] ?? '') ?></p>
                    <?php if ($correction['explication']): ?>
                        <p><?= quiz_escape($correction['explication']) ?></p>
                    <?php endif; ?>
                </section>
            <?php endif; ?>
            <p>Question <?= $index + 1 ?> sur <?= $total ?></p>
            <h2 id="question"><?= quiz_escape($question) ?></h2>
            <form method="post" aria-labelledby="question">
                <input type="hidden" name="csrf_token" value="<?= quiz_csrf_token() ?>">
                <input type="hidden" name="action" value="answer">
                <input type="hidden" name="partie_id" value="<?= $gameId ?>">
                <input type="hidden" name="question_id" value="<?= $questionId ?>">
                <?php foreach ($reponses as $reponse): ?>
                    <button class="category" type="submit" name="reponse_id" value="<?= (int) $reponse['id_reponse'] ?>">
                        <?= quiz_escape($reponse['texte_reponse']) ?>
                    </button>
                <?php endforeach; ?>
            </form>
        <?php elseif ($error === ''): ?>
            <form method="post">
                <input type="hidden" name="csrf_token" value="<?= quiz_csrf_token() ?>">
                <input type="hidden" name="action" value="start">
                <button class="btn btn-primary" type="submit">Lancer le quiz</button>
            </form>
        <?php endif; ?>
        <p><a href="accueil.php">Retour aux thèmes</a></p>
    </main>
</body>
</html>
