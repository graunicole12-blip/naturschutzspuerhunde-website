<?php
require __DIR__ . '/includes/content.php';

$pageKey = 'naturschutzspuerhunde';
$wasSindText = getContentBlock($pageKey, 'was_sind_text', 'Text folgt in Kürze.');
$wieArbeitenText = getContentBlock($pageKey, 'wie_arbeiten_text', 'Text folgt in Kürze.');
$einsatzText = getContentBlock($pageKey, 'einsatzmoeglichkeiten_text', 'Text folgt in Kürze.');
$warumHundeText = getContentBlock($pageKey, 'warum_hunde_text', 'Text folgt in Kürze.');
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Naturschutzspürhunde &ndash; Naturschutzspürhunde Schweiz</title>
  <link rel="stylesheet" href="/assets/css/variables.css">
  <link rel="stylesheet" href="/assets/css/site.css">
  <?php require __DIR__ . '/includes/link-style.php'; ?>
</head>
<body>
  <?php require __DIR__ . '/includes/header.php'; ?>

  <main style="max-width:1100px;margin:0 auto;padding:24px;">
    <h1>Naturschutzspürhunde</h1>

    <section>
      <h2>Was sind Naturschutzspürhunde?</h2>
      <div><?php echo renderRichText($wasSindText); ?></div>
    </section>

    <section>
      <h2>Wie arbeiten sie?</h2>
      <div><?php echo renderRichText($wieArbeitenText); ?></div>
    </section>

    <section>
      <h2>Einsatzmöglichkeiten</h2>
      <div><?php echo renderRichText($einsatzText); ?></div>
      <p><a href="/projekte.php">Konkrete Einsätze in unseren Projekten ansehen &rarr;</a></p>
    </section>

    <section>
      <h2>Warum Hunde?</h2>
      <div><?php echo renderRichText($warumHundeText); ?></div>
    </section>
  </main>

  <?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
