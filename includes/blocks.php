<?php

require_once __DIR__ . '/sanitize-html.php';

const BLOCK_TYPES = ['paragraph', 'heading', 'quote', 'image', 'list'];
const BLOCK_IMAGE_FILENAME_PATTERN = '/^[A-Za-z0-9._-]+\.(jpe?g|png|webp)$/i';

// Keys map 1:1 to the CSS custom properties --color-{key} / --font-size-{key}
// defined in assets/css/variables.css (single source of truth for the CI/CD
// palette per docs/corporate-design.md). Only whitelisted keys are ever
// accepted, so a stored value can always be safely emitted as var(--color-KEY).
const BLOCK_COLOR_OPTIONS = [
    'primary' => 'Dunkelolive (Haupttext)',
    'primary-dark' => 'Fast-Schwarzoliv',
    'accent-red' => 'Rot (Akzent)',
    'accent-rust' => 'Rostrot/Terrakotta',
    'secondary-gold' => 'Olivgold',
    'secondary-khaki' => 'Olivkhaki',
    'neutral-cream' => 'Creme',
    'neutral-tan' => 'Beige/Tan',
    'neutral-blue-light' => 'Blaugrau hell',
    'neutral-blue' => 'Blaugrau',
    'accent-mauve-dark' => 'Mauve dunkel',
    'accent-mauve' => 'Mauve/Beere',
];

const BLOCK_FONT_SIZE_OPTIONS = [
    'text' => 'Fliesstext (14px)',
    'zitat' => 'Zitat (16px)',
    'abschnitt-kopfzeile' => 'Abschnitt-Kopfzeile (18px)',
    'zwischenueberschrift' => 'Zwischenüberschrift (24px)',
    'ueberschrift' => 'Überschrift (32px)',
    'untertitel' => 'Untertitel (32px)',
    'titel' => 'Titel (42px)',
];

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

function sanitizeBlockColorKey($raw): ?string
{
    $raw = is_string($raw) ? trim($raw) : '';
    return isset(BLOCK_COLOR_OPTIONS[$raw]) ? $raw : null;
}

function sanitizeBlockFontSizeKey($raw): ?string
{
    $raw = is_string($raw) ? trim($raw) : '';
    return isset(BLOCK_FONT_SIZE_OPTIONS[$raw]) ? $raw : null;
}

function sanitizeBlock(array $block): ?array
{
    switch ($block['type']) {
        case 'paragraph':
        case 'quote':
            $content = sanitizeHtml((string) ($block['content'] ?? ''));
            if ($content === '') {
                return null;
            }
            return addBlockStyleFields(['type' => $block['type'], 'content' => $content], $block);

        case 'heading':
            $content = trim(strip_tags((string) ($block['content'] ?? '')));
            if ($content === '') {
                return null;
            }
            return addBlockStyleFields(['type' => 'heading', 'content' => $content], $block);

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
            if (empty($items)) {
                return null;
            }
            return addBlockStyleFields(['type' => 'list', 'items' => $items], $block);

        default:
            return null;
    }
}

function addBlockStyleFields(array $clean, array $raw): array
{
    $color = sanitizeBlockColorKey($raw['color'] ?? null);
    if ($color !== null) {
        $clean['color'] = $color;
    }

    $fontSize = sanitizeBlockFontSizeKey($raw['fontSize'] ?? null);
    if ($fontSize !== null) {
        $clean['fontSize'] = $fontSize;
    }

    return $clean;
}

function blocksToJson(array $blocks): string
{
    return json_encode(sanitizeBlocks($blocks), JSON_UNESCAPED_UNICODE);
}

function sanitizeBlockFieldInput(string $raw): string
{
    $raw = trim($raw);
    if ($raw === '') {
        return '';
    }

    if (isBlockJson($raw)) {
        return blocksToJson(json_decode($raw, true));
    }

    // Not block-JSON (e.g. submitted without JS) - fall back to the legacy
    // plain-HTML sanitizer instead of storing an unsanitized raw string.
    return sanitizeHtml($raw);
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
