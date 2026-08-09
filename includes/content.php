<?php

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/sanitize-html.php';
require_once __DIR__ . '/blocks.php';

function getContentBlock(string $pageKey, string $blockKey, string $default = ''): string
{
    $stmt = getDb()->prepare('SELECT content FROM content_blocks WHERE page_key = ? AND block_key = ?');
    $stmt->execute([$pageKey, $blockKey]);
    $value = $stmt->fetchColumn();
    return ($value !== false && $value !== null) ? $value : $default;
}

function renderRichText(?string $raw): string
{
    $raw = $raw ?? '';
    if ($raw === '') {
        return '';
    }

    if (isBlockJson($raw)) {
        return renderBlockList(json_decode($raw, true));
    }

    // Legacy format: plain sanitized HTML string, from before the block editor.
    return nl2br(sanitizeHtml($raw));
}

function clampCardLimit(?string $raw, int $default = 3, int $min = 1, int $max = 12): int
{
    $raw = trim($raw ?? '');
    if ($raw === '' || !ctype_digit($raw)) {
        return $default;
    }

    $value = (int) $raw;
    if ($value < $min) {
        return $default;
    }

    return min($value, $max);
}

function renderPlainText(?string $raw): string
{
    $raw = $raw ?? '';
    if ($raw === '') {
        return '';
    }

    if (isBlockJson($raw)) {
        return blockListToPlainText(json_decode($raw, true));
    }

    return trim(strip_tags($raw));
}
