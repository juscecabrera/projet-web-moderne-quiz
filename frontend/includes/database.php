<?php
declare(strict_types=1);

function quiz_database(): PDO
{
    $host = getenv('QUIZ_DB_HOST') ?: '127.0.0.1';
    $port = getenv('QUIZ_DB_PORT') ?: '3306';
    $name = getenv('QUIZ_DB_NAME') ?: 'quiz_culture_generale';
    $user = getenv('QUIZ_DB_USER') ?: 'root';
    $password = getenv('QUIZ_DB_PASSWORD');

    return new PDO(
        "mysql:host=$host;port=$port;dbname=$name;charset=utf8mb4",
        $user,
        $password === false ? '' : $password,
        [PDO::ATTR_EMULATE_PREPARES => false]
    );
}
