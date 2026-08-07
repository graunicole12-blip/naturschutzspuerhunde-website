<?php

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/sanitize-html.php';

function getContentBlock(string $pageKey, string $blockKey, string $default = ''): string
{
    $stmt = getDb()->prepare('SELECT content FROM content_blocks WHERE page_key = ? AND block_key = ?');
    $stmt->execute([$pageKey, $blockKey]);
    $value = $stmt->fetchColumn();
    return ($value !== false && $value !== null) ? $value : $default;
}

function renderRichText(?string $raw): string
{
    return nl2br(sanitizeHtml($raw ?? ''));
}
