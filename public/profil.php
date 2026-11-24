<?php
declare(strict_types=1);

require __DIR__ . '/../src/session.php';
require __DIR__ . '/../src/database.php';
$userId = quiz_require_user();
$error = '';
$saved = isset($_SESSION['profile_saved']);
unset($_SESSION['profile_saved']);
$pseudo = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    quiz_require_post_token();
}
try {
    $database = quiz_database();
    $statement = $database->prepare('SELECT pseudo FROM utilisateurs WHERE id_utilisateur = ?');
    $statement->execute([$userId]);
    $pseudo = $statement->fetchColumn();
    if ($pseudo === false) {
        $_SESSION = [];
        quiz_redirect('connexion.php');
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $pseudo = trim(quiz_input('pseudo'));
        if ($pseudo === '' || !mb_check_encoding($pseudo) || mb_strlen($pseudo) > 50) {
            $error = 'Saisissez un pseudo de 1 à 50 caractères.';
        } else {
            $statement = $database->prepare('UPDATE utilisateurs SET pseudo = ? WHERE id_utilisateur = ?');
            $statement->execute([$pseudo, $userId]);
            $_SESSION['pseudo'] = $pseudo;
            $_SESSION['profile_saved'] = true;
            quiz_redirect('profil.php');
        }
    }
} catch (PDOException $exception) {
    if (($exception->errorInfo[1] ?? null) === 1062) {
        $error = 'Ce pseudo est déjà utilisé.';
    } else {
        error_log($exception->getMessage());
        http_response_code(503);
        $error = 'Le profil est temporairement indisponible.';
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mon profil · Quiz de culture générale</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="account-panel theme-panel profile-layout">
        <aside>
            <nav aria-label="Menu du profil">
                <a href="accueil.php">Accueil</a>
                <a href="profil.php" aria-current="page">Mon profil</a>
                <a href="performances.php">Mes performances</a>
                <a href="classement.php">Classement</a>
            </nav>
            <form action="deconnexion.php" method="post">
                <input type="hidden" name="csrf_token" value="<?= quiz_csrf_token() ?>">
                <button class="btn" type="submit">Se déconnecter</button>
            </form>
        </aside>
        <main>
            <h1>Mon profil</h1>
            <?php if ($error !== ''): ?>
                <p role="alert"><?= quiz_escape($error) ?></p>
            <?php elseif ($saved): ?>
                <p role="status">Pseudo enregistré.</p>
            <?php endif; ?>
            <?php if (http_response_code() !== 503): ?>
                <form method="post">
                    <input type="hidden" name="csrf_token" value="<?= quiz_csrf_token() ?>">
                    <label>Pseudo public
                        <input name="pseudo" value="<?= quiz_escape($pseudo) ?>" maxlength="50" required>
                    </label>
                    <p>Ce nom apparaît dans le classement.</p>
                    <button class="btn btn-primary" type="submit">Enregistrer</button>
                </form>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
