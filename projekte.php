<?php
require __DIR__ . '/includes/content.php';
require __DIR__ . '/includes/projects.php';
$projects = getAllProjects();
$forschungText = getContentBlock('projekte', 'forschung_text', 'Unsere Forschungsbeiträge sind in Vorbereitung.');
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Projekte &ndash; Naturschutzspürhunde Schweiz</title>
  <link rel="stylesheet" href="/assets/css/variables.css">
  <link rel="stylesheet" href="/assets/css/site.css">
  <?php require __DIR__ . '/includes/link-style.php'; ?>
</head>
<body>
  <?php require __DIR__ . '/includes/header.php'; ?>

  <main style="max-width:1100px;margin:0 auto;padding:24px;">
    <h1>Projekte</h1>
    <?php if (empty($projects)): ?>
      <p class="teaser-empty">Unsere Projekte werden hier in Kürze vorgestellt.</p>
    <?php else: ?>
      <div class="teaser-grid">
        <?php foreach ($projects as $project): ?>
          <a class="teaser-card" href="/projekt.php?id=<?php echo (int) $project['id']; ?>" style="display:block;text-decoration:none;color:inherit;">
            <?php if (!empty($project['image'])): ?>
              <img src="/uploads/<?php echo htmlspecialchars($project['image']); ?>" alt="">
            <?php endif; ?>
            <?php if (!empty($project['status'])): ?>
              <span class="teaser-badge"><?php echo htmlspecialchars(PROJECT_STATUSES[$project['status']] ?? $project['status']); ?></span>
            <?php endif; ?>
            <h3><?php echo htmlspecialchars($project['title']); ?></h3>
            <p><?php echo htmlspecialchars(mb_strimwidth(renderPlainText($project['content'] ?? ''), 0, 140, '…')); ?></p>
          </a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <section>
      <h2>Forschung</h2>
      <div><?php echo renderRichText($forschungText); ?></div>
    </section>
  </main>

  <?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
