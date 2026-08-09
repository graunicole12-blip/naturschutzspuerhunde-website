<?php

require __DIR__ . '/includes/auth.php';
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/content.php';
require_once __DIR__ . '/../includes/sanitize-html.php';

requireLogin();

$pageKey = 'ausbildung';
$qualitaetKey = 'qualitaetsstandards_text';
$zusammenarbeitKey = 'internationale_zusammenarbeit_text';
$assessmentsKey = 'assessments_text';
$weiterbildungKey = 'weiterbildung_text';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $qualitaetText = sanitizeBlockFieldInput(trim($_POST['qualitaetsstandards_text'] ?? ''));
    $zusammenarbeitText = sanitizeBlockFieldInput(trim($_POST['internationale_zusammenarbeit_text'] ?? ''));
    $assessmentsText = sanitizeBlockFieldInput(trim($_POST['assessments_text'] ?? ''));
    $weiterbildungText = sanitizeBlockFieldInput(trim($_POST['weiterbildung_text'] ?? ''));

    $stmt = getDb()->prepare(
        'INSERT INTO content_blocks (page_key, block_key, content) VALUES (?, ?, ?)
         ON DUPLICATE KEY UPDATE content = VALUES(content)'
    );
    $stmt->execute([$pageKey, $qualitaetKey, $qualitaetText]);
    $stmt->execute([$pageKey, $zusammenarbeitKey, $zusammenarbeitText]);
    $stmt->execute([$pageKey, $assessmentsKey, $assessmentsText]);
    $stmt->execute([$pageKey, $weiterbildungKey, $weiterbildungText]);

    $message = 'Gespeichert.';
}

$qualitaetText = getContentBlock($pageKey, $qualitaetKey, '');
$zusammenarbeitText = getContentBlock($pageKey, $zusammenarbeitKey, '');
$assessmentsText = getContentBlock($pageKey, $assessmentsKey, '');
$weiterbildungText = getContentBlock($pageKey, $weiterbildungKey, '');
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ausbildung &ndash; Naturschutzsp&uuml;rhunde Admin</title>
  <link rel="stylesheet" href="../assets/css/variables.css">
  <link rel="stylesheet" href="../assets/css/block-editor.css">
  <link rel="stylesheet" href="../assets/css/focus-point.css">
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
      <h1>Ausbildung</h1>
    </div>
    <?php if ($message !== ''): ?>
      <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <form method="post">
      <label for="qualitaetsstandards_text">Qualitätsstandards</label>
      <textarea id="qualitaetsstandards_text" name="qualitaetsstandards_text" placeholder="Kriterien, Zertifizierung..."><?php echo htmlspecialchars($qualitaetText); ?></textarea>

      <label for="internationale_zusammenarbeit_text">Internationale Zusammenarbeit</label>
      <textarea id="internationale_zusammenarbeit_text" name="internationale_zusammenarbeit_text" placeholder="Partnerorganisationen, Netzwerke im Ausland..."><?php echo htmlspecialchars($zusammenarbeitText); ?></textarea>

      <label for="assessments_text">Assessments</label>
      <textarea id="assessments_text" name="assessments_text" placeholder="Ablauf, Kriterien, Häufigkeit..."><?php echo htmlspecialchars($assessmentsText); ?></textarea>

      <label for="weiterbildung_text">Weiterbildung</label>
      <textarea id="weiterbildung_text" name="weiterbildung_text" placeholder="Angebot, Zielgruppe, Anmeldeprozess..."><?php echo htmlspecialchars($weiterbildungText); ?></textarea>

      <button type="submit">Speichern</button>
    </form>
  </div>

  <script src="../assets/js/block-editor.js"></script>
  <script src="../assets/js/focus-point.js"></script>
  <script>
    initBlockEditor('qualitaetsstandards_text');
    initBlockEditor('internationale_zusammenarbeit_text');
    initBlockEditor('assessments_text');
    initBlockEditor('weiterbildung_text');
  </script>
</body>
</html>
