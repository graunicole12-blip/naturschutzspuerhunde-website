<?php
$config = json_decode(file_get_contents(__DIR__ . '/config.json'), true);

try {
    $pdo = new PDO(
        'mysql:host=' . $config['DB_HOST'] . ';dbname=' . $config['DB_NAME'] . ';charset=utf8mb4',
        $config['DB_USER'],
        $config['DB_PASSWORD'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    $pdo->query('SELECT 1');
    $status = 'Datenbankverbindung erfolgreich.';
} catch (Exception $e) {
    $status = 'Datenbankverbindung fehlgeschlagen: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Datenbank-Test</title>
</head>
<body>
  <h1>Datenbank-Test</h1>
  <p><?php echo htmlspecialchars($status); ?></p>
  <p>Geprüft am: <?php echo date('d.m.Y H:i:s'); ?></p>
</body>
</html>
