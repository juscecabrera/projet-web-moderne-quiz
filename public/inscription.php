<?php
declare(strict_types=1);

require __DIR__ . '/../src/session.php';
require __DIR__ . '/../src/database.php';

if (isset($_SESSION['user_id'])) {
    quiz_redirect('accueil.php');
}

$error = $email = $pseudo = '';
$registration = true;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    quiz_require_post_token();
    $pseudo = trim(quiz_input('pseudo'));
    $email = trim(quiz_input('email'));
    $password = quiz_input('password');
    $confirmation = quiz_input('password_confirmation');

    if ($pseudo === '' || $email === '' || $password === '' || $confirmation === '') {
        $error = 'Remplissez tous les champs.';
    } elseif ($password !== $confirmation) {
        $error = 'Les mots de passe ne correspondent pas.';
    } elseif (mb_strlen($pseudo) > 50) {
        $error = 'Saisissez un pseudo de 1 à 50 caractères.';
    } elseif (strlen($email) > 150 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Saisissez une adresse e-mail valide.';
    } elseif (mb_strlen($password) < 8 || strlen($password) > 72 || str_contains($password, "\0")) {
        $error = 'Saisissez un mot de passe d’au moins 8 caractères et de 72 octets maximum.';
    } else {
        try {
            $database = quiz_database();
            $statement = $database->prepare(
                'INSERT INTO utilisateurs (pseudo, email, mot_de_passe_hash) VALUES (?, ?, ?)'
            );
            $statement->execute([$pseudo, $email, password_hash($password, PASSWORD_DEFAULT)]);
            quiz_sign_in((int) $database->lastInsertId(), $pseudo);
        } catch (PDOException $exception) {
            if (($exception->errorInfo[1] ?? null) === 1062) {
                $error = 'Ce pseudo ou cette adresse e-mail est déjà utilisé.';
            } else {
                error_log($exception->getMessage());
                http_response_code(503);
                $error = 'Inscription indisponible. Réessayez dans un instant.';
            }
        }
    }
}

require __DIR__ . '/../src/auth-form.php';
