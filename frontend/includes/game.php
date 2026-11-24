<?php
declare(strict_types=1);

function quiz_game_theme(PDO $database, int $themeId): array|false
{
    $statement = $database->prepare('SELECT nom_categorie FROM categories WHERE id_categorie = ?');
    $statement->execute([$themeId]);
    return $statement->fetch(PDO::FETCH_ASSOC);
}

function quiz_game_questions(PDO $database, int $themeId): array
{
    $statement = $database->prepare(
        "SELECT id_question FROM (
             SELECT q.id_question, q.id_difficulte,
                    ROW_NUMBER() OVER (PARTITION BY q.id_difficulte ORDER BY q.id_question) AS rang
             FROM questions q
             INNER JOIN reponses r ON r.id_question = q.id_question
             WHERE q.id_categorie = ? AND q.statut_validation = 'validee'
             GROUP BY q.id_question
             HAVING COUNT(*) >= 2 AND SUM(r.est_correcte = 1) = 1
         ) AS questions_jouables
         ORDER BY rang, id_difficulte LIMIT 10"
    );
    $statement->execute([$themeId]);
    return array_map('intval', $statement->fetchAll(PDO::FETCH_COLUMN));
}

function quiz_game_start(PDO $database, int $userId, int $themeId, array $questions): void
{
    $database->beginTransaction();
    try {
        if (isset($_SESSION['quiz_game'])) {
            $statement = $database->prepare(
                "UPDATE parties SET statut = 'abandonnee', date_fin = NOW()
                 WHERE id_partie = ? AND id_utilisateur = ? AND statut = 'en_cours'"
            );
            $statement->execute([$_SESSION['quiz_game']['id'], $userId]);
        }
        $statement = $database->prepare('INSERT INTO parties (id_utilisateur) VALUES (?)');
        $statement->execute([$userId]);
        $gameId = (int) $database->lastInsertId();
        $database->commit();
    } catch (Throwable $exception) {
        $database->rollBack();
        throw $exception;
    }

    $_SESSION['quiz_game'] = ['id' => $gameId, 'theme_id' => $themeId, 'questions' => $questions];
}

function quiz_game_answers(PDO $database, int $gameId): array
{
    $statement = $database->prepare(
        'SELECT id_question FROM reponses_joueurs WHERE id_partie = ? ORDER BY id_reponse_joueur'
    );
    $statement->execute([$gameId]);
    return $statement->fetchAll(PDO::FETCH_COLUMN);
}

function quiz_game_submit(
    PDO $database,
    int $gameId,
    int $userId,
    int $questionId,
    int $answerId,
    bool $finished
): bool {
    $database->beginTransaction();
    try {
        $game = $database->prepare(
            "SELECT id_partie FROM parties
             WHERE id_partie = ? AND id_utilisateur = ? AND statut = 'en_cours' FOR UPDATE"
        );
        $game->execute([$gameId, $userId]);
        if (!$game->fetchColumn()) {
            $database->rollBack();
            return false;
        }

        $answer = $database->prepare(
            'INSERT INTO reponses_joueurs
                (id_partie, id_question, id_reponse, est_correcte, points_obtenus)
             SELECT ?, r.id_question, r.id_reponse, r.est_correcte,
                    IF(r.est_correcte = 1, d.multiplicateur, 0)
             FROM reponses r
             JOIN questions q ON q.id_question = r.id_question
             JOIN difficultes d ON d.id_difficulte = q.id_difficulte
             WHERE r.id_question = ? AND r.id_reponse = ?'
        );
        $answer->execute([$gameId, $questionId, $answerId]);
        if ($answer->rowCount() !== 1) {
            $database->rollBack();
            return false;
        }

        $update = $database->prepare(
            "UPDATE parties p
             JOIN reponses_joueurs r ON r.id_partie = p.id_partie AND r.id_question = ?
             SET p.score_total = p.score_total + r.points_obtenus,
                 p.nombre_bonnes_reponses = p.nombre_bonnes_reponses + r.est_correcte,
                 p.statut = IF(?, 'terminee', 'en_cours'),
                 p.date_fin = IF(?, NOW(), NULL)
             WHERE p.id_partie = ?"
        );
        $update->execute([$questionId, (int) $finished, (int) $finished, $gameId]);
        $database->commit();
        return true;
    } catch (Throwable $exception) {
        $database->rollBack();
        throw $exception;
    }
}

function quiz_game_details(PDO $database, int $gameId, ?int $questionId = null): array
{
    $sql = 'SELECT q.enonce, q.explication, choisie.texte_reponse AS choix,
                   correcte.texte_reponse AS bonne_reponse, r.est_correcte, r.points_obtenus
            FROM reponses_joueurs r
            JOIN questions q ON q.id_question = r.id_question
            LEFT JOIN reponses choisie ON choisie.id_reponse = r.id_reponse
            LEFT JOIN reponses correcte ON correcte.id_question = q.id_question AND correcte.est_correcte = 1
            WHERE r.id_partie = ?';
    $params = [$gameId];
    if ($questionId !== null) {
        $sql .= ' AND r.id_question = ?';
        $params[] = $questionId;
    }
    $statement = $database->prepare($sql . ' ORDER BY r.id_reponse_joueur');
    $statement->execute($params);
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function quiz_game_points(string $value): string
{
    return str_replace('.', ',', str_contains($value, '.') ? rtrim(rtrim($value, '0'), '.') : $value);
}
