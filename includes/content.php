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

const HERO_IMAGE_ALIGNMENTS = ['left', 'center', 'right'];

function clampAlignment(?string $raw, string $default = 'center'): string
{
    $raw = trim($raw ?? '');
    return in_array($raw, HERO_IMAGE_ALIGNMENTS, true) ? $raw : $default;
}

function heroImageAlignStyle(string $align): string
{
    switch ($align) {
        case 'left':
            return 'margin-left:0;margin-right:auto;';
        case 'right':
            return 'margin-left:auto;margin-right:0;';
        default:
            return 'margin-left:auto;margin-right:auto;';
    }
}

function clampFocusPoint(?string $raw, float $default = 50.0): float
{
    $raw = trim($raw ?? '');
    if ($raw === '' || !is_numeric($raw)) {
        return $default;
    }

    $value = (float) $raw;
    if ($value < 0 || $value > 100) {
        return $default;
    }

    return round($value, 2);
}

function focusPointObjectPositionStyle(float $focusX, float $focusY): string
{
    return 'object-position:' . $focusX . '% ' . $focusY . '%;';
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
