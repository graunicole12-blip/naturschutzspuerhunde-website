<?php
require __DIR__ . '/includes/maintenance.php';
checkMaintenanceMode();

require __DIR__ . '/includes/content.php';
require __DIR__ . '/includes/projects.php';
require __DIR__ . '/includes/news.php';
$vision = getContentBlock('startseite', 'vision', 'Vision folgt in Kürze.');
$nshText = getContentBlock('startseite', 'nsh_text', 'Naturschutzspürhunde sind speziell ausgebildete Hunde, die dank ihrer feinen Nase seltene, bedrohte oder invasive Arten aufspüren – schnell, zuverlässig und ohne die Natur zu stören. So unterstützen sie Naturschutzorganisationen dabei, wichtige Daten für den Schutz unserer Umwelt zu sammeln.');
$heroImage = getContentBlock('startseite', 'hero_image', '');
$ctaText = getContentBlock('startseite', 'cta_text', 'Unterstütze unser Crowdfunding-Projekt auf Lokalhelden – werde jetzt Fan und hilf uns beim Start!');
$ctaLink = getContentBlock('startseite', 'cta_link', 'https://www.lokalhelden.ch/naturschutzhunde');
$featuredProjects = getFeaturedProjects();
$latestNews = getLatestNews();
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Naturschutzspürhunde Schweiz</title>
  <link rel="stylesheet" href="/assets/css/variables.css">
  <link rel="stylesheet" href="/assets/css/site.css">
</head>
<body>
  <?php require __DIR__ . '/includes/header.php'; ?>

  <main style="max-width:1100px;margin:0 auto;padding:24px;">
    <?php if ($heroImage !== ''): ?>
      <img src="/uploads/<?php echo htmlspecialchars($heroImage); ?>" alt="" style="max-width:100%;border-radius:8px;display:block;margin-bottom:24px;">
    <?php endif; ?>

    <section>
      <h1>Vision</h1>
      <p><?php echo renderRichText($vision); ?></p>
    </section>

    <section>
      <h2>Was sind Naturschutzspürhunde?</h2>
      <p><?php echo renderRichText($nshText); ?></p>
    </section>

    <section>
      <h2>Aktuelle Projekte</h2>
      <?php if (empty($featuredProjects)): ?>
        <p class="teaser-empty">Unsere Projekte werden hier in Kürze vorgestellt.</p>
      <?php else: ?>
        <div class="teaser-grid">
          <?php foreach ($featuredProjects as $project): ?>
            <a class="teaser-card" href="/projekt.php?id=<?php echo (int) $project['id']; ?>" style="display:block;text-decoration:none;color:inherit;">
              <?php if (!empty($project['image'])): ?>
                <img src="/uploads/<?php echo htmlspecialchars($project['image']); ?>" alt="">
              <?php endif; ?>
              <h3><?php echo htmlspecialchars($project['title']); ?></h3>
              <p><?php echo htmlspecialchars(mb_strimwidth(trim(strip_tags($project['content'] ?? '')), 0, 140, '…')); ?></p>
            </a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </section>
  </main>

  <section class="cta-section">
    <h2>Unterstützen</h2>
    <p><?php echo renderRichText($ctaText); ?></p>
    <a class="cta-button" href="<?php echo htmlspecialchars($ctaLink); ?>" target="_blank" rel="noopener">Jetzt unterstützen</a>
  </section>

  <main style="max-width:1100px;margin:0 auto;padding:24px;">
    <section>
      <h2>Aktuelles</h2>
      <?php if (empty($latestNews)): ?>
        <p class="teaser-empty">Hier erscheinen bald aktuelle News.</p>
      <?php else: ?>
        <div class="teaser-grid">
          <?php foreach ($latestNews as $item): ?>
            <div class="teaser-card">
              <?php if (!empty($item['image'])): ?>
                <img src="/uploads/<?php echo htmlspecialchars($item['image']); ?>" alt="">
              <?php endif; ?>
              <span class="teaser-badge"><?php echo htmlspecialchars(NEWS_CATEGORIES[$item['category']] ?? $item['category']); ?></span>
              <h3><?php echo htmlspecialchars($item['title']); ?></h3>
              <p><?php echo htmlspecialchars(date('d.m.Y', strtotime($item['published_at']))); ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </section>
  </main>

  <?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
