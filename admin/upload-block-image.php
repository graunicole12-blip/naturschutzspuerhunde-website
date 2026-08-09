<?php

require __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../includes/upload.php';

requireLogin();

header('Content-Type: application/json');

$result = handleImageUpload($_FILES['image'] ?? [], __DIR__ . '/../uploads');

if (!$result['success']) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $result['error'] ?? 'Kein Bild ausgewählt.']);
    exit;
}

echo json_encode(['success' => true, 'filename' => $result['filename'], 'url' => '/uploads/' . $result['filename']]);
