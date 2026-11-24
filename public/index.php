<?php
declare(strict_types=1);

require __DIR__ . '/../src/session.php';

quiz_redirect(isset($_SESSION['user_id']) ? 'accueil.php' : 'connexion.php');
