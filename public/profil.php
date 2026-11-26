<?php
declare(strict_types=1);

require __DIR__ . '/../src/session.php';
require __DIR__ . '/../src/database.php';
require __DIR__ . '/../src/layout.php';
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
    $pseudo = (string) $statement->fetchColumn();
    if ($pseudo === '') {
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

$initial = $pseudo !== '' ? mb_strtoupper(mb_substr($pseudo, 0, 1)) : '?';

quiz_layout_start('Mon profil', 'profil', true);
?>
<div class="page-head">
    <div>
        <p class="eyebrow">Mon compte</p>
        <h1>Mon profil</h1>
        <p class="lead">Le pseudo choisi ici apparaît publiquement dans le classement.</p>
    </div>
</div>

<div class="card card--narrow">
    <div class="profile-head">
        <span class="profile-avatar" aria-hidden="true"><?= quiz_escape($initial) ?></span>
        <div>
            <h1><?= quiz_escape($pseudo) ?></h1>
            <p>Joueur connecté</p>
        </div>
    </div>

    <?php quiz_alert($error); ?>
    <?php if ($error === '' && $saved): ?>
        <?php quiz_alert('Pseudo enregistré avec succès.', 'success'); ?>
    <?php endif; ?>

    <?php if (http_response_code() !== 503): ?>
        <form method="post">
            <input type="hidden" name="csrf_token" value="<?= quiz_csrf_token() ?>">
            <div class="field">
                <label for="pseudo">Pseudo public</label>
                <input id="pseudo" name="pseudo" type="text" value="<?= quiz_escape($pseudo) ?>" maxlength="50" autocomplete="nickname" required>
                <p class="field-hint">Entre 1 et 50 caractères. Ce nom est visible par les autres joueurs.</p>
            </div>
            <button class="btn btn-primary btn-block" type="submit">Enregistrer</button>
        </form>
    <?php endif; ?>
</div>
<?php
quiz_layout_end();
