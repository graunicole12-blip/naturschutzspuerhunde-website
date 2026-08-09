<?php

require __DIR__ . '/includes/auth.php';
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/content.php';
require_once __DIR__ . '/../includes/sanitize-html.php';

requireLogin();

$pageKey = 'naturschutzspuerhunde';
$wasSindKey = 'was_sind_text';
$wieArbeitenKey = 'wie_arbeiten_text';
$einsatzKey = 'einsatzmoeglichkeiten_text';
$warumHundeKey = 'warum_hunde_text';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $wasSindText = sanitizeHtml(trim($_POST['was_sind_text'] ?? ''));
    $wieArbeitenText = sanitizeHtml(trim($_POST['wie_arbeiten_text'] ?? ''));
    $einsatzText = sanitizeHtml(trim($_POST['einsatzmoeglichkeiten_text'] ?? ''));
    $warumHundeText = sanitizeHtml(trim($_POST['warum_hunde_text'] ?? ''));

    $stmt = getDb()->prepare(
        'INSERT INTO content_blocks (page_key, block_key, content) VALUES (?, ?, ?)
         ON DUPLICATE KEY UPDATE content = VALUES(content)'
    );
    $stmt->execute([$pageKey, $wasSindKey, $wasSindText]);
    $stmt->execute([$pageKey, $wieArbeitenKey, $wieArbeitenText]);
    $stmt->execute([$pageKey, $einsatzKey, $einsatzText]);
    $stmt->execute([$pageKey, $warumHundeKey, $warumHundeText]);

    $message = 'Gespeichert.';
}

$wasSindText = getContentBlock($pageKey, $wasSindKey, '');
$wieArbeitenText = getContentBlock($pageKey, $wieArbeitenKey, '');
$einsatzText = getContentBlock($pageKey, $einsatzKey, '');
$warumHundeText = getContentBlock($pageKey, $warumHundeKey, '');
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Naturschutzsp&uuml;rhunde &ndash; Naturschutzsp&uuml;rhunde Admin</title>
  <link rel="stylesheet" href="../assets/css/variables.css">
  <link rel="stylesheet" href="../assets/css/block-editor.css">
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
  </style>
</head>
<body>
  <div class="topbar">
    <a href="/admin/index.php">&larr; Zur&uuml;ck zum Dashboard</a>
    <a href="/admin/logout.php">Abmelden</a>
  </div>
  <div class="panel">
    <div class="panel-header">
      <h1>Naturschutzsp&uuml;rhunde</h1>
    </div>
    <?php if ($message !== ''): ?>
      <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <form method="post">
      <label for="was_sind_text">Was sind Naturschutzspürhunde?</label>
      <textarea id="was_sind_text" name="was_sind_text" placeholder="Fachlich, aber laienverständlich..."><?php echo htmlspecialchars($wasSindText); ?></textarea>

      <label for="wie_arbeiten_text">Wie arbeiten sie?</label>
      <textarea id="wie_arbeiten_text" name="wie_arbeiten_text" placeholder="Trainingsmethode, Einsatzablauf..."><?php echo htmlspecialchars($wieArbeitenText); ?></textarea>

      <label for="einsatzmoeglichkeiten_text">Einsatzmöglichkeiten</label>
      <textarea id="einsatzmoeglichkeiten_text" name="einsatzmoeglichkeiten_text" placeholder="Kategorien mit Beispielen..."><?php echo htmlspecialchars($einsatzText); ?></textarea>

      <label for="warum_hunde_text">Warum Hunde?</label>
      <textarea id="warum_hunde_text" name="warum_hunde_text" placeholder="Vorteile gegenüber anderen Nachweismethoden..."><?php echo htmlspecialchars($warumHundeText); ?></textarea>

      <button type="submit">Speichern</button>
    </form>
  </div>

  <script src="../assets/js/block-editor.js"></script>
  <script>
    initBlockEditor('was_sind_text');
    initBlockEditor('wie_arbeiten_text');
    initBlockEditor('einsatzmoeglichkeiten_text');
    initBlockEditor('warum_hunde_text');
  </script>
</body>
</html>
