<?php
if (!isset($registration)) {
    http_response_code(404);
    exit;
}
$title = $registration ? 'Inscription' : 'Connexion';
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?> · Quiz de culture générale</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/auth.css">
</head>
<body>
    <main class="account-panel">
        <h1><?= $title ?></h1>
        <?php if ($accountCreated ?? false): ?>
            <p role="status">Compte créé. Vous pouvez vous connecter.</p>
        <?php endif; ?>
        <?php if ($error !== ''): ?>
            <p role="alert"><?= quiz_escape($error) ?></p>
        <?php endif; ?>
        <form method="post">
            <input type="hidden" name="csrf_token" value="<?= quiz_csrf_token() ?>">
            <?php if ($registration): ?>
                <label>Pseudo
                    <input name="pseudo" autocomplete="nickname" maxlength="50" value="<?= quiz_escape($pseudo) ?>" required>
                </label>
            <?php endif; ?>
            <label>Adresse e-mail
                <input name="email" type="email" autocomplete="email" maxlength="150" value="<?= quiz_escape($email) ?>" required>
            </label>
            <label>Mot de passe
                <input name="password" type="password" autocomplete="<?= $registration ? 'new-password' : 'current-password' ?>" <?= $registration ? 'minlength="8"' : '' ?> required>
            </label>
            <button class="btn" type="submit"><?= $registration ? 'Créer mon compte' : 'Se connecter' ?></button>
        </form>
        <p>
            <?php if ($registration): ?>
                <a href="connexion.php">Se connecter</a>
            <?php else: ?>
                <a href="inscription.php">Créer un compte</a>
            <?php endif; ?>
        </p>
    </main>
</body>
</html>
