<?php

require_once __DIR__ . '/db.php';

function getAllBoardMembers(): array
{
    $stmt = getDb()->query('SELECT id, name, role, bio, image FROM board_members ORDER BY sort_order ASC, id ASC');
    return $stmt->fetchAll();
}
