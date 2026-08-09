<?php

require_once __DIR__ . '/db.php';

const NEWS_CATEGORIES = [
    'einsaetze' => 'Einsätze',
    'veranstaltungen' => 'Veranstaltungen',
    'medienberichte' => 'Medienberichte',
];

function getLatestNews(int $limit = 3): array
{
    $stmt = getDb()->prepare('SELECT id, title, category, image, published_at FROM news ORDER BY published_at DESC, id DESC LIMIT ?');
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

function getAllNews(): array
{
    $stmt = getDb()->query('SELECT id, title, category, content, image, published_at FROM news ORDER BY published_at DESC, id DESC');
    return $stmt->fetchAll();
}

function getNewsById(int $id): ?array
{
    $stmt = getDb()->prepare('SELECT id, title, category, content, image, published_at FROM news WHERE id = ?');
    $stmt->execute([$id]);
    $item = $stmt->fetch();
    return $item !== false ? $item : null;
}
