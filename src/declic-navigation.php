<?php
$links = [
    'accueil.php' => ['⌂', 'Accueil'],
    'themes.php' => ['◇', 'Thèmes'],
    'classement.php' => ['♜', 'Classement'],
    'profil.php' => ['◎', 'Mon profil'],
];
$initial = mb_strtoupper(mb_substr($pseudo, 0, 1, 'UTF-8'), 'UTF-8');
?>
<a class="declic-skip" href="#contenu">Aller au contenu</a>
<aside class="declic-rail">
    <a class="declic-brand" href="accueil.php"><span aria-hidden="true"></span>DÉCLIC</a>
    <nav aria-label="Navigation principale">
        <?php foreach ($links as $url => [$icon, $label]): ?>
            <a href="<?= $url ?>" <?= $url === $activePage ? 'aria-current="page"' : '' ?>>
                <span class="declic-icon" aria-hidden="true"><?= $icon ?></span><?= $label ?>
            </a>
        <?php endforeach; ?>
    </nav>
    <a class="declic-user" href="profil.php">
        <span class="declic-initial" aria-hidden="true"><?= quiz_escape($initial) ?></span>
        <span><?= quiz_escape($pseudo) ?></span>
    </a>
</aside>
