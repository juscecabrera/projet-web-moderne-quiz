<?php
declare(strict_types=1);

require __DIR__ . '/../src/session.php';
require __DIR__ . '/../src/database.php';

if (isset($_SESSION['user_id'])) {
    quiz_redirect('accueil.php');
}

$error = $email = '';
$registration = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    quiz_require_post_token();
    $email = trim(quiz_input('email'));
    $password = quiz_input('password');
    $error = $email === '' || $password === ''
        ? 'Remplissez tous les champs.'
        : 'Adresse e-mail ou mot de passe incorrect.';

    if (strlen($email) <= 150 && filter_var($email, FILTER_VALIDATE_EMAIL) &&
        $password !== '' && strlen($password) <= 72 && !str_contains($password, "\0")) {
        try {
            $statement = quiz_database()->prepare(
                'SELECT id_utilisateur, pseudo, mot_de_passe_hash FROM utilisateurs WHERE email = ?'
            );
            $statement->execute([$email]);
            $user = $statement->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['mot_de_passe_hash'])) {
                quiz_sign_in((int) $user['id_utilisateur'], $user['pseudo']);
            }
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            http_response_code(503);
            $error = 'Connexion indisponible. Réessayez dans un instant.';
        }
    }
}

require __DIR__ . '/../src/auth-form.php';
