<?php

require_once __DIR__ . '/db.php';

const PROJECT_STATUSES = [
    'in_vorbereitung' => 'In Vorbereitung',
    'laufend' => 'Laufend',
    'abgeschlossen' => 'Abgeschlossen',
];

function getFeaturedProjects(int $limit = 3): array
{
    $stmt = getDb()->prepare('SELECT title, content, image FROM projects ORDER BY sort_order ASC, id DESC LIMIT ?');
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}
