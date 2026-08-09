<?php

require __DIR__ . '/includes/auth.php';
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/content.php';
require __DIR__ . '/../includes/projects.php';
require_once __DIR__ . '/../includes/sanitize-html.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['forschung_text'])) {
    $stmt = getDb()->prepare(
        'INSERT INTO content_blocks (page_key, block_key, content) VALUES (?, ?, ?)
         ON DUPLICATE KEY UPDATE content = VALUES(content)'
    );
    $stmt->execute(['projekte', 'forschung_text', sanitizeBlockFieldInput(trim($_POST['forschung_text']))]);
    header('Location: /admin/projects.php');
    exit;
}

$projects = getDb()->query('SELECT id, title, status FROM projects ORDER BY sort_order ASC, title ASC')->fetchAll();
$forschungText = getContentBlock('projekte', 'forschung_text', '');
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Projekte &ndash; Naturschutzsp&uuml;rhunde Admin</title>
  <link rel="stylesheet" href="../assets/css/variables.css">
  <link rel="stylesheet" href="../assets/css/block-editor.css">
  <link rel="stylesheet" href="../assets/css/focus-point.css">
  <style>
    body { font-family: var(--font-text); margin: 0; background: var(--color-neutral-cream); }
    .topbar { display: flex; justify-content: space-between; align-items: center; padding: 16px 32px; }
    .topbar a { color: var(--color-primary); font-size: 13px; text-decoration: none; }
    .panel { background: #fff; border-radius: 12px; margin: 0 32px 32px; padding: 20px; box-sizing: border-box; }
    .panel-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
    h1 { font-family: var(--font-titel); color: var(--color-primary); font-size: 22px; margin: 0; }
    .new-btn { background: var(--color-accent-red); color: #fff; border-radius: 6px; padding: 8px 16px; font-size: 13px; text-decoration: none; }
    table { width: 100%; border-collapse: collapse; font-size: 13px; }
    th { text-align: left; color: var(--color-secondary-khaki); font-weight: 500; padding: 8px; border-bottom: 1px solid var(--color-neutral-blue-light); }
    td { padding: 8px; border-bottom: 1px solid var(--color-neutral-blue-light); color: var(--color-primary); }
    td a { color: var(--color-secondary-gold); text-decoration: none; margin-right: 12px; }
    .delete-btn { background: none; border: none; color: var(--color-accent-red); font-size: 13px; cursor: pointer; padding: 0; }
    .empty { color: var(--color-secondary-khaki); font-size: 13px; }
    textarea { width: 100%; box-sizing: border-box; min-height: 100px; border: 1px solid var(--color-neutral-blue); border-radius: 6px; padding: 10px; font-size: 14px; font-family: var(--font-text); }
    label { display: block; font-size: 12px; color: var(--color-primary); margin: 0 0 4px; }
    .save-btn { background: var(--color-accent-red); color: #fff; border: none; border-radius: 6px; height: 36px; padding: 0 20px; font-size: 14px; font-weight: 500; cursor: pointer; margin-top: 12px; }
  </style>
</head>
<body>
  <div class="topbar">
    <a href="/admin/index.php">&larr; Zur&uuml;ck zum Dashboard</a>
    <a href="/admin/logout.php">Abmelden</a>
  </div>
  <div class="panel">
    <div class="panel-header">
      <h1>Projekte</h1>
      <a class="new-btn" href="/admin/projects-edit.php">+ Neues Projekt</a>
    </div>
    <?php if (empty($projects)): ?>
      <p class="empty">Noch keine Projekte vorhanden.</p>
    <?php else: ?>
      <table>
        <tr>
          <th>Titel</th>
          <th>Status</th>
          <th></th>
        </tr>
        <?php foreach ($projects as $project): ?>
          <tr>
            <td><?php echo htmlspecialchars($project['title']); ?></td>
            <td><?php echo htmlspecialchars(PROJECT_STATUSES[$project['status']] ?? $project['status']); ?></td>
            <td>
              <a href="/admin/projects-edit.php?id=<?php echo (int) $project['id']; ?>">Bearbeiten</a>
              <form method="post" action="/admin/projects-delete.php" style="display:inline" onsubmit="return confirm('Projekt wirklich löschen?');">
                <input type="hidden" name="id" value="<?php echo (int) $project['id']; ?>">
                <button type="submit" class="delete-btn">L&ouml;schen</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>
    <?php endif; ?>
  </div>
  <div class="panel">
    <div class="panel-header">
      <h1>Forschung</h1>
    </div>
    <form method="post">
      <label for="forschung_text">Text im Forschungsbereich der Projekte-Seite</label>
      <textarea id="forschung_text" name="forschung_text" placeholder="Publikationen, Berichte oder Links zur Forschung..."><?php echo htmlspecialchars($forschungText); ?></textarea>
      <button type="submit" class="save-btn">Speichern</button>
    </form>
  </div>
  <script src="../assets/js/block-editor.js"></script>
  <script src="../assets/js/focus-point.js"></script>
  <script>
    initBlockEditor('forschung_text');
  </script>
</body>
</html>
