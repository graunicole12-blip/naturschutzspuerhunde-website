<?php

require_once __DIR__ . '/settings.php';
require_once __DIR__ . '/../admin/includes/session.php';

function checkMaintenanceMode(): void
{
    startSecureSession();

    if (getSetting('maintenance_mode', '0') !== '1') {
        return;
    }

    if (!empty($_SESSION['admin_id'])) {
        return;
    }

    $currentToken = getSetting('maintenance_preview_token', '');

    if ($currentToken !== '' && isset($_GET['preview']) && hash_equals($currentToken, $_GET['preview'])) {
        $_SESSION['maintenance_preview_token'] = $currentToken;
        return;
    }

    if ($currentToken !== '' && !empty($_SESSION['maintenance_preview_token'])
        && hash_equals($currentToken, $_SESSION['maintenance_preview_token'])) {
        return;
    }

    http_response_code(503);
    header('Retry-After: 3600');
    require __DIR__ . '/maintenance-page.php';
    exit;
}
