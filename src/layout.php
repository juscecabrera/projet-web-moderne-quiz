<?php
declare(strict_types=1);

/**
 * Ouvre une page complète avec l'en-tête commun.
 *
 * @param string      $title         Titre affiché dans l'onglet.
 * @param string|null $active        Clé du lien de navigation actif.
 * @param bool        $authenticated Affiche la navigation du joueur connecté.
 * @param string      $pageClass     Classe supplémentaire pour <main>.
 */
function quiz_layout_start(
    string $title,
    ?string $active = null,
    bool $authenticated = false,
    string $pageClass = ''
): void {
    $pseudo = $authenticated ? (string) ($_SESSION['pseudo'] ?? '') : '';
    $initial = $pseudo !== '' ? mb_strtoupper(mb_substr($pseudo, 0, 1)) : '?';
    $nav = [
        'accueil' => ['accueil.php', 'Accueil'],
        'themes' => ['themes.php', 'Jouer'],
        'performances' => ['performances.php', 'Performances'],
        'classement' => ['classement.php', 'Classement'],
        'profil' => ['profil.php', 'Profil'],
    ];
    ?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#6c5ce7">
    <title><?= quiz_escape($title) ?> · Quiz de culture générale</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="<?= $authenticated ? 'is-authenticated' : 'is-guest' ?>">
    <header class="topbar">
        <div class="topbar__inner">
            <a class="brand" href="<?= $authenticated ? 'accueil.php' : 'connexion.php' ?>" aria-label="Quiz de culture générale — accueil">
                <span class="brand__mark" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9.1 9a3 3 0 1 1 5 2.2c-.8.7-1.6 1.2-1.6 2.3"/>
                        <path d="M12.5 17.2h.01"/>
                        <circle cx="12" cy="12" r="10"/>
                    </svg>
                </span>
                <span class="brand__name">Quiz<span>Culture</span></span>
            </a>
            <?php if ($authenticated): ?>
                <nav class="nav" aria-label="Navigation principale">
                    <?php foreach ($nav as $key => [$href, $label]): ?>
                        <a class="nav__link<?= $active === $key ? ' is-active' : '' ?>"
                           href="<?= quiz_escape($href) ?>"<?= $active === $key ? ' aria-current="page"' : '' ?>>
                            <?= quiz_escape($label) ?>
                        </a>
                    <?php endforeach; ?>
                </nav>
                <div class="topbar__actions">
                    <span class="user-chip" title="Connecté en tant que <?= quiz_escape($pseudo) ?>">
                        <span class="user-chip__avatar" aria-hidden="true"><?= quiz_escape($initial) ?></span>
                        <span class="user-chip__name"><?= quiz_escape($pseudo) ?></span>
                    </span>
                    <form action="deconnexion.php" method="post">
                        <input type="hidden" name="csrf_token" value="<?= quiz_csrf_token() ?>">
                        <button class="btn btn-ghost btn-sm" type="submit">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                <path d="M16 17l5-5-5-5"/>
                                <path d="M21 12H9"/>
                            </svg>
                            <span>Déconnexion</span>
                        </button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </header>
    <main class="page <?= quiz_escape($pageClass) ?>">
<?php
}

/**
 * Ferme la page ouverte par quiz_layout_start().
 */
function quiz_layout_end(): void
{
    ?>
    </main>
    <footer class="site-footer">
        <p>Quiz de culture générale · Projet web moderne</p>
    </footer>
</body>
</html>
<?php
}

/**
 * Affiche un message d'alerte accessible.
 */
function quiz_alert(string $message, string $type = 'error'): void
{
    if ($message === '') {
        return;
    }
    $icons = [
        'error' => '<path d="M12 8v5"/><path d="M12 16.5h.01"/><circle cx="12" cy="12" r="10"/>',
        'success' => '<path d="M20 6 9 17l-5-5"/>',
        'info' => '<path d="M12 16v-5"/><path d="M12 8h.01"/><circle cx="12" cy="12" r="10"/>',
    ];
    ?>
    <div class="alert alert--<?= quiz_escape($type) ?>" role="<?= $type === 'error' ? 'alert' : 'status' ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <?= $icons[$type] ?? $icons['info'] ?>
        </svg>
        <span><?= quiz_escape($message) ?></span>
    </div>
    <?php
}
