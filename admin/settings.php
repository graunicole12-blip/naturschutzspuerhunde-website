<?php

require __DIR__ . '/includes/auth.php';
require __DIR__ . '/../includes/settings.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'save_settings') {
        setSetting('maintenance_mode', isset($_POST['maintenance_mode']) ? '1' : '0');
        setSetting('maintenance_text', trim($_POST['maintenance_text'] ?? ''));
    } elseif ($action === 'save_link_settings') {
        setSetting('link_color', sanitizeHexColor($_POST['link_color'] ?? null, '#b82020'));
        setSetting('link_underline', isset($_POST['link_underline']) ? '1' : '0');
        setSetting('link_hover_color', sanitizeHexColor($_POST['link_hover_color'] ?? null, '#a33122'));
    } elseif ($action === 'generate_preview') {
        setSetting('maintenance_preview_token', bin2hex(random_bytes(16)));
    } elseif ($action === 'revoke_preview') {
        setSetting('maintenance_preview_token', '');
    }

    header('Location: /admin/settings.php');
    exit;
}

$maintenanceMode = getSetting('maintenance_mode', '0') === '1';
$maintenanceText = getSetting('maintenance_text', '');
$previewToken = getSetting('maintenance_preview_token', '');

$linkColor = sanitizeHexColor(getSetting('link_color', ''), '#b82020');
$linkUnderline = getSetting('link_underline', '1') === '1';
$linkHoverColor = sanitizeHexColor(getSetting('link_hover_color', ''), '#a33122');

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$previewUrl = $previewToken !== ''
    ? $scheme . '://' . $_SERVER['HTTP_HOST'] . '/index.php?preview=' . urlencode($previewToken)
    : '';
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Einstellungen &ndash; Naturschutzsp&uuml;rhunde Admin</title>
  <link rel="stylesheet" href="../assets/css/variables.css">
  <style>
    body { font-family: var(--font-text); margin: 0; background: var(--color-neutral-cream); }
    .topbar { display: flex; justify-content: space-between; align-items: center; padding: 16px 32px; }
    .topbar a { color: var(--color-primary); font-size: 13px; text-decoration: none; }
    .panel { background: #fff; border-radius: 12px; margin: 0 32px 24px; padding: 20px; max-width: 480px; box-sizing: border-box; }
    h1 { font-family: var(--font-titel); color: var(--color-primary); font-size: 22px; margin: 0 0 16px; }
    h2 { font-family: var(--font-titel); color: var(--color-primary); font-size: 16px; margin: 0 0 4px; }
    label { display: block; font-size: 12px; color: var(--color-primary); margin: 16px 0 4px; }
    textarea { width: 100%; box-sizing: border-box; border: 1px solid var(--color-neutral-blue); border-radius: 6px; padding: 8px; font-size: 14px; font-family: var(--font-text); min-height: 90px; }
    .checkbox-row { display: flex; align-items: center; gap: 8px; margin: 16px 0 0; }
    .checkbox-row label { margin: 0; }
    input[type="color"] { width: 60px; height: 36px; border: 1px solid var(--color-neutral-blue); border-radius: 6px; padding: 2px; cursor: pointer; }
    .link-preview { margin: 12px 0 0; font-size: 14px; }
    button { background: var(--color-accent-red); color: #fff; border: none; border-radius: 6px; height: 36px; padding: 0 20px; font-size: 14px; font-weight: 500; cursor: pointer; margin-top: 16px; }
    button.secondary { background: var(--color-secondary-gold); }
    .hint { font-size: 12px; color: var(--color-secondary-khaki); margin: 4px 0 0; }
    .preview-link { font-size: 13px; word-break: break-all; background: var(--color-neutral-cream); border-radius: 6px; padding: 8px; margin: 8px 0 0; }
    .preview-empty { font-size: 13px; color: var(--color-secondary-khaki); margin: 8px 0 0; }
  </style>
</head>
<body>
  <div class="topbar">
    <a href="/admin/index.php">&larr; Zur&uuml;ck zum Dashboard</a>
    <a href="/admin/logout.php">Abmelden</a>
  </div>

  <div class="panel">
    <h1>Einstellungen</h1>
    <form method="post">
      <input type="hidden" name="action" value="save_settings">

      <div class="checkbox-row">
        <input type="checkbox" id="maintenance_mode" name="maintenance_mode" <?php echo $maintenanceMode ? 'checked' : ''; ?>>
        <label for="maintenance_mode">Wartungsmodus aktiv</label>
      </div>
      <p class="hint">Bei aktivem Wartungsmodus sehen nicht eingeloggte Besucherinnen die Wartungsseite statt der normalen Webseite.</p>

      <label for="maintenance_text">Text auf der Wartungsseite</label>
      <textarea id="maintenance_text" name="maintenance_text"><?php echo htmlspecialchars($maintenanceText); ?></textarea>

      <button type="submit">Speichern</button>
    </form>
  </div>

  <div class="panel">
    <h2>Vorschau-Link</h2>
    <p class="hint">Damit k&ouml;nnen Vereinskolleginnen die Baustelle ohne Login sehen, solange der Wartungsmodus aktiv ist.</p>
    <?php if ($previewUrl !== ''): ?>
      <p class="preview-link"><?php echo htmlspecialchars($previewUrl); ?></p>
    <?php else: ?>
      <p class="preview-empty">Noch kein Vorschau-Link generiert.</p>
    <?php endif; ?>
    <form method="post">
      <input type="hidden" name="action" value="generate_preview">
      <button type="submit"><?php echo $previewUrl !== '' ? 'Neuen Link generieren' : 'Link generieren'; ?></button>
    </form>
    <?php if ($previewUrl !== ''): ?>
      <form method="post">
        <input type="hidden" name="action" value="revoke_preview">
        <button type="submit" class="secondary">Link widerrufen</button>
      </form>
    <?php endif; ?>
  </div>

  <div class="panel">
    <h1>Link-Darstellung</h1>
    <p class="hint">Gilt f&uuml;r Links innerhalb von Inhaltstexten (z.B. im Block-Editor eingef&uuml;gte Links). Header-Navigation und rote CTA-Buttons (z.B. &laquo;Jetzt unterst&uuml;tzen&raquo;) sind eigenst&auml;ndig gestaltet und bleiben unver&auml;ndert.</p>
    <form method="post">
      <input type="hidden" name="action" value="save_link_settings">

      <label for="link_color">Textfarbe</label>
      <input type="color" id="link_color" name="link_color" value="<?php echo htmlspecialchars($linkColor); ?>">

      <div class="checkbox-row">
        <input type="checkbox" id="link_underline" name="link_underline" <?php echo $linkUnderline ? 'checked' : ''; ?>>
        <label for="link_underline">Links unterstreichen</label>
      </div>

      <label for="link_hover_color">Hover-Farbe</label>
      <input type="color" id="link_hover_color" name="link_hover_color" value="<?php echo htmlspecialchars($linkHoverColor); ?>">

      <p class="link-preview">Vorschau: <a id="linkPreview" href="#" onclick="return false;" style="color:<?php echo htmlspecialchars($linkColor); ?>;text-decoration:<?php echo $linkUnderline ? 'underline' : 'none'; ?>;">Beispiellink in einem Inhaltstext</a></p>

      <button type="submit">Speichern</button>
    </form>
  </div>

  <script>
    document.getElementById('link_color').addEventListener('input', function (e) {
      document.getElementById('linkPreview').style.color = e.target.value;
    });
    document.getElementById('link_underline').addEventListener('change', function (e) {
      document.getElementById('linkPreview').style.textDecoration = e.target.checked ? 'underline' : 'none';
    });
    document.getElementById('linkPreview').addEventListener('mouseenter', function () {
      this.style.color = document.getElementById('link_hover_color').value;
    });
    document.getElementById('linkPreview').addEventListener('mouseleave', function () {
      this.style.color = document.getElementById('link_color').value;
    });
  </script>
</body>
</html>
