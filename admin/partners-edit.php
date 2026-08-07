<?php

require __DIR__ . '/includes/auth.php';
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/upload.php';
require __DIR__ . '/../includes/sanitize-html.php';

requireLogin();

$id = isset($_GET['id']) ? (int) $_GET['id'] : (isset($_POST['id']) ? (int) $_POST['id'] : 0);
$error = '';

$name = '';
$description = '';
$link = '';
$logo = '';
$sortOrder = 0;

if ($id > 0) {
    $stmt = getDb()->prepare('SELECT * FROM partners WHERE id = ?');
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if ($row) {
        $name = $row['name'];
        $description = $row['description'] ?? '';
        $link = $row['link'] ?? '';
        $logo = $row['logo'] ?? '';
        $sortOrder = (int) $row['sort_order'];
    } else {
        $id = 0;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = sanitizeHtml(trim($_POST['description'] ?? ''));
    $link = trim($_POST['link'] ?? '');
    $sortOrder = (int) ($_POST['sort_order'] ?? 0);

    if ($name === '') {
        $error = 'Name darf nicht leer sein.';
    }

    if ($error === '') {
        $uploadResult = handleImageUpload($_FILES['logo'] ?? [], __DIR__ . '/../uploads');
        if ($uploadResult['error'] !== null) {
            $error = $uploadResult['error'];
        } elseif ($uploadResult['success']) {
            $logo = $uploadResult['filename'];
        }
    }

    if ($error === '') {
        if ($id > 0) {
            $stmt = getDb()->prepare(
                'UPDATE partners SET name = ?, description = ?, link = ?, logo = ?, sort_order = ? WHERE id = ?'
            );
            $stmt->execute([$name, $description, $link, $logo, $sortOrder, $id]);
        } else {
            $stmt = getDb()->prepare(
                'INSERT INTO partners (name, description, link, logo, sort_order) VALUES (?, ?, ?, ?, ?)'
            );
            $stmt->execute([$name, $description, $link, $logo, $sortOrder]);
        }
        header('Location: /admin/about.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $id > 0 ? 'Partner bearbeiten' : 'Neuer Partner'; ?> &ndash; Naturschutzsp&uuml;rhunde Admin</title>
  <link rel="stylesheet" href="../assets/css/variables.css">
  <link rel="stylesheet" href="../assets/css/wysiwyg.css">
  <style>
    body { font-family: var(--font-text); margin: 0; background: var(--color-neutral-cream); }
    .topbar { display: flex; justify-content: space-between; align-items: center; padding: 16px 32px; }
    .topbar a { color: var(--color-primary); font-size: 13px; text-decoration: none; }
    .panel { background: #fff; border-radius: 12px; margin: 0 32px 32px; padding: 20px; max-width: 480px; box-sizing: border-box; }
    h1 { font-family: var(--font-titel); color: var(--color-primary); font-size: 22px; margin: 0 0 16px; }
    label { display: block; font-size: 12px; color: var(--color-primary); margin: 16px 0 4px; }
    label:first-of-type { margin-top: 0; }
    input[type="text"], input[type="number"], textarea { width: 100%; box-sizing: border-box; border: 1px solid var(--color-neutral-blue); border-radius: 6px; padding: 8px; font-size: 14px; font-family: var(--font-text); }
    textarea { min-height: 90px; }
    input[type="file"] { font-size: 13px; }
    button { background: var(--color-accent-red); color: #fff; border: none; border-radius: 6px; height: 36px; padding: 0 20px; font-size: 14px; font-weight: 500; cursor: pointer; margin-top: 16px; }
    .error { color: var(--color-accent-red); font-size: 13px; }
    .current-image { max-width: 200px; display: block; margin-top: 8px; border-radius: 6px; }
    .hint { font-size: 12px; color: var(--color-secondary-khaki); margin: 4px 0 0; }
  </style>
</head>
<body>
  <div class="topbar">
    <a href="/admin/about.php">&larr; Zur&uuml;ck zu &Uuml;ber uns</a>
    <a href="/admin/logout.php">Abmelden</a>
  </div>
  <div class="panel">
    <h1><?php echo $id > 0 ? 'Partner bearbeiten' : 'Neuer Partner'; ?></h1>
    <?php if ($error !== ''): ?>
      <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?php echo (int) $id; ?>">

      <label for="name">Name</label>
      <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>

      <label for="description">Kurzbeschrieb</label>
      <textarea id="description" name="description"><?php echo htmlspecialchars($description); ?></textarea>

      <label for="link">Link</label>
      <input type="text" id="link" name="link" value="<?php echo htmlspecialchars($link); ?>" placeholder="https://...">

      <label for="logo">Logo</label>
      <?php if ($logo !== ''): ?>
        <img class="current-image" src="/uploads/<?php echo htmlspecialchars($logo); ?>" alt="Aktuelles Logo">
      <?php endif; ?>
      <input type="file" id="logo" name="logo" accept="image/jpeg,image/png,image/webp">
      <p class="hint">JPEG, PNG oder WebP, max. 5 MB. Optional.</p>

      <label for="sort_order">Reihenfolge</label>
      <input type="number" id="sort_order" name="sort_order" value="<?php echo (int) $sortOrder; ?>">

      <button type="submit">Speichern</button>
    </form>
  </div>

  <script src="../assets/js/wysiwyg.js"></script>
  <script>
    initWysiwyg('description');
  </script>
</body>
</html>
