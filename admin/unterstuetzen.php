<?php

require __DIR__ . '/includes/auth.php';
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/content.php';
require_once __DIR__ . '/../includes/sanitize-html.php';

requireLogin();

$pageKey = 'unterstuetzen';
$spendenKey = 'spenden_text';
$mitgliedKey = 'mitglied_text';
$sponsoringKey = 'sponsoring_text';
$crowdfundingKey = 'crowdfunding_text';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $spendenText = sanitizeHtml(trim($_POST['spenden_text'] ?? ''));
    $mitgliedText = sanitizeHtml(trim($_POST['mitglied_text'] ?? ''));
    $sponsoringText = sanitizeHtml(trim($_POST['sponsoring_text'] ?? ''));
    $crowdfundingText = sanitizeHtml(trim($_POST['crowdfunding_text'] ?? ''));

    $stmt = getDb()->prepare(
        'INSERT INTO content_blocks (page_key, block_key, content) VALUES (?, ?, ?)
         ON DUPLICATE KEY UPDATE content = VALUES(content)'
    );
    $stmt->execute([$pageKey, $spendenKey, $spendenText]);
    $stmt->execute([$pageKey, $mitgliedKey, $mitgliedText]);
    $stmt->execute([$pageKey, $sponsoringKey, $sponsoringText]);
    $stmt->execute([$pageKey, $crowdfundingKey, $crowdfundingText]);

    $message = 'Gespeichert.';
}

$spendenText = getContentBlock($pageKey, $spendenKey, '');
$mitgliedText = getContentBlock($pageKey, $mitgliedKey, '');
$sponsoringText = getContentBlock($pageKey, $sponsoringKey, '');
$crowdfundingText = getContentBlock($pageKey, $crowdfundingKey, '');
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Unterstützen &ndash; Naturschutzsp&uuml;rhunde Admin</title>
  <link rel="stylesheet" href="../assets/css/variables.css">
  <link rel="stylesheet" href="../assets/css/wysiwyg.css">
  <style>
    body { font-family: var(--font-text); margin: 0; background: var(--color-neutral-cream); }
    .topbar { display: flex; justify-content: space-between; align-items: center; padding: 16px 32px; }
    .topbar a { color: var(--color-primary); font-size: 13px; text-decoration: none; }
    .panel { background: #fff; border-radius: 12px; margin: 0 32px 24px; padding: 20px; max-width: 640px; box-sizing: border-box; }
    .panel-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
    h1 { font-family: var(--font-titel); color: var(--color-primary); font-size: 22px; margin: 0; }
    label { display: block; font-size: 12px; color: var(--color-primary); margin: 16px 0 4px; }
    label:first-of-type { margin-top: 0; }
    textarea { width: 100%; box-sizing: border-box; min-height: 100px; border: 1px solid var(--color-neutral-blue); border-radius: 6px; padding: 10px; font-size: 14px; font-family: var(--font-text); }
    button { background: var(--color-accent-red); color: #fff; border: none; border-radius: 6px; height: 36px; padding: 0 20px; font-size: 14px; font-weight: 500; cursor: pointer; margin-top: 16px; }
    .message { color: var(--color-secondary-gold); font-size: 13px; }
    .hint { font-size: 12px; color: var(--color-secondary-khaki); margin: 4px 0 0; }
  </style>
</head>
<body>
  <div class="topbar">
    <a href="/admin/index.php">&larr; Zur&uuml;ck zum Dashboard</a>
    <a href="/admin/logout.php">Abmelden</a>
  </div>
  <div class="panel">
    <div class="panel-header">
      <h1>Unterstützen</h1>
    </div>
    <?php if ($message !== ''): ?>
      <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <form method="post">
      <label for="spenden_text">Spenden</label>
      <textarea id="spenden_text" name="spenden_text" placeholder="Zahlungsmöglichkeiten, Verwendungszweck..."><?php echo htmlspecialchars($spendenText); ?></textarea>
      <p class="hint">Der Spenden-Button auf der öffentlichen Seite verlinkt fest auf die Lokalhelden-Kampagne.</p>

      <label for="mitglied_text">Mitglied werden</label>
      <textarea id="mitglied_text" name="mitglied_text" placeholder="Mitgliedschaftsarten, Kosten, Anmeldeweg..."><?php echo htmlspecialchars($mitgliedText); ?></textarea>

      <label for="sponsoring_text">Sponsoring</label>
      <textarea id="sponsoring_text" name="sponsoring_text" placeholder="Sponsoring-Pakete, Kontaktmöglichkeit..."><?php echo htmlspecialchars($sponsoringText); ?></textarea>

      <label for="crowdfunding_text">Crowdfunding</label>
      <textarea id="crowdfunding_text" name="crowdfunding_text" placeholder="Kurzer Hinweis zur laufenden Kampagne..."><?php echo htmlspecialchars($crowdfundingText); ?></textarea>
      <p class="hint">Der Crowdfunding-Button auf der öffentlichen Seite verlinkt fest auf die Lokalhelden-Kampagne.</p>

      <button type="submit">Speichern</button>
    </form>
  </div>

  <script src="../assets/js/wysiwyg.js"></script>
  <script>
    initWysiwyg('spenden_text');
    initWysiwyg('mitglied_text');
    initWysiwyg('sponsoring_text');
    initWysiwyg('crowdfunding_text');
  </script>
</body>
</html>
