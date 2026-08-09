<?php
require __DIR__ . '/includes/content.php';
require __DIR__ . '/includes/board.php';
require __DIR__ . '/includes/partners.php';

$pageKey = 'ueber-uns';
$vereinText = getContentBlock($pageKey, 'verein_text', 'Text folgt in Kürze.');
$visionMissionText = getContentBlock($pageKey, 'vision_mission_text', 'Text folgt in Kürze.');
$statutenDocument = getContentBlock($pageKey, 'statuten_document', '');
$boardMembers = getAllBoardMembers();
$partners = getAllPartners();
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Über uns &ndash; Naturschutzspürhunde Schweiz</title>
  <link rel="stylesheet" href="/assets/css/variables.css">
  <link rel="stylesheet" href="/assets/css/site.css">
  <?php require __DIR__ . '/includes/link-style.php'; ?>
</head>
<body>
  <?php require __DIR__ . '/includes/header.php'; ?>

  <main style="max-width:1100px;margin:0 auto;padding:24px;">
    <h1>Über uns</h1>

    <section>
      <h2>Der Verein</h2>
      <div><?php echo renderRichText($vereinText); ?></div>
      <?php if ($statutenDocument !== ''): ?>
        <p><a href="/uploads/<?php echo htmlspecialchars($statutenDocument); ?>" target="_blank" rel="noopener">Statuten herunterladen (PDF)</a></p>
      <?php endif; ?>
    </section>

    <section>
      <h2>Vision &amp; Mission</h2>
      <div><?php echo renderRichText($visionMissionText); ?></div>
    </section>

    <section>
      <h2>Vorstand</h2>
      <?php if (empty($boardMembers)): ?>
        <p class="teaser-empty">Der Vorstand wird hier in Kürze vorgestellt.</p>
      <?php else: ?>
        <div class="teaser-grid">
          <?php foreach ($boardMembers as $member): ?>
            <div class="teaser-card">
              <?php if (!empty($member['image'])): ?>
                <img src="/uploads/<?php echo htmlspecialchars($member['image']); ?>" alt="">
              <?php endif; ?>
              <h3><?php echo htmlspecialchars($member['name']); ?></h3>
              <?php if (!empty($member['role'])): ?>
                <span class="teaser-badge"><?php echo htmlspecialchars($member['role']); ?></span>
              <?php endif; ?>
              <div><?php echo renderRichText($member['bio'] ?? ''); ?></div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </section>

    <section>
      <h2>Partner</h2>
      <?php if (empty($partners)): ?>
        <p class="teaser-empty">Unsere Partner werden hier in Kürze vorgestellt.</p>
      <?php else: ?>
        <div class="teaser-grid">
          <?php foreach ($partners as $partner): ?>
            <div class="teaser-card">
              <?php if (!empty($partner['logo'])): ?>
                <img src="/uploads/<?php echo htmlspecialchars($partner['logo']); ?>" alt="">
              <?php endif; ?>
              <h3><?php echo htmlspecialchars($partner['name']); ?></h3>
              <div><?php echo renderRichText($partner['description'] ?? ''); ?></div>
              <?php if (!empty($partner['link'])): ?>
                <p><a href="<?php echo htmlspecialchars($partner['link']); ?>" target="_blank" rel="noopener">Website besuchen</a></p>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </section>
  </main>

  <?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
