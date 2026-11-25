<?php
$links = [
    'accueil.php' => ['⌂', 'Accueil'],
    'themes.php' => ['◇', 'Thèmes'],
    'classement.php' => ['♜', 'Classement'],
    'profil.php' => ['◎', 'Mon compte'],
];
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
    <div class="declic-user">
        <?php if ($hasPhoto): ?>
            <img class="declic-avatar" src="photo-compte.php" alt="">
        <?php else: ?>
            <span class="declic-initial" aria-hidden="true"><?= quiz_escape(quiz_avatar_initial($pseudo)) ?></span>
        <?php endif; ?>
        <span><?= quiz_escape($pseudo) ?></span>
    </div>
</aside>
