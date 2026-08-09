<?php

require_once __DIR__ . '/settings.php';

$linkStyleColor = sanitizeHexColor(getSetting('link_color', ''), '#b82020');
$linkStyleUnderline = getSetting('link_underline', '1') === '1';
$linkStyleHoverColor = sanitizeHexColor(getSetting('link_hover_color', ''), '#a33122');
?>
<style>
  a { color: <?php echo $linkStyleColor; ?>; text-decoration: <?php echo $linkStyleUnderline ? 'underline' : 'none'; ?>; }
  a:hover { color: <?php echo $linkStyleHoverColor; ?>; }
</style>
