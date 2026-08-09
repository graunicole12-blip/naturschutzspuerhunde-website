<?php
require __DIR__ . '/includes/content.php';

const LOKALHELDEN_URL = 'https://www.lokalhelden.ch/naturschutzhunde';

$pageKey = 'unterstuetzen';
$spendenText = getContentBlock($pageKey, 'spenden_text', 'Mit deiner Spende ermöglichst du den Einsatz unserer Naturschutzspürhunde. Jeder Beitrag hilft.');
$mitgliedText = getContentBlock($pageKey, 'mitglied_text', 'Text folgt in Kürze.');
$sponsoringText = getContentBlock($pageKey, 'sponsoring_text', 'Text folgt in Kürze.');
$crowdfundingText = getContentBlock($pageKey, 'crowdfunding_text', 'Unterstütze unsere laufende Kampagne auf Lokalhelden und hilf uns beim Start.');
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Unterstützen &ndash; Naturschutzspürhunde Schweiz</title>
  <link rel="stylesheet" href="/assets/css/variables.css">
  <link rel="stylesheet" href="/assets/css/site.css">
</head>
<body>
  <?php require __DIR__ . '/includes/header.php'; ?>

  <main style="max-width:1100px;margin:0 auto;padding:24px;">
    <h1>Unterstützen</h1>

    <section>
      <h2>Spenden</h2>
      <p><?php echo renderRichText($spendenText); ?></p>
      <p><a class="cta-button" href="<?php echo htmlspecialchars(LOKALHELDEN_URL); ?>" target="_blank" rel="noopener">Jetzt spenden</a></p>
    </section>

    <section>
      <h2>Mitglied werden</h2>
      <p><?php echo renderRichText($mitgliedText); ?></p>
      <p><a href="/kontakt.php">Für eine Mitgliedschaft kontaktieren &rarr;</a></p>
    </section>

    <section>
      <h2>Sponsoring</h2>
      <p><?php echo renderRichText($sponsoringText); ?></p>
      <p><a href="/kontakt.php">Für Sponsoring kontaktieren &rarr;</a></p>
    </section>

    <section>
      <h2>Crowdfunding</h2>
      <p><?php echo renderRichText($crowdfundingText); ?></p>
      <p><a class="cta-button" href="<?php echo htmlspecialchars(LOKALHELDEN_URL); ?>" target="_blank" rel="noopener">Zur Lokalhelden-Kampagne</a></p>
    </section>
  </main>

  <?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
