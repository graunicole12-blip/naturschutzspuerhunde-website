<?php
require __DIR__ . '/includes/news.php';
$newsItems = getAllNews();
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>News &ndash; Naturschutzspürhunde Schweiz</title>
  <link rel="stylesheet" href="/assets/css/variables.css">
  <link rel="stylesheet" href="/assets/css/site.css">
</head>
<body>
  <?php require __DIR__ . '/includes/header.php'; ?>

  <main style="max-width:1100px;margin:0 auto;padding:24px;">
    <h1>News</h1>
    <?php if (empty($newsItems)): ?>
      <p class="teaser-empty">Hier erscheinen bald aktuelle News.</p>
    <?php else: ?>
      <div class="teaser-grid">
        <?php foreach ($newsItems as $item): ?>
          <a class="teaser-card" href="/news-beitrag.php?id=<?php echo (int) $item['id']; ?>" style="display:block;text-decoration:none;color:inherit;">
            <?php if (!empty($item['image'])): ?>
              <img src="/uploads/<?php echo htmlspecialchars($item['image']); ?>" alt="">
            <?php endif; ?>
            <span class="teaser-badge"><?php echo htmlspecialchars(NEWS_CATEGORIES[$item['category']] ?? $item['category']); ?></span>
            <h3><?php echo htmlspecialchars($item['title']); ?></h3>
            <p><?php echo htmlspecialchars(date('d.m.Y', strtotime($item['published_at']))); ?></p>
          </a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </main>

  <?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
