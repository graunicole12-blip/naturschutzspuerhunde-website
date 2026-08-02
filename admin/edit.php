<?php

require __DIR__ . '/includes/auth.php';
require __DIR__ . '/../includes/db.php';

requireLogin();

$pageKey = 'startseite';
$blockKey = 'vision';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = trim($_POST['content'] ?? '');
    $stmt = getDb()->prepare(
        'INSERT INTO content_blocks (page_key, block_key, content) VALUES (?, ?, ?)
         ON DUPLICATE KEY UPDATE content = VALUES(content)'
    );
    $stmt->execute([$pageKey, $blockKey, $content]);
    $message = 'Gespeichert.';
}

$stmt = getDb()->prepare('SELECT content FROM content_blocks WHERE page_key = ? AND block_key = ?');
$stmt->execute([$pageKey, $blockKey]);
$current = $stmt->fetchColumn();
if ($current === false) {
    $current = '';
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Text bearbeiten &ndash; Naturschutzsp&uuml;rhunde Admin</title>
  <link rel="stylesheet" href="../assets/css/variables.css">
  <style>
    body { font-family: var(--font-text); margin: 0; background: var(--color-neutral-cream); }
    .topbar { display: flex; justify-content: space-between; align-items: center; padding: 16px 32px; }
    .topbar a { color: var(--color-primary); font-size: 13px; text-decoration: none; }
    .panel { background: #fff; border-radius: 12px; margin: 0 32px 32px; padding: 20px; max-width: 640px; box-sizing: border-box; }
    select { width: 260px; margin-bottom: 12px; padding: 6px; }
    textarea { width: 100%; box-sizing: border-box; min-height: 100px; border: 1px solid var(--color-neutral-blue); border-radius: 6px; padding: 10px; font-size: 14px; font-family: var(--font-text); }
    button { background: var(--color-accent-red); color: #fff; border: none; border-radius: 6px; height: 36px; padding: 0 20px; font-size: 14px; font-weight: 500; cursor: pointer; margin-top: 12px; }
    .message { color: var(--color-secondary-gold); font-size: 13px; }
  </style>
</head>
<body>
  <div class="topbar">
    <a href="/admin/index.php">&larr; Zur&uuml;ck zum Dashboard</a>
    <a href="/admin/logout.php">Abmelden</a>
  </div>
  <div class="panel">
    <select disabled>
      <option>Startseite &ndash; Vision</option>
    </select>
    <?php if ($message !== ''): ?>
      <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <form method="post">
      <textarea name="content" placeholder="Kurztext Vision verfassen (max. 3-4 S&auml;tze)..."><?php echo htmlspecialchars($current); ?></textarea>
      <br>
      <button type="submit">Speichern</button>
    </form>
  </div>
</body>
</html>
