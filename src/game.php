<?php
declare(strict_types=1);

const QUIZ_PLAYABLE_QUESTION = "q.statut_validation = 'validee'
    AND EXISTS (SELECT 1 FROM reponses r WHERE r.id_question = q.id_question
                GROUP BY r.id_question HAVING COUNT(*) = 4 AND SUM(r.est_correcte = 1) = 1)";
const QUIZ_LEVELS = [1 => 'Facile', 2 => 'Moyen', 3 => 'Difficile'];

function quiz_themes(PDO $database): array
{
    return $database->query(
        "SELECT id_categorie, nom_categorie, description FROM categories
         WHERE nom_categorie IN ('Géographie', 'Sciences') ORDER BY nom_categorie"
    )->fetchAll(PDO::FETCH_ASSOC);
}

function quiz_game_limit(): int
{
    return filter_var(getenv('QUIZ_QUESTION_LIMIT'), FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1, 'max_range' => 10],
    ]) ?: 10;
}

function quiz_game_theme(PDO $database, int $themeId): array|false
{
    $statement = $database->prepare('SELECT nom_categorie FROM categories WHERE id_categorie = ?');
    $statement->execute([$themeId]);
    return $statement->fetch(PDO::FETCH_ASSOC);
}

function quiz_game_questions(PDO $database, int $themeId, int $level): array
{
    $statement = $database->prepare(
        'SELECT q.id_question FROM questions q
         WHERE q.id_categorie = :theme AND q.id_difficulte = :niveau AND ' . QUIZ_PLAYABLE_QUESTION . '
         ORDER BY RAND() LIMIT :limite'
    );
    $statement->bindValue('theme', $themeId, PDO::PARAM_INT);
    $statement->bindValue('niveau', $level, PDO::PARAM_INT);
    $statement->bindValue('limite', quiz_game_limit(), PDO::PARAM_INT);
    $statement->execute();
    return array_map('intval', $statement->fetchAll(PDO::FETCH_COLUMN));
}

function quiz_game_abandon(PDO $database, int $userId, int $gameId): void
{
    $statement = $database->prepare(
        "UPDATE parties SET statut = 'abandonnee', date_fin = NOW()
         WHERE id_partie = ? AND id_utilisateur = ? AND statut = 'en_cours'"
    );
    $statement->execute([$gameId, $userId]);
}

function quiz_game_start(PDO $database, int $userId, int $themeId, int $level, array $questions): void
{
    $database->beginTransaction();
    try {
        if (isset($_SESSION['quiz_game'])) {
            quiz_game_abandon($database, $userId, $_SESSION['quiz_game']['id']);
        }
        $statement = $database->prepare('INSERT INTO parties (id_utilisateur) VALUES (?)');
        $statement->execute([$userId]);
        $gameId = (int) $database->lastInsertId();
        $database->commit();
    } catch (Throwable $exception) {
        $database->rollBack();
        throw $exception;
    }

    $_SESSION['quiz_game'] = ['id' => $gameId, 'theme_id' => $themeId, 'level' => $level, 'questions' => $questions];
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

function quiz_game_results(PDO $database, int $userId, ?int $gameId = null): array
{
    $sql = "SELECT p.id_partie, p.score_total, p.nombre_bonnes_reponses, p.date_fin,
                   COUNT(r.id_question) AS total, MIN(c.id_categorie) AS theme_id, MIN(c.nom_categorie) AS theme
            FROM parties p
            LEFT JOIN reponses_joueurs r ON r.id_partie = p.id_partie
            LEFT JOIN questions q ON q.id_question = r.id_question
            LEFT JOIN categories c ON c.id_categorie = q.id_categorie
            WHERE p.id_utilisateur = ? AND p.statut = 'terminee'";
    $params = [$userId];
    if ($gameId !== null) {
        $sql .= ' AND p.id_partie = ?';
        $params[] = $gameId;
    }
    $statement = $database->prepare($sql . ' GROUP BY p.id_partie ORDER BY p.date_fin DESC, p.id_partie DESC');
    $statement->execute($params);
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function quiz_success_rate(int $correct, int $total): string
{
    return number_format($total ? 100 * $correct / $total : 0, 0, ',', ' ') . ' %';
}

function quiz_ranking(PDO $database, int $themeId): array
{
    $statement = $database->prepare(
        "WITH eligibles AS (
             SELECT p.id_utilisateur, p.nombre_bonnes_reponses
             FROM parties p
             JOIN reponses_joueurs r ON r.id_partie = p.id_partie
             JOIN questions q ON q.id_question = r.id_question
             WHERE p.statut = 'terminee' AND p.id_utilisateur IS NOT NULL
             GROUP BY p.id_partie
             HAVING COUNT(*) = 10 AND MIN(q.id_categorie) = ? AND MAX(q.id_categorie) = ?
         ), meilleurs AS (
             SELECT id_utilisateur, MAX(nombre_bonnes_reponses) AS bonnes_reponses
             FROM eligibles GROUP BY id_utilisateur
         )
         SELECT RANK() OVER (ORDER BY m.bonnes_reponses DESC) AS rang, u.pseudo, m.bonnes_reponses
         FROM meilleurs m JOIN utilisateurs u ON u.id_utilisateur = m.id_utilisateur
         ORDER BY rang, u.pseudo, u.id_utilisateur"
    );
    $statement->execute([$themeId, $themeId]);
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}
