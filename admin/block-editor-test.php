<?php

require __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../includes/blocks.php';

requireLogin();

$saved = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $raw = trim($_POST['demo_content'] ?? '');
    $decoded = json_decode($raw, true);
    $blocks = is_array($decoded) ? sanitizeBlocks($decoded) : [];
    $saved = json_encode($blocks, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Block-Editor Testseite &ndash; Naturschutzsp&uuml;rhunde Admin</title>
  <link rel="stylesheet" href="../assets/css/variables.css">
  <link rel="stylesheet" href="../assets/css/wysiwyg.css">
  <link rel="stylesheet" href="../assets/css/block-editor.css">
  <style>
    body { font-family: var(--font-text); margin: 0; background: var(--color-neutral-cream); }
    .topbar { display: flex; justify-content: space-between; align-items: center; padding: 16px 32px; }
    .topbar a { color: var(--color-primary); font-size: 13px; text-decoration: none; }
    .panel { background: #fff; border-radius: 12px; margin: 0 32px 24px; padding: 20px; max-width: 640px; box-sizing: border-box; }
    h1 { font-family: var(--font-titel); color: var(--color-primary); font-size: 20px; margin: 0 0 6px; }
    .hint { font-size: 12px; color: var(--color-secondary-khaki); margin: 0 0 16px; }
    button.save-btn { background: var(--color-accent-red); color: #fff; border: none; border-radius: 6px; height: 36px; padding: 0 20px; font-size: 14px; font-weight: 500; cursor: pointer; margin-top: 16px; }
    pre { background: var(--color-neutral-cream); padding: 12px; border-radius: 6px; font-size: 12px; overflow-x: auto; }
  </style>
</head>
<body>
  <div class="topbar">
    <a href="/admin/index.php">&larr; Zur&uuml;ck zum Dashboard</a>
    <a href="/admin/logout.php">Abmelden</a>
  </div>
  <div class="panel">
    <h1>Block-Editor &ndash; Testseite (Issue #125)</h1>
    <p class="hint">Interner Test der Editor-Komponente, keine echten Inhalte. Wird entfernt/ersetzt, sobald die Bl&ouml;cke in den echten Admin-Seiten ausgerollt sind (#129).</p>
    <form method="post">
      <textarea id="demo_content" name="demo_content"></textarea>
      <button type="submit" class="save-btn">Als Block-JSON speichern &amp; anzeigen</button>
    </form>
    <?php if ($saved !== ''): ?>
      <p class="hint">Gespeichertes (server-seitig sanitisiertes) Block-JSON:</p>
      <pre><?php echo htmlspecialchars($saved); ?></pre>
    <?php endif; ?>
  </div>

  <script src="../assets/js/block-editor.js"></script>
  <script>
    initBlockEditor('demo_content');
  </script>
</body>
</html>
