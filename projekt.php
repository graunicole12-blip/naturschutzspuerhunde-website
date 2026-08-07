<?php
require __DIR__ . '/includes/projects.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$project = $id > 0 ? getProjectById($id) : null;

if ($project === null) {
    http_response_code(404);
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $project !== null ? htmlspecialchars($project['title']) . ' &ndash; ' : ''; ?>Projekte &ndash; Naturschutzspürhunde Schweiz</title>
  <link rel="stylesheet" href="/assets/css/variables.css">
  <link rel="stylesheet" href="/assets/css/site.css">
</head>
<body>
  <?php require __DIR__ . '/includes/header.php'; ?>

  <main style="max-width:800px;margin:0 auto;padding:24px;">
    <p><a href="/projekte.php">&larr; Zurück zu Projekte</a></p>
    <?php if ($project === null): ?>
      <h1>Projekt nicht gefunden</h1>
      <p class="teaser-empty">Dieses Projekt existiert nicht oder wurde entfernt.</p>
    <?php else: ?>
      <?php if (!empty($project['image'])): ?>
        <img src="/uploads/<?php echo htmlspecialchars($project['image']); ?>" alt="" style="max-width:100%;border-radius:8px;display:block;margin-bottom:16px;">
      <?php endif; ?>
      <?php if (!empty($project['status'])): ?>
        <span class="teaser-badge"><?php echo htmlspecialchars(PROJECT_STATUSES[$project['status']] ?? $project['status']); ?></span>
      <?php endif; ?>
      <h1><?php echo htmlspecialchars($project['title']); ?></h1>
      <p><?php echo nl2br(htmlspecialchars($project['content'] ?? '')); ?></p>
    <?php endif; ?>
  </main>

  <?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
