<?php

const SANITIZE_HTML_ALLOWED_TAGS = ['b', 'strong', 'i', 'em', 'u', 'ul', 'ol', 'li', 'a', 'br', 'p', 'span', 'div'];

function sanitizeHtml(string $html): string
{
    if (trim($html) === '') {
        return '';
    }

    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML('<?xml encoding="utf-8"?><div>' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    libxml_clear_errors();

    $root = $dom->documentElement;
    if ($root === null) {
        return '';
    }

    sanitizeHtmlNode($dom, $root);

    $result = '';
    foreach (iterator_to_array($root->childNodes) as $child) {
        $result .= $dom->saveHTML($child);
    }
    return $result;
}

function sanitizeHtmlNode(DOMDocument $dom, DOMNode $node): void
{
    foreach (iterator_to_array($node->childNodes) as $child) {
        if ($child instanceof DOMText) {
            continue;
        }

        if (!($child instanceof DOMElement)) {
            $node->removeChild($child);
            continue;
        }

        // Sanitize the subtree first so nested disallowed content is handled
        // regardless of whether this tag itself is kept or unwrapped below.
        sanitizeHtmlNode($dom, $child);

        $tagName = strtolower($child->tagName);

        if (!in_array($tagName, SANITIZE_HTML_ALLOWED_TAGS, true)) {
            while ($child->firstChild) {
                $node->insertBefore($child->firstChild, $child);
            }
            $node->removeChild($child);
            continue;
        }

        sanitizeHtmlAttributes($child, $tagName);
    }
}

function sanitizeHtmlAttributes(DOMElement $element, string $tagName): void
{
    foreach (iterator_to_array($element->attributes) as $attr) {
        $name = strtolower($attr->name);

        if ($tagName === 'a' && $name === 'href' && isHrefSchemeAllowed($attr->value)) {
            continue;
        }

        if ($tagName === 'span' && $name === 'class' && $attr->value === 'text-upper') {
            continue;
        }

        $element->removeAttribute($attr->name);
    }
}

function isHrefSchemeAllowed(string $href): bool
{
    $href = trim($href);
    if ($href === '') {
        return false;
    }
    if (!preg_match('/^[a-zA-Z][a-zA-Z0-9+.-]*:/', $href)) {
        return true;
    }
    return (bool) preg_match('/^(https?|mailto):/i', $href);
}
