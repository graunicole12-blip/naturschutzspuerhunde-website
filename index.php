<?php
require __DIR__ . '/includes/content.php';
$vision = getContentBlock('startseite', 'vision', 'Vision folgt in Kürze.');
$nshText = getContentBlock('startseite', 'nsh_text', 'Naturschutzspürhunde sind speziell ausgebildete Hunde, die dank ihrer feinen Nase seltene, bedrohte oder invasive Arten aufspüren – schnell, zuverlässig und ohne die Natur zu stören. So unterstützen sie Naturschutzorganisationen dabei, wichtige Daten für den Schutz unserer Umwelt zu sammeln.');
$heroImage = getContentBlock('startseite', 'hero_image', '');
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
      <p><?php echo nl2br(htmlspecialchars($vision)); ?></p>
    </section>

    <section>
      <h2>Was sind Naturschutzspürhunde?</h2>
      <p><?php echo nl2br(htmlspecialchars($nshText)); ?></p>
    </section>
  </main>

  <?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
