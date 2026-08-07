<?php

require __DIR__ . '/includes/auth.php';
require __DIR__ . '/../includes/db.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    if ($id > 0) {
        $stmt = getDb()->prepare('DELETE FROM board_members WHERE id = ?');
        $stmt->execute([$id]);
    }
}

header('Location: /admin/about.php');
exit;
