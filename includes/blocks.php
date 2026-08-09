<?php

require_once __DIR__ . '/sanitize-html.php';

const BLOCK_TYPES = ['paragraph', 'heading', 'quote', 'image', 'list'];
const BLOCK_IMAGE_FILENAME_PATTERN = '/^[A-Za-z0-9._-]+\.(jpe?g|png|webp)$/i';

function sanitizeBlocks(array $blocks): array
{
    $sanitized = [];
    foreach ($blocks as $block) {
        if (!is_array($block) || !isset($block['type']) || !in_array($block['type'], BLOCK_TYPES, true)) {
            continue;
        }

        $clean = sanitizeBlock($block);
        if ($clean !== null) {
            $sanitized[] = $clean;
        }
    }
    return $sanitized;
}

function sanitizeBlock(array $block): ?array
{
    switch ($block['type']) {
        case 'paragraph':
        case 'quote':
            $content = sanitizeHtml((string) ($block['content'] ?? ''));
            return $content !== '' ? ['type' => $block['type'], 'content' => $content] : null;

        case 'heading':
            $content = trim(strip_tags((string) ($block['content'] ?? '')));
            return $content !== '' ? ['type' => 'heading', 'content' => $content] : null;

        case 'image':
            $src = basename((string) ($block['src'] ?? ''));
            if ($src === '' || !preg_match(BLOCK_IMAGE_FILENAME_PATTERN, $src)) {
                return null;
            }
            $alt = trim(strip_tags((string) ($block['alt'] ?? '')));
            return ['type' => 'image', 'src' => $src, 'alt' => $alt];

        case 'list':
            $items = [];
            foreach ((array) ($block['items'] ?? []) as $item) {
                $text = trim(strip_tags((string) $item));
                if ($text !== '') {
                    $items[] = $text;
                }
            }
            return !empty($items) ? ['type' => 'list', 'items' => $items] : null;

        default:
            return null;
    }
}

function blocksToJson(array $blocks): string
{
    return json_encode(sanitizeBlocks($blocks), JSON_UNESCAPED_UNICODE);
}

function isBlockJson(string $raw): bool
{
    $trimmed = trim($raw);
    if ($trimmed === '' || $trimmed[0] !== '[') {
        return false;
    }
    $decoded = json_decode($trimmed, true);
    return is_array($decoded);
}

function blockListToPlainText(array $blocks): string
{
    $parts = [];
    foreach (sanitizeBlocks($blocks) as $block) {
        switch ($block['type']) {
            case 'paragraph':
            case 'heading':
            case 'quote':
                $text = trim(strip_tags($block['content']));
                if ($text !== '') {
                    $parts[] = $text;
                }
                break;
            case 'list':
                $text = trim(implode(', ', $block['items']));
                if ($text !== '') {
                    $parts[] = $text;
                }
                break;
        }
    }
    return implode(' ', $parts);
}

function renderBlockList(array $blocks): string
{
    $html = '';
    foreach (sanitizeBlocks($blocks) as $block) {
        switch ($block['type']) {
            case 'paragraph':
                $html .= '<p class="block-paragraph">' . $block['content'] . '</p>';
                break;
            case 'heading':
                $html .= '<h3 class="block-heading">' . htmlspecialchars($block['content']) . '</h3>';
                break;
            case 'quote':
                $html .= '<blockquote class="block-quote">' . $block['content'] . '</blockquote>';
                break;
            case 'image':
                $html .= '<img src="/uploads/' . htmlspecialchars($block['src']) . '" alt="' . htmlspecialchars($block['alt']) . '" class="block-image">';
                break;
            case 'list':
                $html .= '<ul class="block-list">';
                foreach ($block['items'] as $item) {
                    $html .= '<li>' . htmlspecialchars($item) . '</li>';
                }
                $html .= '</ul>';
                break;
        }
    }
    return $html;
}
