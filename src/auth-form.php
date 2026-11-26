<?php
if (!isset($registration)) {
    http_response_code(404);
    exit;
}
require __DIR__ . '/layout.php';
$title = $registration ? 'Inscription' : 'Connexion';
quiz_layout_start($title, null, false, 'page--narrow');
?>
<section class="auth-card">
    <p class="eyebrow"><?= $registration ? 'Rejoignez le jeu' : 'Bon retour parmi nous' ?></p>
    <h1><?= $registration ? 'Créer un compte' : 'Se connecter' ?></h1>
    <p class="auth-intro">
        <?= $registration
            ? 'Quelques secondes suffisent pour commencer à jouer et enregistrer vos scores.'
            : 'Retrouvez vos parties, vos performances et votre place dans le classement.' ?>
    </p>

    <?php quiz_alert($error); ?>

    <form method="post">
        <input type="hidden" name="csrf_token" value="<?= quiz_csrf_token() ?>">
        <?php if ($registration): ?>
            <div class="field">
                <label for="pseudo">Pseudo</label>
                <input id="pseudo" name="pseudo" type="text" autocomplete="nickname" maxlength="50" value="<?= quiz_escape($pseudo) ?>" placeholder="Votre pseudo de joueur" required>
            </div>
        <?php endif; ?>
        <div class="field">
            <label for="email">Adresse e-mail</label>
            <input id="email" name="email" type="email" autocomplete="email" maxlength="150" value="<?= quiz_escape($email) ?>" placeholder="vous@exemple.fr" required>
        </div>
        <div class="field">
            <label for="password">Mot de passe</label>
            <input id="password" name="password" type="password"
                autocomplete="<?= $registration ? 'new-password' : 'current-password' ?>"
                <?= $registration ? 'minlength="8"' : '' ?>
                placeholder="<?= $registration ? '8 caractères minimum' : 'Votre mot de passe' ?>" required>
        </div>
        <?php if ($registration): ?>
            <div class="field">
                <label for="password_confirmation">Confirmation du mot de passe</label>
                <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" placeholder="Répétez le mot de passe" required>
            </div>
        <?php endif; ?>
        <button class="btn btn-primary btn-lg btn-block" type="submit">
            <?= $registration ? 'Créer mon compte' : 'Se connecter' ?>
        </button>
    </form>

    <p class="auth-switch"><?= $registration ? 'Vous avez déjà un compte ?' : 'Pas encore de compte ?' ?></p>
    <a class="btn btn-secondary btn-block" href="<?= $registration ? 'connexion.php' : 'inscription.php' ?>">
        <?= $registration ? 'Se connecter' : 'Créer un compte' ?>
    </a>
</section>
<?php
quiz_layout_end();
