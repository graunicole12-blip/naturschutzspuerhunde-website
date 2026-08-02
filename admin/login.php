<?php

require __DIR__ . '/includes/session.php';
require __DIR__ . '/../includes/db.php';

startSecureSession();

if (!empty($_SESSION['admin_id'])) {
    header('Location: /admin/index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = getDb()->prepare('SELECT id, password_hash FROM admin_users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = $user['id'];
        header('Location: /admin/index.php');
        exit;
    }

    $error = 'Benutzername oder Passwort falsch.';
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin-Login &ndash; Naturschutzsp&uuml;rhunde</title>
  <link rel="stylesheet" href="../assets/css/variables.css">
  <style>
    body { font-family: var(--font-text); background: var(--color-neutral-cream); display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
    .card { background: #fff; border-radius: 12px; padding: 28px 32px; width: 280px; box-sizing: border-box; }
    .logo { width: 56px; height: 56px; border-radius: 50%; display: block; margin: 0 auto 12px; }
    h1 { font-family: var(--font-titel); font-weight: 700; font-size: 20px; color: var(--color-primary); text-align: center; margin: 0 0 4px; }
    .subtitle { font-size: 12px; color: var(--color-secondary-khaki); text-align: center; margin: 0 0 20px; }
    label { font-size: 12px; color: var(--color-primary); display: block; margin-bottom: 4px; }
    input { width: 100%; box-sizing: border-box; margin-bottom: 12px; border: 1px solid var(--color-neutral-blue); border-radius: 6px; padding: 8px; font-size: 14px; }
    button { width: 100%; background: var(--color-accent-red); color: #fff; border: none; border-radius: 6px; height: 36px; font-size: 14px; font-weight: 500; cursor: pointer; }
    .error { color: var(--color-accent-red); font-size: 13px; margin: 0 0 12px; }
  </style>
</head>
<body>
  <div class="card">
    <img src="../assets/img/logo.png" alt="Vereinslogo" class="logo">
    <h1>Admin-Login</h1>
    <p class="subtitle">Naturschutzsp&uuml;rhunde Schweiz</p>
    <?php if ($error !== ''): ?>
      <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="post" novalidate>
      <label for="username">Benutzername</label>
      <input type="text" id="username" name="username" autocomplete="username" required>
      <label for="password">Passwort</label>
      <input type="password" id="password" name="password" autocomplete="current-password" required>
      <button type="submit">Anmelden</button>
    </form>
  </div>
</body>
</html>
