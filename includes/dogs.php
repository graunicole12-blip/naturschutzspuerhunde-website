<?php

require_once __DIR__ . '/db.php';

function getAllDogs(): array
{
    $stmt = getDb()->query('SELECT id, name, bio, einsatzgebiet, charakter, image, is_active FROM dogs ORDER BY sort_order ASC, id ASC');
    return $stmt->fetchAll();
}

function getActiveDogs(int $limit = 3): array
{
    $stmt = getDb()->prepare('SELECT id, name, bio, einsatzgebiet, charakter, image FROM dogs WHERE is_active = 1 ORDER BY sort_order ASC, id ASC LIMIT ?');
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}
