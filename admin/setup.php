<?php

require __DIR__ . '/includes/db.php';

$pdo = getDb();
$adminCount = (int) $pdo->query('SELECT COUNT(*) FROM admin_users')->fetchColumn();

if ($adminCount > 0) {
    http_response_code(403);
    echo 'Einrichtung bereits abgeschlossen.';
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '') {
        $error = 'Benutzername darf nicht leer sein.';
    } elseif (strlen($password) < 8) {
        $error = 'Passwort muss mindestens 8 Zeichen lang sein.';
    } else {
        $stmt = $pdo->prepare('INSERT INTO admin_users (username, password_hash) VALUES (?, ?)');
        $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT)]);
        header('Location: /admin/login.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin-Ersteinrichtung &ndash; Naturschutzsp&uuml;rhunde</title>
  <link rel="stylesheet" href="../assets/css/variables.css">
  <style>
    body { font-family: var(--font-text); background: var(--color-neutral-cream); display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
    .card { background: #fff; border-radius: 12px; padding: 28px 32px; width: 320px; box-sizing: border-box; }
    h1 { font-family: var(--font-titel); font-weight: 700; font-size: 20px; color: var(--color-primary); text-align: center; margin: 0 0 4px; }
    .subtitle { font-size: 12px; color: var(--color-secondary-khaki); text-align: center; margin: 0 0 20px; }
    label { font-size: 12px; color: var(--color-primary); display: block; margin-bottom: 4px; }
    input { width: 100%; box-sizing: border-box; margin-bottom: 12px; border: 1px solid var(--color-neutral-blue); border-radius: 6px; padding: 8px; font-size: 14px; }
    button { width: 100%; background: var(--color-accent-red); color: #fff; border: none; border-radius: 6px; height: 36px; font-size: 14px; font-weight: 500; cursor: pointer; }
    .error { color: var(--color-accent-red); font-size: 13px; margin: 0 0 12px; }
    .hint { font-size: 12px; color: var(--color-secondary-khaki); margin: 16px 0 0; }
  </style>
</head>
<body>
  <div class="card">
    <h1>Admin-Konto einrichten</h1>
    <p class="subtitle">Einmalige Ersteinrichtung</p>
    <?php if ($error !== ''): ?>
      <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="post" novalidate>
      <label for="username">Benutzername</label>
      <input type="text" id="username" name="username" autocomplete="username" required>
      <label for="password">Passwort (mind. 8 Zeichen)</label>
      <input type="password" id="password" name="password" autocomplete="new-password" minlength="8" required>
      <button type="submit">Konto erstellen</button>
    </form>
    <p class="hint">Diese Seite funktioniert nur einmal. Sobald ein Konto existiert, ist sie dauerhaft gesperrt.</p>
  </div>
</body>
</html>
