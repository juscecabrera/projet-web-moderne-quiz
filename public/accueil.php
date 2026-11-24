<?php
declare(strict_types=1);

require __DIR__ . '/../src/session.php';
quiz_require_user();
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Accueil · Quiz de culture générale</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <main class="account-panel">
        <h1>Bonjour, <?= quiz_escape($_SESSION['pseudo']) ?></h1>
        <p><a class="btn btn-primary" href="themes.php">Jouer</a></p>
        <p><a href="performances.php">Mes performances</a></p>
        <p><a href="classement.php">Classement</a></p>
        <p><a href="profil.php">Mon profil</a></p>
        <form action="deconnexion.php" method="post">
            <input type="hidden" name="csrf_token" value="<?= quiz_csrf_token() ?>">
            <button class="btn" type="submit">Se déconnecter</button>
        </form>
    </main>
</body>
</html>
