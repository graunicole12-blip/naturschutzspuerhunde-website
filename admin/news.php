<?php

require __DIR__ . '/includes/auth.php';
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/news.php';

requireLogin();

$newsItems = getDb()->query('SELECT id, title, category, published_at FROM news ORDER BY published_at DESC, id DESC')->fetchAll();
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>News-Beiträge &ndash; Naturschutzsp&uuml;rhunde Admin</title>
  <link rel="stylesheet" href="../assets/css/variables.css">
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
  </style>
</head>
<body>
  <div class="topbar">
    <a href="/admin/index.php">&larr; Zur&uuml;ck zum Dashboard</a>
    <a href="/admin/logout.php">Abmelden</a>
  </div>
  <div class="panel">
    <div class="panel-header">
      <h1>News-Beitr&auml;ge</h1>
      <a class="new-btn" href="/admin/news-edit.php">+ Neuer Beitrag</a>
    </div>
    <?php if (empty($newsItems)): ?>
      <p class="empty">Noch keine News-Beitr&auml;ge vorhanden.</p>
    <?php else: ?>
      <table>
        <tr>
          <th>Titel</th>
          <th>Kategorie</th>
          <th>Datum</th>
          <th></th>
        </tr>
        <?php foreach ($newsItems as $item): ?>
          <tr>
            <td><?php echo htmlspecialchars($item['title']); ?></td>
            <td><?php echo htmlspecialchars(NEWS_CATEGORIES[$item['category']] ?? $item['category']); ?></td>
            <td><?php echo htmlspecialchars($item['published_at']); ?></td>
            <td>
              <a href="/admin/news-edit.php?id=<?php echo (int) $item['id']; ?>">Bearbeiten</a>
              <form method="post" action="/admin/news-delete.php" style="display:inline" onsubmit="return confirm('Beitrag wirklich löschen?');">
                <input type="hidden" name="id" value="<?php echo (int) $item['id']; ?>">
                <button type="submit" class="delete-btn">L&ouml;schen</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>
    <?php endif; ?>
  </div>
</body>
</html>
