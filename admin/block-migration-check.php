<?php

require __DIR__ . '/includes/auth.php';
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/content.php';

requireLogin();

function formatOf(?string $raw): string
{
    $raw = trim($raw ?? '');
    if ($raw === '') {
        return 'leer';
    }
    return isBlockJson($raw) ? 'block-json' : 'legacy-html';
}

function previewOf(?string $raw): string
{
    $rendered = renderRichText($raw ?? '');
    $text = trim(strip_tags($rendered));
    return mb_strimwidth($text, 0, 160, '…');
}

$rows = [];

$stmt = getDb()->query('SELECT page_key, block_key, content FROM content_blocks ORDER BY page_key, block_key');
foreach ($stmt->fetchAll() as $r) {
    $rows[] = ['bereich' => $r['page_key'], 'feld' => $r['block_key'], 'content' => $r['content']];
}

$stmt = getDb()->query('SELECT id, name, bio, charakter FROM dogs ORDER BY id');
foreach ($stmt->fetchAll() as $r) {
    $rows[] = ['bereich' => 'Hund #' . $r['id'] . ' (' . $r['name'] . ')', 'feld' => 'bio', 'content' => $r['bio']];
    $rows[] = ['bereich' => 'Hund #' . $r['id'] . ' (' . $r['name'] . ')', 'feld' => 'charakter', 'content' => $r['charakter']];
}

$stmt = getDb()->query('SELECT id, title, content FROM projects ORDER BY id');
foreach ($stmt->fetchAll() as $r) {
    $rows[] = ['bereich' => 'Projekt #' . $r['id'] . ' (' . $r['title'] . ')', 'feld' => 'content', 'content' => $r['content']];
}

$stmt = getDb()->query('SELECT id, title, content FROM news ORDER BY id');
foreach ($stmt->fetchAll() as $r) {
    $rows[] = ['bereich' => 'News #' . $r['id'] . ' (' . $r['title'] . ')', 'feld' => 'content', 'content' => $r['content']];
}

$stmt = getDb()->query('SELECT id, name, bio FROM board_members ORDER BY id');
foreach ($stmt->fetchAll() as $r) {
    $rows[] = ['bereich' => 'Vorstand #' . $r['id'] . ' (' . $r['name'] . ')', 'feld' => 'bio', 'content' => $r['bio']];
}

$stmt = getDb()->query('SELECT id, name, description FROM partners ORDER BY id');
foreach ($stmt->fetchAll() as $r) {
    $rows[] = ['bereich' => 'Partner #' . $r['id'] . ' (' . $r['name'] . ')', 'feld' => 'description', 'content' => $r['description']];
}

$counts = ['leer' => 0, 'legacy-html' => 0, 'block-json' => 0];
foreach ($rows as $row) {
    $counts[formatOf($row['content'])]++;
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Block-Migration &ndash; Naturschutzsp&uuml;rhunde Admin</title>
  <link rel="stylesheet" href="../assets/css/variables.css">
  <style>
    body { font-family: var(--font-text); margin: 0; background: var(--color-neutral-cream); }
    .topbar { display: flex; justify-content: space-between; align-items: center; padding: 16px 32px; }
    .topbar a { color: var(--color-primary); font-size: 13px; text-decoration: none; }
    .panel { background: #fff; border-radius: 12px; margin: 0 32px 24px; padding: 20px; box-sizing: border-box; }
    h1 { font-family: var(--font-titel); color: var(--color-primary); font-size: 22px; margin: 0 0 8px; }
    .hint { font-size: 12px; color: var(--color-secondary-khaki); margin: 0 0 16px; }
    .summary { display: flex; gap: 16px; margin-bottom: 16px; }
    .summary span { font-size: 13px; background: var(--color-neutral-tan); color: var(--color-primary); padding: 4px 10px; border-radius: 10px; }
    table { width: 100%; border-collapse: collapse; font-size: 13px; }
    th { text-align: left; color: var(--color-secondary-khaki); font-weight: 500; padding: 8px; border-bottom: 1px solid var(--color-neutral-blue-light); }
    td { padding: 8px; border-bottom: 1px solid var(--color-neutral-blue-light); color: var(--color-primary); vertical-align: top; }
    .format-badge { display: inline-block; font-size: 11px; padding: 2px 8px; border-radius: 10px; }
    .format-leer { background: var(--color-neutral-blue-light, #eee); color: var(--color-secondary-khaki); }
    .format-legacy-html { background: var(--color-neutral-tan); color: var(--color-primary); }
    .format-block-json { background: var(--color-secondary-gold); color: #fff; }
    .preview { color: var(--color-secondary-khaki); }
  </style>
</head>
<body>
  <div class="topbar">
    <a href="/admin/index.php">&larr; Zur&uuml;ck zum Dashboard</a>
    <a href="/admin/logout.php">Abmelden</a>
  </div>
  <div class="panel">
    <h1>Block-Migration &ndash; Altbestand-Check</h1>
    <p class="hint">Übersicht aller Textfelder: welches Format sie aktuell haben und wie sie mit <code>renderRichText()</code> dargestellt werden. Legacy-HTML-Felder werden automatisch als ein einzelner Text-Block geladen, sobald sie im Block-Editor geöffnet und gespeichert werden &ndash; kein Datenverlust, keine manuelle Nacherfassung nötig.</p>
    <div class="summary">
      <span><?php echo $counts['block-json']; ?> Block-JSON</span>
      <span><?php echo $counts['legacy-html']; ?> Legacy-HTML</span>
      <span><?php echo $counts['leer']; ?> Leer</span>
    </div>
    <table>
      <tr>
        <th>Bereich</th>
        <th>Feld</th>
        <th>Format</th>
        <th>Vorschau (gerendert)</th>
      </tr>
      <?php foreach ($rows as $row): ?>
        <?php $format = formatOf($row['content']); ?>
        <tr>
          <td><?php echo htmlspecialchars($row['bereich']); ?></td>
          <td><?php echo htmlspecialchars($row['feld']); ?></td>
          <td><span class="format-badge format-<?php echo $format; ?>"><?php echo htmlspecialchars($format); ?></span></td>
          <td class="preview"><?php echo htmlspecialchars(previewOf($row['content'])); ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
  </div>
</body>
</html>
