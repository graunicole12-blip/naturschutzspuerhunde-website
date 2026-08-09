<?php

require __DIR__ . '/includes/auth.php';
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/content.php';
require __DIR__ . '/../includes/upload.php';
require_once __DIR__ . '/../includes/sanitize-html.php';
require __DIR__ . '/../includes/board.php';
require __DIR__ . '/../includes/partners.php';

requireLogin();

$pageKey = 'ueber-uns';
$vereinTextKey = 'verein_text';
$visionMissionKey = 'vision_mission_text';
$statutenKey = 'statuten_document';
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verein_text'])) {
    $vereinText = sanitizeHtml(trim($_POST['verein_text']));
    $visionMissionText = sanitizeHtml(trim($_POST['vision_mission_text'] ?? ''));

    $stmt = getDb()->prepare(
        'INSERT INTO content_blocks (page_key, block_key, content) VALUES (?, ?, ?)
         ON DUPLICATE KEY UPDATE content = VALUES(content)'
    );
    $stmt->execute([$pageKey, $vereinTextKey, $vereinText]);
    $stmt->execute([$pageKey, $visionMissionKey, $visionMissionText]);

    $uploadResult = handleDocumentUpload($_FILES['statuten_document'] ?? [], __DIR__ . '/../uploads');
    if ($uploadResult['error'] !== null) {
        $error = $uploadResult['error'];
    } elseif ($uploadResult['success']) {
        $stmt->execute([$pageKey, $statutenKey, $uploadResult['filename']]);
    }

    $message = $error === '' ? 'Gespeichert.' : '';
}

$vereinText = getContentBlock($pageKey, $vereinTextKey, '');
$visionMissionText = getContentBlock($pageKey, $visionMissionKey, '');
$statutenDocument = getContentBlock($pageKey, $statutenKey, '');
$boardMembers = getAllBoardMembers();
$partners = getAllPartners();
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Über uns &ndash; Naturschutzsp&uuml;rhunde Admin</title>
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
    input[type="file"] { font-size: 13px; }
    button { background: var(--color-accent-red); color: #fff; border: none; border-radius: 6px; height: 36px; padding: 0 20px; font-size: 14px; font-weight: 500; cursor: pointer; margin-top: 16px; }
    .message { color: var(--color-secondary-gold); font-size: 13px; }
    .error { color: var(--color-accent-red); font-size: 13px; }
    .hint { font-size: 12px; color: var(--color-secondary-khaki); margin: 4px 0 0; }
    .current-document { font-size: 13px; margin: 4px 0 0; }
    .new-btn { background: var(--color-accent-red); color: #fff; border-radius: 6px; padding: 8px 16px; font-size: 13px; text-decoration: none; }
    table { width: 100%; border-collapse: collapse; font-size: 13px; }
    th { text-align: left; color: var(--color-secondary-khaki); font-weight: 500; padding: 8px; border-bottom: 1px solid var(--color-neutral-blue-light); }
    td { padding: 8px; border-bottom: 1px solid var(--color-neutral-blue-light); color: var(--color-primary); }
    td a { color: var(--color-secondary-gold); text-decoration: none; margin-right: 12px; }
    .delete-btn { background: none; border: none; color: var(--color-accent-red); font-size: 13px; cursor: pointer; padding: 0; }
    .empty { color: var(--color-secondary-khaki); font-size: 13px; }
  </style>
</head>
<body>
  <div class="topbar">
    <a href="/admin/index.php">&larr; Zur&uuml;ck zum Dashboard</a>
    <a href="/admin/logout.php">Abmelden</a>
  </div>
  <div class="panel">
    <div class="panel-header">
      <h1>Vereinstexte</h1>
    </div>
    <?php if ($message !== ''): ?>
      <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <?php if ($error !== ''): ?>
      <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data">
      <label for="verein_text">Der Verein</label>
      <textarea id="verein_text" name="verein_text" placeholder="Gründung, Zweck, Rechtsform..."><?php echo htmlspecialchars($vereinText); ?></textarea>

      <label for="vision_mission_text">Vision &amp; Mission</label>
      <textarea id="vision_mission_text" name="vision_mission_text" placeholder="Vision und Mission des Vereins..."><?php echo htmlspecialchars($visionMissionText); ?></textarea>

      <label for="statuten_document">Statuten-Dokument (PDF)</label>
      <?php if ($statutenDocument !== ''): ?>
        <p class="current-document">Aktuell: <a href="/uploads/<?php echo htmlspecialchars($statutenDocument); ?>" target="_blank" rel="noopener">Statuten ansehen</a></p>
      <?php endif; ?>
      <input type="file" id="statuten_document" name="statuten_document" accept="application/pdf">
      <p class="hint">PDF, max. 10 MB. Optional, ersetzt das aktuelle Dokument.</p>

      <button type="submit">Speichern</button>
    </form>
  </div>

  <div class="panel">
    <div class="panel-header">
      <h1>Vorstand</h1>
      <a class="new-btn" href="/admin/board-edit.php">+ Neu</a>
    </div>
    <?php if (empty($boardMembers)): ?>
      <p class="empty">Noch keine Vorstandsmitglieder vorhanden.</p>
    <?php else: ?>
      <table>
        <tr>
          <th>Name</th>
          <th>Funktion</th>
          <th></th>
        </tr>
        <?php foreach ($boardMembers as $member): ?>
          <tr>
            <td><?php echo htmlspecialchars($member['name']); ?></td>
            <td><?php echo htmlspecialchars($member['role'] ?? ''); ?></td>
            <td>
              <a href="/admin/board-edit.php?id=<?php echo (int) $member['id']; ?>">Bearbeiten</a>
              <form method="post" action="/admin/board-delete.php" style="display:inline" onsubmit="return confirm('Vorstandsmitglied wirklich löschen?');">
                <input type="hidden" name="id" value="<?php echo (int) $member['id']; ?>">
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
      <h1>Partner</h1>
      <a class="new-btn" href="/admin/partners-edit.php">+ Neu</a>
    </div>
    <?php if (empty($partners)): ?>
      <p class="empty">Noch keine Partner vorhanden.</p>
    <?php else: ?>
      <table>
        <tr>
          <th>Name</th>
          <th>Link</th>
          <th></th>
        </tr>
        <?php foreach ($partners as $partner): ?>
          <tr>
            <td><?php echo htmlspecialchars($partner['name']); ?></td>
            <td><?php echo htmlspecialchars($partner['link'] ?? ''); ?></td>
            <td>
              <a href="/admin/partners-edit.php?id=<?php echo (int) $partner['id']; ?>">Bearbeiten</a>
              <form method="post" action="/admin/partners-delete.php" style="display:inline" onsubmit="return confirm('Partner wirklich löschen?');">
                <input type="hidden" name="id" value="<?php echo (int) $partner['id']; ?>">
                <button type="submit" class="delete-btn">L&ouml;schen</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>
    <?php endif; ?>
  </div>

  <script src="../assets/js/block-editor.js"></script>
  <script>
    initBlockEditor('verein_text');
    initBlockEditor('vision_mission_text');
  </script>
</body>
</html>
