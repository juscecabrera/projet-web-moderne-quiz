<?php
if (!isset($registration)) {
    http_response_code(404);
    exit;
}
$title = $registration ? 'Inscription' : 'Connexion';
$illustration = $registration ? 'inscription' : 'connexion';
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?> · Déclic</title>
    <link rel="stylesheet" href="assets/auth.css">
</head>
<body>
    <main class="auth-layout">
        <div class="auth-panel">
            <div class="auth-brand"><span aria-hidden="true">✦</span> DÉCLIC</div>
            <div class="auth-content">
                <h1><?= $title ?></h1>
                <?php if ($error !== ''): ?>
                    <p class="auth-error" role="alert"><?= quiz_escape($error) ?></p>
                <?php endif; ?>
                <form method="post">
                    <input type="hidden" name="csrf_token" value="<?= quiz_csrf_token() ?>">
                    <?php if ($registration): ?>
                        <div class="auth-field">
                            <label for="public-name">Pseudo public</label>
                            <input id="public-name" name="pseudo" autocomplete="nickname" maxlength="50"
                                   aria-describedby="public-name-help" value="<?= quiz_escape($pseudo) ?>" required>
                            <small id="public-name-help">Ce nom apparaît dans le classement.</small>
                        </div>
                    <?php endif; ?>
                    <label>Adresse e-mail
                        <input name="email" type="email" autocomplete="username" maxlength="150" value="<?= quiz_escape($email) ?>" required>
                    </label>
                    <label>Mot de passe
                        <input name="password" type="password" autocomplete="<?= $registration ? 'new-password' : 'current-password' ?>" <?= $registration ? 'minlength="8"' : '' ?> required>
                    </label>
                    <?php if ($registration): ?>
                        <label>Confirmation du mot de passe
                            <input name="password_confirmation" type="password" autocomplete="new-password" required>
                        </label>
                    <?php endif; ?>
                    <button type="submit"><?= $registration ? 'Créer mon compte' : 'Se connecter' ?></button>
                </form>
                <p>
                    <?php if ($registration): ?>
                        Déjà un compte ? <a href="connexion.php">Se connecter</a>
                    <?php else: ?>
                        Pas encore de compte ? <a href="inscription.php">Créer un compte</a>
                    <?php endif; ?>
                </p>
            </div>
            <div class="auth-contours" aria-hidden="true">
                <span></span><span></span><span></span><span></span><span></span>
            </div>
        </div>
        <div class="auth-art" aria-hidden="true">
            <img src="assets/images/<?= $illustration ?>.webp" alt="" fetchpriority="high">
        </div>
    </main>
</body>
</html>
