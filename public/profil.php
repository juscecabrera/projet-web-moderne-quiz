<?php
declare(strict_types=1);

require __DIR__ . '/../src/session.php';
require __DIR__ . '/../src/database.php';
require __DIR__ . '/../src/account-photo.php';

$userId = quiz_require_user();
$activePage = 'profil.php';
$pseudo = $_SESSION['pseudo'];
$hasPhoto = false;
$account = null;
$error = '';
$notice = $_SESSION['account_notice'] ?? '';
unset($_SESSION['account_notice']);
$action = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    quiz_require_post_token();
    $action = quiz_input('action');
}

try {
    $database = quiz_database();
    $statement = $database->prepare('SELECT pseudo, email, mot_de_passe_hash FROM utilisateurs WHERE id_utilisateur = ?');
    $statement->execute([$userId]);
    $account = $statement->fetch(PDO::FETCH_ASSOC);
    if (!$account) {
        quiz_sign_out();
    }
    $pseudo = $account['pseudo'];
    $_SESSION['pseudo'] = $pseudo;
    $hasPhoto = quiz_photo_for_user($database, $userId) !== null;

    if (in_array($action, ['email', 'password', 'delete'], true) &&
        !password_verify(quiz_input('current_password'), $account['mot_de_passe_hash'])) {
        throw new DomainException('Le mot de passe actuel est incorrect.');
    }

    switch ($action) {
        case '':
            break;

        case 'pseudo':
            $newPseudo = trim(quiz_input('pseudo'));
            if ($newPseudo === '' || !mb_check_encoding($newPseudo) || mb_strlen($newPseudo) > 50) {
                throw new DomainException('Saisis un pseudo de 1 à 50 caractères.');
            }
            $statement = $database->prepare('UPDATE utilisateurs SET pseudo = ? WHERE id_utilisateur = ?');
            $statement->execute([$newPseudo, $userId]);
            $_SESSION['pseudo'] = $newPseudo;
            $notice = 'Pseudo enregistré.';
            break;

        case 'photo':
            $photo = quiz_uploaded_avatar($_FILES['avatar'] ?? null);
            $statement = $database->prepare(
                'INSERT INTO photos_profils (id_utilisateur, image_webp) VALUES (?, ?)
                 ON DUPLICATE KEY UPDATE image_webp = ?'
            );
            $statement->execute([$userId, $photo, $photo]);
            $notice = 'Photo enregistrée.';
            break;

        case 'remove-photo':
            $statement = $database->prepare('DELETE FROM photos_profils WHERE id_utilisateur = ?');
            $statement->execute([$userId]);
            $notice = 'Photo retirée.';
            break;

        case 'email':
            $email = trim(quiz_input('email'));
            if (strlen($email) > 150 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new DomainException('Saisis une adresse e-mail valide.');
            }
            $statement = $database->prepare('UPDATE utilisateurs SET email = ? WHERE id_utilisateur = ?');
            $statement->execute([$email, $userId]);
            $notice = 'Adresse e-mail modifiée.';
            break;

        case 'password':
            $password = quiz_input('new_password');
            if ($password !== quiz_input('confirm_password')) {
                throw new DomainException('Les nouveaux mots de passe ne correspondent pas.');
            }
            if (mb_strlen($password) < 8 || strlen($password) > 72 || str_contains($password, "\0")) {
                throw new DomainException('Saisis un mot de passe de 8 caractères minimum et de 72 octets maximum.');
            }
            $statement = $database->prepare('UPDATE utilisateurs SET mot_de_passe_hash = ? WHERE id_utilisateur = ?');
            $statement->execute([password_hash($password, PASSWORD_DEFAULT), $userId]);
            session_regenerate_id(true);
            $notice = 'Mot de passe modifié.';
            break;

        case 'delete':
            if (quiz_input('confirm_delete') !== 'yes') {
                throw new DomainException('Confirme la suppression de ton compte.');
            }
            $database->beginTransaction();
            try {
                $statement = $database->prepare('DELETE FROM parties WHERE id_utilisateur = ?');
                $statement->execute([$userId]);
                $statement = $database->prepare('DELETE FROM utilisateurs WHERE id_utilisateur = ?');
                $statement->execute([$userId]);
                $database->commit();
            } catch (Throwable $exception) {
                $database->rollBack();
                throw $exception;
            }
            quiz_sign_out();

        default:
            http_response_code(400);
            throw new DomainException('Action inconnue.');
    }

    if ($action !== '') {
        $_SESSION['account_notice'] = $notice;
        quiz_redirect('profil.php');
    }
} catch (DomainException $exception) {
    $error = $exception->getMessage();
} catch (RuntimeException $exception) {
    $code = $exception instanceof PDOException ? ($exception->errorInfo[1] ?? null) : null;
    if ($code === 1062) {
        $error = $action === 'email' ? 'Cette adresse e-mail est déjà utilisée.' : 'Ce pseudo est déjà utilisé.';
    } elseif ($code === 1146 && in_array($action, ['photo', 'remove-photo'], true)) {
        $error = 'Les photos seront disponibles après la mise à jour de la base de données.';
    } else {
        error_log($exception->getMessage());
        http_response_code(503);
        $error = 'Cette action est temporairement indisponible.';
    }
}
$token = quiz_csrf_token();
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mon compte · Déclic</title>
    <link rel="stylesheet" href="assets/declic.css">
</head>
<body class="declic-layout">
    <?php require __DIR__ . '/../src/declic-navigation.php'; ?>
    <main id="contenu" class="declic-account">
        <header class="account-hero"><h1>Mon compte</h1></header>
        <?php if ($error !== ''): ?>
            <p class="account-message account-error" role="alert"><?= quiz_escape($error) ?></p>
        <?php elseif ($notice !== ''): ?>
            <p class="account-message" role="status"><?= quiz_escape($notice) ?></p>
        <?php endif; ?>
        <?php if ($account): ?>
            <div class="account-grid">
                <section class="account-card">
                    <h2>Profil public</h2>
                    <div class="account-avatar-preview">
                        <?php if ($hasPhoto): ?>
                            <img class="declic-avatar" src="photo-compte.php" alt="Ta photo de profil">
                        <?php else: ?>
                            <span class="declic-initial" aria-hidden="true"><?= quiz_escape(quiz_avatar_initial($pseudo)) ?></span>
                        <?php endif; ?>
                        <details <?= in_array($action, ['photo', 'remove-photo'], true) ? 'open' : '' ?>>
                            <summary><?= $hasPhoto ? 'Modifier la photo' : 'Ajouter une photo' ?></summary>
                            <form method="post" enctype="multipart/form-data">
                                <input type="hidden" name="csrf_token" value="<?= $token ?>">
                                <label>Photo de profil (JPEG, PNG ou WebP, 3 Mo max.)
                                    <input type="file" name="avatar" accept="image/jpeg,image/png,image/webp" required>
                                </label>
                                <button name="action" value="photo">Enregistrer la photo</button>
                            </form>
                            <?php if ($hasPhoto): ?>
                                <form method="post">
                                    <input type="hidden" name="csrf_token" value="<?= $token ?>">
                                    <button class="account-outline" name="action" value="remove-photo">Retirer la photo</button>
                                </form>
                            <?php endif; ?>
                        </details>
                    </div>
                    <form method="post">
                        <input type="hidden" name="csrf_token" value="<?= $token ?>">
                        <label>Pseudo public
                            <input name="pseudo" value="<?= quiz_escape($pseudo) ?>" maxlength="50" autocomplete="nickname" required>
                        </label>
                        <button name="action" value="pseudo">Enregistrer le pseudo</button>
                    </form>
                </section>
                <section class="account-card">
                    <h2>Identifiants de connexion</h2>
                    <form method="post">
                        <input type="hidden" name="csrf_token" value="<?= $token ?>">
                        <label>Adresse e-mail
                            <input name="email" type="email" autocomplete="username" maxlength="150" value="<?= quiz_escape($account['email']) ?>" required>
                        </label>
                        <label>Mot de passe actuel pour modifier l’e-mail
                            <input name="current_password" type="password" autocomplete="current-password" required>
                        </label>
                        <button name="action" value="email">Modifier mon e-mail</button>
                    </form>
                </section>
                <section class="account-card">
                    <h2>Mot de passe</h2>
                    <form method="post">
                        <input type="hidden" name="csrf_token" value="<?= $token ?>">
                        <label>Mot de passe actuel
                            <input name="current_password" type="password" autocomplete="current-password" required>
                        </label>
                        <label>Nouveau mot de passe
                            <input name="new_password" type="password" autocomplete="new-password" minlength="8" required>
                        </label>
                        <label>Confirmer le nouveau mot de passe
                            <input name="confirm_password" type="password" autocomplete="new-password" minlength="8" required>
                        </label>
                        <button name="action" value="password">Changer le mot de passe</button>
                    </form>
                </section>
                <section class="account-card">
                    <h2>Sécurité du compte</h2>
                    <form method="post" action="deconnexion.php">
                        <input type="hidden" name="csrf_token" value="<?= $token ?>">
                        <button class="account-outline">Se déconnecter</button>
                    </form>
                    <details class="account-danger" <?= $action === 'delete' ? 'open' : '' ?>>
                        <summary>Supprimer mon compte</summary>
                        <p>Cette action est définitive. Ton compte, ta photo et tes parties seront supprimés.</p>
                        <form method="post">
                            <input type="hidden" name="csrf_token" value="<?= $token ?>">
                            <label>Mot de passe actuel pour la suppression
                                <input name="current_password" type="password" autocomplete="current-password" required>
                            </label>
                            <label class="account-check"><input type="checkbox" name="confirm_delete" value="yes" required> Je confirme la suppression.</label>
                            <button class="account-outline" name="action" value="delete">Supprimer définitivement</button>
                        </form>
                    </details>
                </section>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
