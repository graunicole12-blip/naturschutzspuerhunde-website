<?php

const ALLOWED_IMAGE_TYPES = [
    'image/jpeg' => 'jpg',
    'image/png' => 'png',
    'image/webp' => 'webp',
];

const MAX_IMAGE_SIZE = 5 * 1024 * 1024;

function handleImageUpload(array $file, string $destDir): array
{
    if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return ['success' => false, 'filename' => null, 'error' => null];
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'filename' => null, 'error' => 'Upload fehlgeschlagen.'];
    }

    if ($file['size'] > MAX_IMAGE_SIZE) {
        return ['success' => false, 'filename' => null, 'error' => 'Datei ist grösser als 5 MB.'];
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!isset(ALLOWED_IMAGE_TYPES[$mimeType])) {
        return ['success' => false, 'filename' => null, 'error' => 'Ungültiger Dateityp. Erlaubt: JPEG, PNG, WebP.'];
    }

    if (!is_dir($destDir)) {
        mkdir($destDir, 0755, true);
        file_put_contents($destDir . '/.htaccess', "php_flag engine off\n");
    }

    $filename = bin2hex(random_bytes(8)) . '.' . ALLOWED_IMAGE_TYPES[$mimeType];
    $destPath = $destDir . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destPath)) {
        return ['success' => false, 'filename' => null, 'error' => 'Datei konnte nicht gespeichert werden.'];
    }

    return ['success' => true, 'filename' => $filename, 'error' => null];
}
