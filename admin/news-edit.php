<?php

require __DIR__ . '/includes/auth.php';
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/upload.php';
require __DIR__ . '/../includes/news.php';
require __DIR__ . '/../includes/sanitize-html.php';

requireLogin();

$id = isset($_GET['id']) ? (int) $_GET['id'] : (isset($_POST['id']) ? (int) $_POST['id'] : 0);
$error = '';

$title = '';
$category = array_key_first(NEWS_CATEGORIES);
$publishedAt = date('Y-m-d');
$content = '';
$image = '';

if ($id > 0) {
    $stmt = getDb()->prepare('SELECT * FROM news WHERE id = ?');
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if ($row) {
        $title = $row['title'];
        $category = $row['category'];
        $publishedAt = $row['published_at'];
        $content = $row['content'] ?? '';
        $image = $row['image'] ?? '';
    } else {
        $id = 0;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_image']) && $id > 0) {
    $stmt = getDb()->prepare('UPDATE news SET image = ? WHERE id = ?');
    $stmt->execute(['', $id]);
    header('Location: /admin/news-edit.php?id=' . $id);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $category = $_POST['category'] ?? '';
    $publishedAt = $_POST['published_at'] ?? '';
    $content = sanitizeHtml(trim($_POST['content'] ?? ''));

    if ($title === '') {
        $error = 'Titel darf nicht leer sein.';
    } elseif (!isset(NEWS_CATEGORIES[$category])) {
        $error = 'Ungültige Kategorie.';
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $publishedAt)) {
        $error = 'Ungültiges Datum.';
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
            $stmt = getDb()->prepare('UPDATE news SET title = ?, category = ?, content = ?, image = ?, published_at = ? WHERE id = ?');
            $stmt->execute([$title, $category, $content, $image, $publishedAt, $id]);
        } else {
            $stmt = getDb()->prepare('INSERT INTO news (title, category, content, image, published_at) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$title, $category, $content, $image, $publishedAt]);
        }
        header('Location: /admin/news.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $id > 0 ? 'Beitrag bearbeiten' : 'Neuer Beitrag'; ?> &ndash; Naturschutzsp&uuml;rhunde Admin</title>
  <link rel="stylesheet" href="../assets/css/variables.css">
  <link rel="stylesheet" href="../assets/css/block-editor.css">
  <style>
    body { font-family: var(--font-text); margin: 0; background: var(--color-neutral-cream); }
    .topbar { display: flex; justify-content: space-between; align-items: center; padding: 16px 32px; }
    .topbar a { color: var(--color-primary); font-size: 13px; text-decoration: none; }
    .layout { display: flex; gap: 24px; margin: 0 32px 32px; align-items: flex-start; }
    .panel { background: #fff; border-radius: 12px; padding: 20px; max-width: 480px; box-sizing: border-box; flex: 1; }
    h1 { font-family: var(--font-titel); color: var(--color-primary); font-size: 22px; margin: 0 0 16px; }
    label { display: block; font-size: 12px; color: var(--color-primary); margin: 16px 0 4px; }
    label:first-of-type { margin-top: 0; }
    input[type="text"], input[type="date"], select, textarea { width: 100%; box-sizing: border-box; border: 1px solid var(--color-neutral-blue); border-radius: 6px; padding: 8px; font-size: 14px; font-family: var(--font-text); }
    textarea { min-height: 120px; }
    input[type="file"] { font-size: 13px; }
    button { background: var(--color-accent-red); color: #fff; border: none; border-radius: 6px; height: 36px; padding: 0 20px; font-size: 14px; font-weight: 500; cursor: pointer; margin-top: 16px; }
    .error { color: var(--color-accent-red); font-size: 13px; }
    .current-image { max-width: 240px; display: block; margin-top: 8px; border-radius: 6px; }
    .delete-image-btn { background: var(--color-secondary-gold); margin-top: 8px; margin-left: 8px; height: 30px; padding: 0 14px; font-size: 13px; }
    .hint { font-size: 12px; color: var(--color-secondary-khaki); margin: 4px 0 0; }

    .preview-panel { flex: 1; max-width: 360px; }
    .preview-label { font-size: 12px; color: var(--color-secondary-khaki); margin: 0 0 8px; }
    .preview-card { background: #fff; border-radius: 12px; padding: 16px; box-sizing: border-box; }
    .preview-card img { max-width: 100%; border-radius: 6px; display: block; margin-bottom: 12px; }
    .preview-badge { display: inline-block; background: var(--color-neutral-tan); color: var(--color-primary); font-size: 11px; padding: 2px 8px; border-radius: 10px; margin-bottom: 6px; }
    .preview-card h2 { font-family: var(--font-titel); color: var(--color-primary); font-size: 18px; margin: 0 0 4px; }
    .preview-date { font-size: 12px; color: var(--color-secondary-khaki); margin: 0 0 8px; }
    .preview-card p { font-size: 13px; color: var(--color-primary); white-space: pre-wrap; margin: 0; }
  </style>
</head>
<body>
  <div class="topbar">
    <a href="/admin/news.php">&larr; Zur&uuml;ck zur &Uuml;bersicht</a>
    <a href="/admin/logout.php">Abmelden</a>
  </div>
  <div class="layout">
    <div class="panel">
      <h1><?php echo $id > 0 ? 'Beitrag bearbeiten' : 'Neuer Beitrag'; ?></h1>
      <?php if ($error !== ''): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
      <?php endif; ?>
      <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo (int) $id; ?>">

        <label for="title">Titel</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required>

        <label for="category">Kategorie</label>
        <select id="category" name="category">
          <?php foreach (NEWS_CATEGORIES as $key => $label): ?>
            <option value="<?php echo htmlspecialchars($key); ?>" <?php echo $key === $category ? 'selected' : ''; ?>><?php echo htmlspecialchars($label); ?></option>
          <?php endforeach; ?>
        </select>

        <label for="published_at">Datum</label>
        <input type="date" id="published_at" name="published_at" value="<?php echo htmlspecialchars($publishedAt); ?>" required>

        <label for="content">Text</label>
        <textarea id="content" name="content"><?php echo htmlspecialchars($content); ?></textarea>

        <label for="image">Bild</label>
        <?php if ($image !== ''): ?>
          <img class="current-image" src="/uploads/<?php echo htmlspecialchars($image); ?>" alt="Aktuelles Bild">
        <?php endif; ?>
        <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp">
        <p class="hint">JPEG, PNG oder WebP, max. 5 MB. Optional.</p>
        <?php if ($image !== ''): ?>
          <button type="submit" name="delete_image" value="1" class="delete-image-btn" formnovalidate onclick="return confirm('Bild wirklich löschen?');">Bild löschen</button>
        <?php endif; ?>

        <button type="submit">Speichern</button>
      </form>
    </div>

    <div class="preview-panel">
      <p class="preview-label">Vorschau &ndash; so k&ouml;nnte der Beitrag aussehen</p>
      <div class="preview-card">
        <img id="previewImage" src="<?php echo $image !== '' ? '/uploads/' . htmlspecialchars($image) : ''; ?>" alt="" style="<?php echo $image !== '' ? '' : 'display:none;'; ?>">
        <span class="preview-badge" id="previewCategory"><?php echo htmlspecialchars(NEWS_CATEGORIES[$category] ?? ''); ?></span>
        <h2 id="previewTitle"><?php echo htmlspecialchars($title !== '' ? $title : 'Titel des Beitrags'); ?></h2>
        <p class="preview-date" id="previewDate"><?php echo htmlspecialchars($publishedAt); ?></p>
        <p id="previewContent"><?php echo htmlspecialchars($content); ?></p>
      </div>
    </div>
  </div>

  <script src="../assets/js/block-editor.js"></script>
  <script>
    var categories = <?php echo json_encode(NEWS_CATEGORIES); ?>;

    document.getElementById('title').addEventListener('input', function (e) {
      document.getElementById('previewTitle').textContent = e.target.value || 'Titel des Beitrags';
    });
    document.getElementById('category').addEventListener('change', function (e) {
      document.getElementById('previewCategory').textContent = categories[e.target.value] || '';
    });
    document.getElementById('published_at').addEventListener('input', function (e) {
      document.getElementById('previewDate').textContent = e.target.value;
    });
    document.getElementById('content').addEventListener('input', function (e) {
      document.getElementById('previewContent').innerHTML = renderBlocksToHtml(e.target.value);
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

    initBlockEditor('content');
  </script>
</body>
</html>
