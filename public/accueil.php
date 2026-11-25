<?php
declare(strict_types=1);

require __DIR__ . '/../src/session.php';
require __DIR__ . '/../src/database.php';
require __DIR__ . '/../src/account-photo.php';
$userId = quiz_require_user();

$pseudo = $_SESSION['pseudo'];
$hasPhoto = false;
try {
    $hasPhoto = quiz_photo_for_user(quiz_database(), $userId) !== null;
} catch (PDOException $exception) {
    error_log($exception->getMessage());
}
$activePage = 'accueil.php';
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Accueil · Déclic</title>
    <link rel="stylesheet" href="assets/declic.css">
</head>
<body class="declic-layout">
    <?php require __DIR__ . '/../src/declic-navigation.php'; ?>

    <main id="contenu">
        <section class="home-hero" aria-labelledby="welcome-title">
            <img class="home-hero-image" src="assets/images/accueil.webp" alt="" fetchpriority="high">
            <div class="home-hero-content">
                <p class="home-greeting">Bonjour, <?= quiz_escape($pseudo) ?>.</p>
                <h1 id="welcome-title">À toi de jouer.</h1>
                <p>Choisis un thème et relève le défi.</p>
                <a class="home-play" href="themes.php">Jouer <span aria-hidden="true">→</span></a>
            </div>
        </section>

        <div class="home-body">
            <div class="home-shortcuts">
                <a class="home-card" href="classement.php">
                    <img src="assets/images/accueil-classement.webp" alt="">
                    <div>
                        <h2>Classement</h2>
                        <p>Compare tes points cumulés par thème.</p>
                        <span class="home-arrow" aria-hidden="true">→</span>
                    </div>
                </a>
                <a class="home-card" href="profil.php">
                    <img src="assets/images/accueil-profil.webp" alt="">
                    <div>
                        <h2>Mon compte</h2>
                        <p>Retrouve ton compte et ton pseudo public.</p>
                        <span class="home-arrow" aria-hidden="true">→</span>
                    </div>
                </a>
            </div>

            <section class="home-about" aria-labelledby="about-title">
                <h2 id="about-title">Pourquoi Déclic ?</h2>
                <p>Déclic, c’est le quiz de culture générale qui te fait apprendre en jouant.</p>
                <div class="home-features">
                    <div>
                        <span aria-hidden="true">✧</span>
                        <h3>Explore les thèmes</h3>
                        <p>Passe d’un sujet à l’autre selon ton envie.</p>
                    </div>
                    <div>
                        <span aria-hidden="true">◇</span>
                        <h3>Joue à ton niveau</h3>
                        <p>Choisis Facile, Moyen ou Difficile et découvre la correction après chaque réponse.</p>
                    </div>
                    <div>
                        <span aria-hidden="true">↗</span>
                        <h3>Cumule tes points</h3>
                        <p>Chaque partie terminée compte dans le classement de son thème.</p>
                    </div>
                </div>
            </section>
        </div>
    </main>
</body>
</html>
