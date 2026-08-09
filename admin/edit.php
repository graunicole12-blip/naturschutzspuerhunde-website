<?php

require __DIR__ . '/includes/auth.php';
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/upload.php';
require __DIR__ . '/../includes/sanitize-html.php';
require __DIR__ . '/../includes/content.php';

requireLogin();

$pageKey = 'startseite';
$textBlockKey = 'vision';
$nshBlockKey = 'nsh_text';
$imageBlockKey = 'hero_image';
$heroImageMaxHeightKey = 'hero_image_max_height';
$heroImageAlignKey = 'hero_image_align';
$ausbildungTeaserBlockKey = 'ausbildung_teaser_text';
$ctaTextBlockKey = 'cta_text';
$ctaLinkBlockKey = 'cta_link';
$projekteAnzahlKey = 'projekte_anzahl';
$hundeAnzahlKey = 'hunde_anzahl';
$newsAnzahlKey = 'news_anzahl';
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_image'])) {
    $stmt = getDb()->prepare(
        'INSERT INTO content_blocks (page_key, block_key, content) VALUES (?, ?, ?)
         ON DUPLICATE KEY UPDATE content = VALUES(content)'
    );
    $stmt->execute([$pageKey, $imageBlockKey, '']);
    header('Location: /admin/edit.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = sanitizeBlockFieldInput(trim($_POST['content'] ?? ''));
    $nshText = sanitizeBlockFieldInput(trim($_POST['nsh_text'] ?? ''));
    $ausbildungTeaserText = sanitizeBlockFieldInput(trim($_POST['ausbildung_teaser_text'] ?? ''));
    $ctaText = sanitizeBlockFieldInput(trim($_POST['cta_text'] ?? ''));
    $ctaLink = trim($_POST['cta_link'] ?? '');
    $projekteAnzahl = clampCardLimit($_POST['projekte_anzahl'] ?? null);
    $hundeAnzahl = clampCardLimit($_POST['hunde_anzahl'] ?? null);
    $newsAnzahl = clampCardLimit($_POST['news_anzahl'] ?? null);
    $heroImageMaxHeight = clampCardLimit($_POST['hero_image_max_height'] ?? null, 480, 100, 1200);
    $heroImageAlign = clampAlignment($_POST['hero_image_align'] ?? null);
    $stmt = getDb()->prepare(
        'INSERT INTO content_blocks (page_key, block_key, content) VALUES (?, ?, ?)
         ON DUPLICATE KEY UPDATE content = VALUES(content)'
    );
    $stmt->execute([$pageKey, $textBlockKey, $content]);
    $stmt->execute([$pageKey, $nshBlockKey, $nshText]);
    $stmt->execute([$pageKey, $ausbildungTeaserBlockKey, $ausbildungTeaserText]);
    $stmt->execute([$pageKey, $ctaTextBlockKey, $ctaText]);
    $stmt->execute([$pageKey, $ctaLinkBlockKey, $ctaLink]);
    $stmt->execute([$pageKey, $projekteAnzahlKey, (string) $projekteAnzahl]);
    $stmt->execute([$pageKey, $hundeAnzahlKey, (string) $hundeAnzahl]);
    $stmt->execute([$pageKey, $newsAnzahlKey, (string) $newsAnzahl]);
    $stmt->execute([$pageKey, $heroImageMaxHeightKey, (string) $heroImageMaxHeight]);
    $stmt->execute([$pageKey, $heroImageAlignKey, $heroImageAlign]);

    $uploadResult = handleImageUpload($_FILES['hero_image'] ?? [], __DIR__ . '/../uploads');
    if ($uploadResult['error'] !== null) {
        $error = $uploadResult['error'];
    } elseif ($uploadResult['success']) {
        $stmt->execute([$pageKey, $imageBlockKey, $uploadResult['filename']]);
    }

    $message = $error === '' ? 'Gespeichert.' : '';
}

$stmt = getDb()->prepare('SELECT block_key, content FROM content_blocks WHERE page_key = ? AND block_key IN (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
$stmt->execute([$pageKey, $textBlockKey, $nshBlockKey, $imageBlockKey, $ausbildungTeaserBlockKey, $ctaTextBlockKey, $ctaLinkBlockKey, $projekteAnzahlKey, $hundeAnzahlKey, $newsAnzahlKey, $heroImageMaxHeightKey, $heroImageAlignKey]);
$blocks = [];
foreach ($stmt->fetchAll() as $row) {
    $blocks[$row['block_key']] = $row['content'];
}
$currentText = $blocks[$textBlockKey] ?? '';
$currentNshText = $blocks[$nshBlockKey] ?? '';
$currentImage = $blocks[$imageBlockKey] ?? '';
$currentAusbildungTeaserText = $blocks[$ausbildungTeaserBlockKey] ?? '';
$currentCtaText = $blocks[$ctaTextBlockKey] ?? '';
$currentCtaLink = $blocks[$ctaLinkBlockKey] ?? '';
$currentProjekteAnzahl = clampCardLimit($blocks[$projekteAnzahlKey] ?? null);
$currentHundeAnzahl = clampCardLimit($blocks[$hundeAnzahlKey] ?? null);
$currentNewsAnzahl = clampCardLimit($blocks[$newsAnzahlKey] ?? null);
$currentHeroImageMaxHeight = clampCardLimit($blocks[$heroImageMaxHeightKey] ?? null, 480, 100, 1200);
$currentHeroImageAlign = clampAlignment($blocks[$heroImageAlignKey] ?? null);
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Startseite bearbeiten &ndash; Naturschutzsp&uuml;rhunde Admin</title>
  <link rel="stylesheet" href="../assets/css/variables.css">
  <link rel="stylesheet" href="../assets/css/block-editor.css">
  <style>
    body { font-family: var(--font-text); margin: 0; background: var(--color-neutral-cream); }
    .topbar { display: flex; justify-content: space-between; align-items: center; padding: 16px 32px; }
    .topbar a { color: var(--color-primary); font-size: 13px; text-decoration: none; }
    .layout { display: flex; gap: 24px; margin: 0 32px 32px; align-items: flex-start; }
    .panel { background: #fff; border-radius: 12px; padding: 20px; max-width: 480px; box-sizing: border-box; flex: 1; }
    select { width: 260px; margin-bottom: 12px; padding: 6px; }
    label { display: block; font-size: 12px; color: var(--color-primary); margin: 16px 0 4px; }
    textarea { width: 100%; box-sizing: border-box; min-height: 100px; border: 1px solid var(--color-neutral-blue); border-radius: 6px; padding: 10px; font-size: 14px; font-family: var(--font-text); }
    input[type="text"], input[type="number"] { width: 100%; box-sizing: border-box; border: 1px solid var(--color-neutral-blue); border-radius: 6px; padding: 8px; font-size: 14px; font-family: var(--font-text); }
    input[type="file"] { font-size: 13px; }
    button { background: var(--color-accent-red); color: #fff; border: none; border-radius: 6px; height: 36px; padding: 0 20px; font-size: 14px; font-weight: 500; cursor: pointer; margin-top: 16px; }
    .delete-image-btn { background: var(--color-secondary-gold); margin-top: 8px; margin-left: 8px; height: 30px; padding: 0 14px; font-size: 13px; }
    .message { color: var(--color-secondary-gold); font-size: 13px; }
    .error { color: var(--color-accent-red); font-size: 13px; }
    .current-image { max-width: 240px; display: block; margin-top: 8px; border-radius: 6px; }
    .hint { font-size: 12px; color: var(--color-secondary-khaki); margin: 4px 0 0; }

    .preview-panel { flex: 1; max-width: 360px; }
    .preview-label { font-size: 12px; color: var(--color-secondary-khaki); margin: 0 0 8px; }
    .preview-card { background: #fff; border-radius: 12px; padding: 16px; box-sizing: border-box; }
    .preview-card img { max-width: 100%; border-radius: 6px; display: block; margin-bottom: 12px; }
    .preview-card h2 { font-family: var(--font-titel); color: var(--color-primary); font-size: 18px; margin: 0 0 8px; }
    .preview-card p { font-size: 13px; color: var(--color-primary); white-space: pre-wrap; margin: 0 0 8px; }
    .cta-button { display: inline-block; background: var(--color-accent-red); color: #fff; text-decoration: none; padding: 8px 16px; border-radius: 6px; font-size: 13px; }
  </style>
</head>
<body>
  <div class="topbar">
    <a href="/admin/index.php">&larr; Zur&uuml;ck zum Dashboard</a>
    <a href="/admin/logout.php">Abmelden</a>
  </div>
  <div class="layout">
    <div class="panel">
      <select disabled>
        <option>Startseite</option>
      </select>
      <?php if ($message !== ''): ?>
        <p class="message"><?php echo htmlspecialchars($message); ?></p>
      <?php endif; ?>
      <?php if ($error !== ''): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
      <?php endif; ?>
      <form method="post" enctype="multipart/form-data">
        <label for="content">Vision</label>
        <textarea id="content" name="content" placeholder="Kurztext Vision verfassen (max. 3-4 S&auml;tze)..."><?php echo htmlspecialchars($currentText); ?></textarea>

        <label for="nsh_text">Was sind Naturschutzsp&uuml;rhunde?</label>
        <textarea id="nsh_text" name="nsh_text" placeholder="Kurztext verfassen (max. 3-4 S&auml;tze)..."><?php echo htmlspecialchars($currentNshText); ?></textarea>

        <label for="hero_image">Hero-Bild</label>
        <?php if ($currentImage !== ''): ?>
          <img class="current-image" src="/uploads/<?php echo htmlspecialchars($currentImage); ?>" alt="Aktuelles Hero-Bild">
        <?php endif; ?>
        <input type="file" id="hero_image" name="hero_image" accept="image/jpeg,image/png,image/webp">
        <p class="hint">JPEG, PNG oder WebP, max. 5 MB.</p>
        <?php if ($currentImage !== ''): ?>
          <button type="submit" name="delete_image" value="1" class="delete-image-btn" formnovalidate onclick="return confirm('Hero-Bild wirklich löschen?');">Bild löschen</button>
        <?php endif; ?>

        <label for="hero_image_max_height">Hero-Bild &ndash; maximale H&ouml;he (px)</label>
        <input type="number" id="hero_image_max_height" name="hero_image_max_height" min="100" max="1200" value="<?php echo (int) $currentHeroImageMaxHeight; ?>">
        <p class="hint">Erlaubter Bereich: 100&ndash;1200px. Bild bleibt proportional, Breite bleibt responsiv. Ungültige Eingaben werden beim Speichern auf 480px zurückgesetzt.</p>

        <label for="hero_image_align">Hero-Bild &ndash; Ausrichtung</label>
        <select id="hero_image_align" name="hero_image_align">
          <option value="left" <?php echo $currentHeroImageAlign === 'left' ? 'selected' : ''; ?>>Links</option>
          <option value="center" <?php echo $currentHeroImageAlign === 'center' ? 'selected' : ''; ?>>Mittig</option>
          <option value="right" <?php echo $currentHeroImageAlign === 'right' ? 'selected' : ''; ?>>Rechts</option>
        </select>
        <p class="hint">Wirkt sich sichtbar aus, sobald das Bild schmaler als die volle Breite ist.</p>

        <label for="ausbildung_teaser_text">Ausbildung &ndash; Kurztext (Startseiten-Teaser)</label>
        <textarea id="ausbildung_teaser_text" name="ausbildung_teaser_text" placeholder="Kurztext f&uuml;r den Ausbildung-Teaser auf der Startseite..."><?php echo htmlspecialchars($currentAusbildungTeaserText); ?></textarea>

        <label for="cta_text">Unterst&uuml;tzen &ndash; Kurztext</label>
        <textarea id="cta_text" name="cta_text" placeholder="Kurztext f&uuml;r die Unterst&uuml;tzen-Sektion..."><?php echo htmlspecialchars($currentCtaText); ?></textarea>

        <label for="cta_link">Unterst&uuml;tzen &ndash; Link (Button-Ziel)</label>
        <input type="text" id="cta_link" name="cta_link" value="<?php echo htmlspecialchars($currentCtaLink); ?>" placeholder="https://...">

        <label for="projekte_anzahl">Anzahl Projekt-Karten</label>
        <input type="number" id="projekte_anzahl" name="projekte_anzahl" min="1" max="12" value="<?php echo (int) $currentProjekteAnzahl; ?>">

        <label for="hunde_anzahl">Anzahl Hunde-Karten</label>
        <input type="number" id="hunde_anzahl" name="hunde_anzahl" min="1" max="12" value="<?php echo (int) $currentHundeAnzahl; ?>">

        <label for="news_anzahl">Anzahl News-Karten</label>
        <input type="number" id="news_anzahl" name="news_anzahl" min="1" max="12" value="<?php echo (int) $currentNewsAnzahl; ?>">
        <p class="hint">Erlaubter Bereich: 1&ndash;12. Ungültige Eingaben werden beim Speichern auf 3 zurückgesetzt.</p>

        <button type="submit">Speichern</button>
      </form>
    </div>

    <div class="preview-panel">
      <p class="preview-label">Vorschau &ndash; so erscheint es auf der Webseite</p>
      <div class="preview-card">
        <img id="previewImage" src="<?php echo $currentImage !== '' ? '/uploads/' . htmlspecialchars($currentImage) : ''; ?>" alt="" style="<?php echo $currentImage !== '' ? '' : 'display:none;'; ?>max-height:<?php echo (int) $currentHeroImageMaxHeight; ?>px;<?php echo heroImageAlignStyle($currentHeroImageAlign); ?>">
        <h2>Vision</h2>
        <p id="previewText"><?php echo renderRichText($currentText); ?></p>
        <h2>Was sind Naturschutzsp&uuml;rhunde?</h2>
        <p id="previewNshText"><?php echo renderRichText($currentNshText); ?></p>
        <h2>Ausbildung</h2>
        <p id="previewAusbildungTeaserText"><?php echo renderRichText($currentAusbildungTeaserText); ?></p>
        <h2>Unterst&uuml;tzen</h2>
        <p id="previewCtaText"><?php echo renderRichText($currentCtaText); ?></p>
        <a id="previewCtaButton" class="cta-button" href="<?php echo htmlspecialchars($currentCtaLink); ?>" target="_blank" rel="noopener">Jetzt unterst&uuml;tzen</a>
      </div>
    </div>
  </div>

  <script src="../assets/js/block-editor.js"></script>
  <script>
    document.getElementById('content').addEventListener('input', function (e) {
      document.getElementById('previewText').innerHTML = renderBlocksToHtml(e.target.value);
    });
    document.getElementById('nsh_text').addEventListener('input', function (e) {
      document.getElementById('previewNshText').innerHTML = renderBlocksToHtml(e.target.value);
    });
    document.getElementById('ausbildung_teaser_text').addEventListener('input', function (e) {
      document.getElementById('previewAusbildungTeaserText').innerHTML = renderBlocksToHtml(e.target.value);
    });
    document.getElementById('cta_text').addEventListener('input', function (e) {
      document.getElementById('previewCtaText').innerHTML = renderBlocksToHtml(e.target.value);
    });
    document.getElementById('cta_link').addEventListener('input', function (e) {
      document.getElementById('previewCtaButton').href = e.target.value;
    });
    document.getElementById('hero_image_max_height').addEventListener('input', function (e) {
      var value = parseInt(e.target.value, 10);
      if (!isNaN(value) && value > 0) {
        document.getElementById('previewImage').style.maxHeight = value + 'px';
      }
    });
    document.getElementById('hero_image_align').addEventListener('change', function (e) {
      var margins = { left: ['0', 'auto'], center: ['auto', 'auto'], right: ['auto', '0'] };
      var pair = margins[e.target.value] || margins.center;
      var img = document.getElementById('previewImage');
      img.style.marginLeft = pair[0];
      img.style.marginRight = pair[1];
    });
    document.getElementById('hero_image').addEventListener('change', function (e) {
      var file = e.target.files[0];
      if (!file) {
        return;
      }
      var img = document.getElementById('previewImage');
      img.src = URL.createObjectURL(file);
      img.style.display = '';
    });

    initBlockEditor('content');
    initBlockEditor('nsh_text');
    initBlockEditor('ausbildung_teaser_text');
    initBlockEditor('cta_text');
  </script>
</body>
</html>
