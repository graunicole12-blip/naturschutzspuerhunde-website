<?php

require_once __DIR__ . '/db.php';

function getAllPartners(): array
{
    $stmt = getDb()->query('SELECT id, name, description, link, logo FROM partners ORDER BY sort_order ASC, id ASC');
    return $stmt->fetchAll();
}
