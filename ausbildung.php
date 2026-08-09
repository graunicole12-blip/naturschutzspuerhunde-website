<?php
require __DIR__ . '/includes/content.php';

$pageKey = 'ausbildung';
$qualitaetText = getContentBlock($pageKey, 'qualitaetsstandards_text', 'Text folgt in Kürze.');
$zusammenarbeitText = getContentBlock($pageKey, 'internationale_zusammenarbeit_text', 'Text folgt in Kürze.');
$assessmentsText = getContentBlock($pageKey, 'assessments_text', 'Text folgt in Kürze.');
$weiterbildungText = getContentBlock($pageKey, 'weiterbildung_text', 'Text folgt in Kürze.');
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ausbildung &ndash; Naturschutzspürhunde Schweiz</title>
  <link rel="stylesheet" href="/assets/css/variables.css">
  <link rel="stylesheet" href="/assets/css/site.css">
  <?php require __DIR__ . '/includes/link-style.php'; ?>
</head>
<body>
  <?php require __DIR__ . '/includes/header.php'; ?>

  <main style="max-width:1100px;margin:0 auto;padding:24px;">
    <h1>Ausbildung</h1>

    <section>
      <h2>Qualitätsstandards</h2>
      <div><?php echo renderRichText($qualitaetText); ?></div>
    </section>

    <section>
      <h2>Internationale Zusammenarbeit</h2>
      <div><?php echo renderRichText($zusammenarbeitText); ?></div>
    </section>

    <section>
      <h2>Assessments</h2>
      <div><?php echo renderRichText($assessmentsText); ?></div>
    </section>

    <section>
      <h2>Weiterbildung</h2>
      <div><?php echo renderRichText($weiterbildungText); ?></div>
      <p>Fragen zur Weiterbildung? <a href="/kontakt.php">Kontaktiere uns</a>.</p>
    </section>
  </main>

  <?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
