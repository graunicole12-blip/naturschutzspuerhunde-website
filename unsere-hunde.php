<?php
require __DIR__ . '/includes/content.php';
require __DIR__ . '/includes/dogs.php';
$dogs = getAllDogs();
$activeDogs = [];
$pioneerDogs = [];
foreach ($dogs as $dog) {
    if ((int) $dog['is_active'] === 1) {
        $activeDogs[] = $dog;
    } else {
        $pioneerDogs[] = $dog;
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Unsere Hunde &ndash; Naturschutzspürhunde Schweiz</title>
  <link rel="stylesheet" href="/assets/css/variables.css">
  <link rel="stylesheet" href="/assets/css/site.css">
</head>
<body>
  <?php require __DIR__ . '/includes/header.php'; ?>

  <main style="max-width:1100px;margin:0 auto;padding:24px;">
    <h1>Unsere Hunde</h1>

    <?php if (empty($dogs)): ?>
      <p class="teaser-empty">Unsere Hundeprofile werden hier in Kürze vorgestellt.</p>
    <?php endif; ?>

    <?php if (!empty($activeDogs)): ?>
      <section>
        <h2>Im Einsatz</h2>
        <div class="teaser-grid">
          <?php foreach ($activeDogs as $dog): ?>
            <div class="teaser-card">
              <?php if (!empty($dog['image'])): ?>
                <img src="/uploads/<?php echo htmlspecialchars($dog['image']); ?>" alt="">
              <?php endif; ?>
              <h3><?php echo htmlspecialchars($dog['name']); ?></h3>
              <?php if (!empty($dog['einsatzgebiet'])): ?>
                <p><em><?php echo htmlspecialchars($dog['einsatzgebiet']); ?></em></p>
              <?php endif; ?>
              <?php if (!empty($dog['charakter'])): ?>
                <div><?php echo renderRichText($dog['charakter']); ?></div>
              <?php endif; ?>
              <?php if (!empty($dog['bio'])): ?>
                <div><?php echo renderRichText($dog['bio']); ?></div>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </section>
    <?php endif; ?>

    <?php if (!empty($pioneerDogs)): ?>
      <section>
        <h2>Wegbereiterinnen</h2>
        <div class="teaser-grid">
          <?php foreach ($pioneerDogs as $dog): ?>
            <div class="teaser-card">
              <?php if (!empty($dog['image'])): ?>
                <img src="/uploads/<?php echo htmlspecialchars($dog['image']); ?>" alt="">
              <?php endif; ?>
              <span class="teaser-badge">Wegbereiterin</span>
              <h3><?php echo htmlspecialchars($dog['name']); ?></h3>
              <?php if (!empty($dog['einsatzgebiet'])): ?>
                <p><em><?php echo htmlspecialchars($dog['einsatzgebiet']); ?></em></p>
              <?php endif; ?>
              <?php if (!empty($dog['charakter'])): ?>
                <div><?php echo renderRichText($dog['charakter']); ?></div>
              <?php endif; ?>
              <?php if (!empty($dog['bio'])): ?>
                <div><?php echo renderRichText($dog['bio']); ?></div>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </section>
    <?php endif; ?>
  </main>

  <?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
