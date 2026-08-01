<?php

require_once __DIR__ . '/session.php';

function requireLogin(): void
{
    startSecureSession();
    if (empty($_SESSION['admin_id'])) {
        header('Location: /admin/login.php');
        exit;
    }
}
