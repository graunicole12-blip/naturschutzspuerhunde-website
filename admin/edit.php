<?php

require __DIR__ . '/includes/auth.php';
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/upload.php';

requireLogin();

$pageKey = 'startseite';
$textBlockKey = 'vision';
$imageBlockKey = 'hero_image';
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = trim($_POST['content'] ?? '');
    $stmt = getDb()->prepare(
        'INSERT INTO content_blocks (page_key, block_key, content) VALUES (?, ?, ?)
         ON DUPLICATE KEY UPDATE content = VALUES(content)'
    );
    $stmt->execute([$pageKey, $textBlockKey, $content]);

    $uploadResult = handleImageUpload($_FILES['hero_image'] ?? [], __DIR__ . '/../uploads');
    if ($uploadResult['error'] !== null) {
        $error = $uploadResult['error'];
    } elseif ($uploadResult['success']) {
        $stmt->execute([$pageKey, $imageBlockKey, $uploadResult['filename']]);
    }

    $message = $error === '' ? 'Gespeichert.' : '';
}

$stmt = getDb()->prepare('SELECT block_key, content FROM content_blocks WHERE page_key = ? AND block_key IN (?, ?)');
$stmt->execute([$pageKey, $textBlockKey, $imageBlockKey]);
$blocks = [];
foreach ($stmt->fetchAll() as $row) {
    $blocks[$row['block_key']] = $row['content'];
}
$currentText = $blocks[$textBlockKey] ?? '';
$currentImage = $blocks[$imageBlockKey] ?? '';
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Startseite bearbeiten &ndash; Naturschutzsp&uuml;rhunde Admin</title>
  <link rel="stylesheet" href="../assets/css/variables.css">
  <style>
    body { font-family: var(--font-text); margin: 0; background: var(--color-neutral-cream); }
    .topbar { display: flex; justify-content: space-between; align-items: center; padding: 16px 32px; }
    .topbar a { color: var(--color-primary); font-size: 13px; text-decoration: none; }
    .panel { background: #fff; border-radius: 12px; margin: 0 32px 32px; padding: 20px; max-width: 640px; box-sizing: border-box; }
    select { width: 260px; margin-bottom: 12px; padding: 6px; }
    label { display: block; font-size: 12px; color: var(--color-primary); margin: 16px 0 4px; }
    textarea { width: 100%; box-sizing: border-box; min-height: 100px; border: 1px solid var(--color-neutral-blue); border-radius: 6px; padding: 10px; font-size: 14px; font-family: var(--font-text); }
    input[type="file"] { font-size: 13px; }
    button { background: var(--color-accent-red); color: #fff; border: none; border-radius: 6px; height: 36px; padding: 0 20px; font-size: 14px; font-weight: 500; cursor: pointer; margin-top: 16px; }
    .message { color: var(--color-secondary-gold); font-size: 13px; }
    .error { color: var(--color-accent-red); font-size: 13px; }
    .preview { max-width: 240px; display: block; margin-top: 8px; border-radius: 6px; }
    .hint { font-size: 12px; color: var(--color-secondary-khaki); margin: 4px 0 0; }
  </style>
</head>
<body>
  <div class="topbar">
    <a href="/admin/index.php">&larr; Zur&uuml;ck zum Dashboard</a>
    <a href="/admin/logout.php">Abmelden</a>
  </div>
  <div class="panel">
    <select disabled>
      <option>Startseite</option>
    </select>
    <?php if ($message !== ''): ?>
      <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <?php if ($error !== ''): ?>
      <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data">
      <label for="content">Vision</label>
      <textarea id="content" name="content" placeholder="Kurztext Vision verfassen (max. 3-4 S&auml;tze)..."><?php echo htmlspecialchars($currentText); ?></textarea>

      <label for="hero_image">Hero-Bild</label>
      <?php if ($currentImage !== ''): ?>
        <img class="preview" src="/uploads/<?php echo htmlspecialchars($currentImage); ?>" alt="Aktuelles Hero-Bild">
      <?php endif; ?>
      <input type="file" id="hero_image" name="hero_image" accept="image/jpeg,image/png,image/webp">
      <p class="hint">JPEG, PNG oder WebP, max. 5 MB.</p>

      <button type="submit">Speichern</button>
    </form>
  </div>
</body>
</html>
