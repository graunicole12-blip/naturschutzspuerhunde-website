<?php
require __DIR__ . '/includes/maintenance.php';
checkMaintenanceMode();

require __DIR__ . '/includes/content.php';
require __DIR__ . '/includes/projects.php';
require __DIR__ . '/includes/news.php';
require __DIR__ . '/includes/dogs.php';
$vision = getContentBlock('startseite', 'vision');
$nshText = getContentBlock('startseite', 'nsh_text');
$heroImage = getContentBlock('startseite', 'hero_image', '');
$heroImageMaxHeight = clampCardLimit(getContentBlock('startseite', 'hero_image_max_height', ''), 480, 100, 1200);
$heroImageAlign = clampAlignment(getContentBlock('startseite', 'hero_image_align', ''));
$ausbildungTeaserText = getContentBlock('startseite', 'ausbildung_teaser_text', 'Qualitätsstandards, internationale Zusammenarbeit, Assessments und Weiterbildung – wie wir unsere Hundeteams professionell ausbilden und weiterentwickeln.');
$ctaText = getContentBlock('startseite', 'cta_text', 'Unterstütze unser Crowdfunding-Projekt auf Lokalhelden – werde jetzt Fan und hilf uns beim Start!');
$ctaLink = getContentBlock('startseite', 'cta_link', 'https://www.lokalhelden.ch/naturschutzhunde');
$projekteAnzahl = clampCardLimit(getContentBlock('startseite', 'projekte_anzahl', ''));
$hundeAnzahl = clampCardLimit(getContentBlock('startseite', 'hunde_anzahl', ''));
$newsAnzahl = clampCardLimit(getContentBlock('startseite', 'news_anzahl', ''));
$featuredProjects = getFeaturedProjects($projekteAnzahl);
$latestNews = getLatestNews($newsAnzahl);
$activeDogs = getActiveDogs($hundeAnzahl);
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
      <img src="/uploads/<?php echo htmlspecialchars($heroImage); ?>" alt="" style="max-width:100%;max-height:<?php echo (int) $heroImageMaxHeight; ?>px;border-radius:8px;display:block;margin-bottom:24px;<?php echo heroImageAlignStyle($heroImageAlign); ?>">
    <?php endif; ?>

    <section>
      <h1>Vision</h1>
      <div><?php echo renderRichText($vision); ?></div>
      <p><a href="/ueber-uns.php">Mehr über uns &rarr;</a></p>
    </section>

    <section>
      <h2>Was sind Naturschutzspürhunde?</h2>
      <div><?php echo renderRichText($nshText); ?></div>
      <p><a href="/naturschutzspuerhunde.php">Mehr erfahren &rarr;</a></p>
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
              <p><?php echo htmlspecialchars(mb_strimwidth(renderPlainText($project['content'] ?? ''), 0, 140, '…')); ?></p>
            </a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </section>

    <section>
      <h2>Unsere Hunde</h2>
      <?php if (empty($activeDogs)): ?>
        <p class="teaser-empty">Unsere Hundeprofile werden hier in Kürze vorgestellt.</p>
      <?php else: ?>
        <div class="teaser-grid">
          <?php foreach ($activeDogs as $dog): ?>
            <a class="teaser-card" href="/unsere-hunde.php" style="display:block;text-decoration:none;color:inherit;">
              <?php if (!empty($dog['image'])): ?>
                <img src="/uploads/<?php echo htmlspecialchars($dog['image']); ?>" alt="">
              <?php endif; ?>
              <h3><?php echo htmlspecialchars($dog['name']); ?></h3>
              <?php if (!empty($dog['einsatzgebiet'])): ?>
                <p><em><?php echo htmlspecialchars($dog['einsatzgebiet']); ?></em></p>
              <?php endif; ?>
            </a>
          <?php endforeach; ?>
        </div>
        <p><a href="/unsere-hunde.php">Alle Hunde kennenlernen &rarr;</a></p>
      <?php endif; ?>
    </section>

    <section>
      <h2>Ausbildung</h2>
      <div><?php echo renderRichText($ausbildungTeaserText); ?></div>
      <p><a href="/ausbildung.php">Mehr zur Ausbildung &rarr;</a></p>
    </section>
  </main>

  <section class="cta-section">
    <h2>Unterstützen</h2>
    <div><?php echo renderRichText($ctaText); ?></div>
    <a class="cta-button" href="<?php echo htmlspecialchars($ctaLink); ?>" target="_blank" rel="noopener">Jetzt unterstützen</a>
    <p style="margin-top:12px;"><a href="/unterstuetzen.php" style="color:var(--color-neutral-tan);">Alle Unterstützungswege ansehen &rarr;</a></p>
  </section>

  <main style="max-width:1100px;margin:0 auto;padding:24px;">
    <section>
      <h2>Aktuelles</h2>
      <?php if (empty($latestNews)): ?>
        <p class="teaser-empty">Hier erscheinen bald aktuelle News.</p>
      <?php else: ?>
        <div class="teaser-grid">
          <?php foreach ($latestNews as $item): ?>
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
        <p><a href="/news.php">Alle News ansehen &rarr;</a></p>
      <?php endif; ?>
    </section>
  </main>

  <?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
