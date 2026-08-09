<?php
require __DIR__ . '/includes/content.php';
require __DIR__ . '/includes/news.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$item = $id > 0 ? getNewsById($id) : null;

if ($item === null) {
    http_response_code(404);
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $item !== null ? htmlspecialchars($item['title']) . ' &ndash; ' : ''; ?>News &ndash; Naturschutzspürhunde Schweiz</title>
  <link rel="stylesheet" href="/assets/css/variables.css">
  <link rel="stylesheet" href="/assets/css/site.css">
</head>
<body>
  <?php require __DIR__ . '/includes/header.php'; ?>

  <main style="max-width:800px;margin:0 auto;padding:24px;">
    <p><a href="/news.php">&larr; Zurück zu News</a></p>
    <?php if ($item === null): ?>
      <h1>Beitrag nicht gefunden</h1>
      <p class="teaser-empty">Dieser News-Beitrag existiert nicht oder wurde entfernt.</p>
    <?php else: ?>
      <?php if (!empty($item['image'])): ?>
        <img src="/uploads/<?php echo htmlspecialchars($item['image']); ?>" alt="" style="max-width:100%;border-radius:8px;display:block;margin-bottom:16px;">
      <?php endif; ?>
      <span class="teaser-badge"><?php echo htmlspecialchars(NEWS_CATEGORIES[$item['category']] ?? $item['category']); ?></span>
      <h1><?php echo htmlspecialchars($item['title']); ?></h1>
      <p><em><?php echo htmlspecialchars(date('d.m.Y', strtotime($item['published_at']))); ?></em></p>
      <p><?php echo renderRichText($item['content'] ?? ''); ?></p>
    <?php endif; ?>
  </main>

  <?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
