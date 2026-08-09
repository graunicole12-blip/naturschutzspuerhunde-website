<?php

require __DIR__ . '/includes/auth.php';
require __DIR__ . '/../includes/db.php';

requireLogin();

$newsCount = (int) getDb()->query('SELECT COUNT(*) FROM news')->fetchColumn();
$dogsCount = (int) getDb()->query('SELECT COUNT(*) FROM dogs')->fetchColumn();
$projectsCount = (int) getDb()->query('SELECT COUNT(*) FROM projects')->fetchColumn();
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin-Dashboard &ndash; Naturschutzsp&uuml;rhunde</title>
  <link rel="stylesheet" href="../assets/css/variables.css">
  <style>
    body { font-family: var(--font-text); margin: 0; display: flex; min-height: 100vh; }
    .sidebar { width: 200px; background: var(--color-primary); flex-shrink: 0; padding: 20px 0; box-sizing: border-box; }
    .sidebar h2 { font-family: var(--font-titel); color: var(--color-neutral-cream); font-size: 15px; padding: 0 20px; margin: 0 0 20px; }
    .sidebar nav a { display: block; color: var(--color-neutral-tan); font-size: 13px; padding: 10px 20px; text-decoration: none; }
    .sidebar nav a:hover { background: var(--color-secondary-gold); color: #fff; }
    .content { flex: 1; background: var(--color-neutral-cream); padding: 24px 32px; box-sizing: border-box; }
    .content h1 { font-family: var(--font-titel); color: var(--color-primary); font-size: 24px; margin: 0 0 16px; }
    .logout { position: absolute; top: 20px; right: 32px; font-size: 13px; color: var(--color-primary); }
  </style>
</head>
<body>
  <div class="sidebar">
    <h2>NSH Admin</h2>
    <nav>
      <a href="/admin/edit.php">Startseite</a>
      <a href="/admin/about.php">&Uuml;ber uns</a>
      <a href="#">Naturschutzsp&uuml;rhunde</a>
      <a href="/admin/projects.php">Projekte</a>
      <a href="/admin/dogs.php">Unsere Hunde</a>
      <a href="/admin/ausbildung.php">Ausbildung</a>
      <a href="/admin/unterstuetzen.php">Unterst&uuml;tzen</a>
      <a href="/admin/news.php">News &amp; Kontakt</a>
      <a href="/admin/settings.php">Einstellungen</a>
    </nav>
  </div>
  <div class="content">
    <a class="logout" href="/admin/logout.php">Abmelden</a>
    <h1>&Uuml;bersicht</h1>
    <p>Dashboard-Grundger&uuml;st. Inhalte-Bearbeitung folgt in weiteren Ausbaustufen.</p>
    <p><?php echo $newsCount; ?> News-Beitr&auml;ge &middot; <a href="/admin/news.php">verwalten</a></p>
    <p><?php echo $dogsCount; ?> Hundeprofile &middot; <a href="/admin/dogs.php">verwalten</a></p>
    <p><?php echo $projectsCount; ?> Projekte &middot; <a href="/admin/projects.php">verwalten</a></p>
  </div>
</body>
</html>
