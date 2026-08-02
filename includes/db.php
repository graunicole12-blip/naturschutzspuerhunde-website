<?php

function getDb(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $config = json_decode(file_get_contents(__DIR__ . '/../config.json'), true);
        $pdo = new PDO(
            'mysql:host=' . $config['DB_HOST'] . ';dbname=' . $config['DB_NAME'] . ';charset=utf8mb4',
            $config['DB_USER'],
            $config['DB_PASSWORD'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    }
    return $pdo;
}
