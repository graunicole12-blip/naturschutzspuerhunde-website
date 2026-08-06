<?php
$maintenanceText = getSetting(
    'maintenance_text',
    'Wir bauen gerade an unserer neuen Webseite. Bitte schau in Kürze wieder vorbei!'
);
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Wartungsarbeiten &ndash; Naturschutzsp&uuml;rhunde</title>
  <link rel="stylesheet" href="/assets/css/variables.css">
  <style>
    body { font-family: var(--font-text); background: var(--color-neutral-cream); display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; padding: 24px; box-sizing: border-box; }
    .card { background: #fff; border-radius: 12px; padding: 32px; width: 360px; max-width: 100%; box-sizing: border-box; text-align: center; }
    .logo { width: 64px; height: 64px; border-radius: 50%; display: block; margin: 0 auto 16px; }
    h1 { font-family: var(--font-titel); font-weight: 700; font-size: 24px; color: var(--color-primary); margin: 0 0 4px; }
    .subtitle { font-size: 13px; color: var(--color-secondary-khaki); margin: 0 0 20px; }
    .message { font-size: 14px; color: var(--color-primary); line-height: 1.5; margin: 0; }
  </style>
</head>
<body>
  <div class="card">
    <img src="/assets/img/logo.png" alt="Vereinslogo" class="logo">
    <h1>Wir sind kurz nicht da</h1>
    <p class="subtitle">Naturschutzsp&uuml;rhunde Schweiz</p>
    <p class="message"><?php echo nl2br(htmlspecialchars($maintenanceText)); ?></p>
  </div>
</body>
</html>
