<?php
declare(strict_types=1);

require __DIR__ . '/includes/session.php';
require __DIR__ . '/includes/database.php';

if (isset($_SESSION['user_id'])) {
    quiz_redirect('accueil.php');
}

$error = $email = '';
$registration = false;
$accountCreated = isset($_SESSION['registration_success']);
unset($_SESSION['registration_success']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    quiz_require_post_token();
    $email = trim(quiz_input('email'));
    $password = quiz_input('password');
    $error = 'Adresse e-mail ou mot de passe incorrect.';

    if (strlen($email) <= 150 && filter_var($email, FILTER_VALIDATE_EMAIL) &&
        $password !== '' && strlen($password) <= 72 && !str_contains($password, "\0")) {
        try {
            $statement = quiz_database()->prepare(
                'SELECT id_utilisateur, pseudo, mot_de_passe_hash FROM utilisateurs WHERE email = ?'
            );
            $statement->execute([$email]);
            $user = $statement->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['mot_de_passe_hash'])) {
                session_regenerate_id(true);
                $_SESSION = ['user_id' => (int) $user['id_utilisateur'], 'pseudo' => $user['pseudo']];
                quiz_redirect('accueil.php');
            }
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            http_response_code(503);
            $error = 'Connexion indisponible. Réessayez dans un instant.';
        }
    }
}

require __DIR__ . '/includes/auth-form.php';
