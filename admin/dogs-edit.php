<?php

require __DIR__ . '/includes/auth.php';
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/upload.php';
require __DIR__ . '/../includes/sanitize-html.php';

requireLogin();

$id = isset($_GET['id']) ? (int) $_GET['id'] : (isset($_POST['id']) ? (int) $_POST['id'] : 0);
$error = '';

$name = '';
$bio = '';
$einsatzgebiet = '';
$charakter = '';
$image = '';
$isActive = true;
$sortOrder = 0;

if ($id > 0) {
    $stmt = getDb()->prepare('SELECT * FROM dogs WHERE id = ?');
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if ($row) {
        $name = $row['name'];
        $bio = $row['bio'] ?? '';
        $einsatzgebiet = $row['einsatzgebiet'] ?? '';
        $charakter = $row['charakter'] ?? '';
        $image = $row['image'] ?? '';
        $isActive = (int) $row['is_active'] === 1;
        $sortOrder = (int) $row['sort_order'];
    } else {
        $id = 0;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_image']) && $id > 0) {
    $stmt = getDb()->prepare('UPDATE dogs SET image = ? WHERE id = ?');
    $stmt->execute(['', $id]);
    header('Location: /admin/dogs-edit.php?id=' . $id);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $bio = sanitizeHtml(trim($_POST['bio'] ?? ''));
    $einsatzgebiet = trim($_POST['einsatzgebiet'] ?? '');
    $charakter = sanitizeHtml(trim($_POST['charakter'] ?? ''));
    $isActive = isset($_POST['is_active']);
    $sortOrder = (int) ($_POST['sort_order'] ?? 0);

    if ($name === '') {
        $error = 'Name darf nicht leer sein.';
    }

    if ($error === '') {
        $uploadResult = handleImageUpload($_FILES['image'] ?? [], __DIR__ . '/../uploads');
        if ($uploadResult['error'] !== null) {
            $error = $uploadResult['error'];
        } elseif ($uploadResult['success']) {
            $image = $uploadResult['filename'];
        }
    }

    if ($error === '') {
        if ($id > 0) {
            $stmt = getDb()->prepare(
                'UPDATE dogs SET name = ?, bio = ?, einsatzgebiet = ?, charakter = ?, image = ?, is_active = ?, sort_order = ? WHERE id = ?'
            );
            $stmt->execute([$name, $bio, $einsatzgebiet, $charakter, $image, $isActive ? 1 : 0, $sortOrder, $id]);
        } else {
            $stmt = getDb()->prepare(
                'INSERT INTO dogs (name, bio, einsatzgebiet, charakter, image, is_active, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?)'
            );
            $stmt->execute([$name, $bio, $einsatzgebiet, $charakter, $image, $isActive ? 1 : 0, $sortOrder]);
        }
        header('Location: /admin/dogs.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $id > 0 ? 'Profil bearbeiten' : 'Neues Profil'; ?> &ndash; Naturschutzsp&uuml;rhunde Admin</title>
  <link rel="stylesheet" href="../assets/css/variables.css">
  <link rel="stylesheet" href="../assets/css/wysiwyg.css">
  <style>
    body { font-family: var(--font-text); margin: 0; background: var(--color-neutral-cream); }
    .topbar { display: flex; justify-content: space-between; align-items: center; padding: 16px 32px; }
    .topbar a { color: var(--color-primary); font-size: 13px; text-decoration: none; }
    .layout { display: flex; gap: 24px; margin: 0 32px 32px; align-items: flex-start; }
    .panel { background: #fff; border-radius: 12px; padding: 20px; max-width: 480px; box-sizing: border-box; flex: 1; }
    h1 { font-family: var(--font-titel); color: var(--color-primary); font-size: 22px; margin: 0 0 16px; }
    label { display: block; font-size: 12px; color: var(--color-primary); margin: 16px 0 4px; }
    label:first-of-type { margin-top: 0; }
    input[type="text"], input[type="number"], textarea { width: 100%; box-sizing: border-box; border: 1px solid var(--color-neutral-blue); border-radius: 6px; padding: 8px; font-size: 14px; font-family: var(--font-text); }
    textarea { min-height: 90px; }
    input[type="file"] { font-size: 13px; }
    .checkbox-row { display: flex; align-items: center; gap: 8px; margin: 16px 0 0; }
    .checkbox-row label { margin: 0; }
    button { background: var(--color-accent-red); color: #fff; border: none; border-radius: 6px; height: 36px; padding: 0 20px; font-size: 14px; font-weight: 500; cursor: pointer; margin-top: 16px; }
    .error { color: var(--color-accent-red); font-size: 13px; }
    .current-image { max-width: 200px; display: block; margin-top: 8px; border-radius: 6px; }
    .delete-image-btn { background: var(--color-secondary-gold); margin-top: 8px; margin-left: 8px; height: 30px; padding: 0 14px; font-size: 13px; }
    .hint { font-size: 12px; color: var(--color-secondary-khaki); margin: 4px 0 0; }

    .preview-panel { flex: 1; max-width: 360px; }
    .preview-label { font-size: 12px; color: var(--color-secondary-khaki); margin: 0 0 8px; }
    .preview-card { background: #fff; border-radius: 12px; padding: 16px; box-sizing: border-box; }
    .preview-card img { max-width: 100%; border-radius: 6px; display: block; margin-bottom: 12px; }
    .preview-badge { display: inline-block; background: var(--color-neutral-tan); color: var(--color-primary); font-size: 11px; padding: 2px 8px; border-radius: 10px; margin-bottom: 6px; }
    .preview-card h2 { font-family: var(--font-titel); color: var(--color-primary); font-size: 18px; margin: 0 0 4px; }
    .preview-meta { font-size: 12px; color: var(--color-secondary-khaki); margin: 0 0 8px; }
    .preview-card p { font-size: 13px; color: var(--color-primary); white-space: pre-wrap; margin: 0 0 8px; }
  </style>
</head>
<body>
  <div class="topbar">
    <a href="/admin/dogs.php">&larr; Zur&uuml;ck zur &Uuml;bersicht</a>
    <a href="/admin/logout.php">Abmelden</a>
  </div>
  <div class="layout">
    <div class="panel">
      <h1><?php echo $id > 0 ? 'Profil bearbeiten' : 'Neues Profil'; ?></h1>
      <?php if ($error !== ''): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
      <?php endif; ?>
      <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo (int) $id; ?>">

        <label for="name">Name</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>

        <label for="einsatzgebiet">Einsatzgebiet</label>
        <input type="text" id="einsatzgebiet" name="einsatzgebiet" value="<?php echo htmlspecialchars($einsatzgebiet); ?>">

        <label for="charakter">Charakter</label>
        <textarea id="charakter" name="charakter"><?php echo htmlspecialchars($charakter); ?></textarea>

        <label for="bio">Steckbrief</label>
        <textarea id="bio" name="bio"><?php echo htmlspecialchars($bio); ?></textarea>

        <label for="image">Bild</label>
        <?php if ($image !== ''): ?>
          <img class="current-image" src="/uploads/<?php echo htmlspecialchars($image); ?>" alt="Aktuelles Bild">
        <?php endif; ?>
        <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp">
        <p class="hint">JPEG, PNG oder WebP, max. 5 MB. Optional.</p>
        <?php if ($image !== ''): ?>
          <button type="submit" name="delete_image" value="1" class="delete-image-btn" formnovalidate onclick="return confirm('Bild wirklich löschen?');">Bild löschen</button>
        <?php endif; ?>

        <label for="sort_order">Reihenfolge</label>
        <input type="number" id="sort_order" name="sort_order" value="<?php echo (int) $sortOrder; ?>">

        <div class="checkbox-row">
          <input type="checkbox" id="is_active" name="is_active" <?php echo $isActive ? 'checked' : ''; ?>>
          <label for="is_active">Aktiv im Einsatz (deaktivieren = Wegbereiterin, z.B. Malou)</label>
        </div>

        <button type="submit">Speichern</button>
      </form>
    </div>

    <div class="preview-panel">
      <p class="preview-label">Vorschau &ndash; so k&ouml;nnte das Profil aussehen</p>
      <div class="preview-card">
        <img id="previewImage" src="<?php echo $image !== '' ? '/uploads/' . htmlspecialchars($image) : ''; ?>" alt="" style="<?php echo $image !== '' ? '' : 'display:none;'; ?>">
        <span class="preview-badge" id="previewStatus"><?php echo $isActive ? 'Aktiv' : 'Wegbereiterin'; ?></span>
        <h2 id="previewName"><?php echo htmlspecialchars($name !== '' ? $name : 'Name des Hundes'); ?></h2>
        <p class="preview-meta" id="previewEinsatzgebiet"><?php echo htmlspecialchars($einsatzgebiet); ?></p>
        <p id="previewCharakter"><?php echo htmlspecialchars($charakter); ?></p>
        <p id="previewBio"><?php echo htmlspecialchars($bio); ?></p>
      </div>
    </div>
  </div>

  <script src="../assets/js/wysiwyg.js"></script>
  <script>
    document.getElementById('name').addEventListener('input', function (e) {
      document.getElementById('previewName').textContent = e.target.value || 'Name des Hundes';
    });
    document.getElementById('einsatzgebiet').addEventListener('input', function (e) {
      document.getElementById('previewEinsatzgebiet').textContent = e.target.value;
    });
    document.getElementById('charakter').addEventListener('input', function (e) {
      document.getElementById('previewCharakter').innerHTML = e.target.value;
    });
    document.getElementById('bio').addEventListener('input', function (e) {
      document.getElementById('previewBio').innerHTML = e.target.value;
    });
    document.getElementById('is_active').addEventListener('change', function (e) {
      document.getElementById('previewStatus').textContent = e.target.checked ? 'Aktiv' : 'Wegbereiterin';
    });
    document.getElementById('image').addEventListener('change', function (e) {
      var file = e.target.files[0];
      if (!file) {
        return;
      }
      var img = document.getElementById('previewImage');
      img.src = URL.createObjectURL(file);
      img.style.display = '';
    });

    initWysiwyg('charakter');
    initWysiwyg('bio');
  </script>
</body>
</html>
