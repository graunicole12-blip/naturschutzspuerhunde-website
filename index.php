<?php
require __DIR__ . '/includes/content.php';
$vision = getContentBlock('startseite', 'vision', 'Vision folgt in K&uuml;rze.');
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Naturschutzspürhunde</title>
</head>
<body>
  <h1>Naturschutzspürhunde</h1>
  <p>Testseite &ndash; Deployment-Pipeline aktiv.</p>
  <p>Generiert am: <?php echo date('d.m.Y H:i:s'); ?></p>
  <h2>Vision</h2>
  <p><?php echo nl2br(htmlspecialchars($vision)); ?></p>
</body>
</html>
