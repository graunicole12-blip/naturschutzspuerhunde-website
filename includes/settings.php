<?php

require_once __DIR__ . '/db.php';

function getSetting(string $key, string $default = ''): string
{
    $stmt = getDb()->prepare('SELECT setting_value FROM settings WHERE setting_key = ?');
    $stmt->execute([$key]);
    $value = $stmt->fetchColumn();
    return ($value !== false && $value !== null) ? $value : $default;
}

function setSetting(string $key, string $value): void
{
    $stmt = getDb()->prepare(
        'INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)
         ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)'
    );
    $stmt->execute([$key, $value]);
}

function sanitizeHexColor(?string $raw, string $default): string
{
    $raw = trim($raw ?? '');
    return preg_match('/^#[0-9a-fA-F]{6}$/', $raw) ? $raw : $default;
}
