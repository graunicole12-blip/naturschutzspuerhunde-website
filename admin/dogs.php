<?php

require __DIR__ . '/includes/auth.php';
require __DIR__ . '/../includes/db.php';

requireLogin();

$dogs = getDb()->query('SELECT id, name, einsatzgebiet, is_active FROM dogs ORDER BY sort_order ASC, name ASC')->fetchAll();
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hundeprofile &ndash; Naturschutzsp&uuml;rhunde Admin</title>
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
    .badge { font-size: 11px; padding: 2px 8px; border-radius: 10px; }
    .badge-active { background: var(--color-neutral-tan); color: var(--color-primary); }
    .badge-inactive { background: var(--color-neutral-blue-light); color: var(--color-primary); }
  </style>
</head>
<body>
  <div class="topbar">
    <a href="/admin/index.php">&larr; Zur&uuml;ck zum Dashboard</a>
    <a href="/admin/logout.php">Abmelden</a>
  </div>
  <div class="panel">
    <div class="panel-header">
      <h1>Hundeprofile</h1>
      <a class="new-btn" href="/admin/dogs-edit.php">+ Neues Profil</a>
    </div>
    <?php if (empty($dogs)): ?>
      <p class="empty">Noch keine Hundeprofile vorhanden.</p>
    <?php else: ?>
      <table>
        <tr>
          <th>Name</th>
          <th>Einsatzgebiet</th>
          <th>Status</th>
          <th></th>
        </tr>
        <?php foreach ($dogs as $dog): ?>
          <tr>
            <td><?php echo htmlspecialchars($dog['name']); ?></td>
            <td><?php echo htmlspecialchars($dog['einsatzgebiet'] ?? ''); ?></td>
            <td>
              <?php if ((int) $dog['is_active'] === 1): ?>
                <span class="badge badge-active">Aktiv</span>
              <?php else: ?>
                <span class="badge badge-inactive">Wegbereiterin</span>
              <?php endif; ?>
            </td>
            <td>
              <a href="/admin/dogs-edit.php?id=<?php echo (int) $dog['id']; ?>">Bearbeiten</a>
              <form method="post" action="/admin/dogs-delete.php" style="display:inline" onsubmit="return confirm('Profil wirklich löschen?');">
                <input type="hidden" name="id" value="<?php echo (int) $dog['id']; ?>">
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
