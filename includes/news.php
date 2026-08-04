<?php

require_once __DIR__ . '/db.php';

const NEWS_CATEGORIES = [
    'einsaetze' => 'Einsätze',
    'veranstaltungen' => 'Veranstaltungen',
    'medienberichte' => 'Medienberichte',
];

function getLatestNews(int $limit = 3): array
{
    $stmt = getDb()->prepare('SELECT title, category, image, published_at FROM news ORDER BY published_at DESC, id DESC LIMIT ?');
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}
