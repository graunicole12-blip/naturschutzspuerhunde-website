<?php

const CONTACT_RECIPIENT = 'info@naturschutzspürhunde.ch';

const CONTACT_CATEGORIES = [
    'allgemein' => 'Allgemeine Anfrage',
    'presse' => 'Presse',
    'sponsoring' => 'Sponsoring',
    'mitgliedschaft' => 'Mitgliedschaft',
];

$name = '';
$email = '';
$category = 'allgemein';
$message = '';
$error = '';
$sent = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = str_replace(["\r", "\n"], '', trim($_POST['name'] ?? ''));
    $email = trim($_POST['email'] ?? '');
    $category = $_POST['category'] ?? 'allgemein';
    $message = trim($_POST['message'] ?? '');
    $honeypot = trim($_POST['website'] ?? '');

    if (!array_key_exists($category, CONTACT_CATEGORIES)) {
        $category = 'allgemein';
    }

    if ($honeypot !== '') {
        $sent = true;
    } elseif ($name === '' || $email === '' || $message === '') {
        $error = 'Bitte alle Pflichtfelder ausfüllen.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Bitte eine gültige E-Mail-Adresse angeben.';
    } else {
        $subject = mb_encode_mimeheader('[Kontaktformular] ' . CONTACT_CATEGORIES[$category] . ' – ' . $name, 'UTF-8');
        $body = "Anliegen: " . CONTACT_CATEGORIES[$category] . "\n"
            . "Name: " . $name . "\n"
            . "E-Mail: " . $email . "\n\n"
            . $message;
        $headers = 'From: Naturschutzspürhunde Website <no-reply@naturschutzspürhunde.ch>' . "\r\n"
            . 'Reply-To: ' . $email . "\r\n"
            . 'Content-Type: text/plain; charset=UTF-8';

        $sent = mail(CONTACT_RECIPIENT, $subject, $body, $headers);
        if (!$sent) {
            $error = 'Die Nachricht konnte nicht versendet werden. Bitte versuche es später erneut oder schreibe direkt an ' . CONTACT_RECIPIENT . '.';
        } else {
            $name = '';
            $email = '';
            $category = 'allgemein';
            $message = '';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kontakt &ndash; Naturschutzspürhunde Schweiz</title>
  <link rel="stylesheet" href="/assets/css/variables.css">
  <link rel="stylesheet" href="/assets/css/site.css">
</head>
<body>
  <?php require __DIR__ . '/includes/header.php'; ?>

  <main style="max-width:600px;margin:0 auto;padding:24px;">
    <h1>Kontakt</h1>
    <p>Hast du eine Frage, ein Presseanliegen oder möchtest du uns unterstützen? Schreib uns eine Nachricht oder direkt an <a href="mailto:<?php echo htmlspecialchars(CONTACT_RECIPIENT); ?>"><?php echo htmlspecialchars(CONTACT_RECIPIENT); ?></a>.</p>

    <?php if ($sent): ?>
      <p>Danke für deine Nachricht! Wir melden uns so bald wie möglich.</p>
    <?php else: ?>
      <?php if ($error !== ''): ?>
        <p style="color: var(--color-accent-red);"><?php echo htmlspecialchars($error); ?></p>
      <?php endif; ?>
      <form method="post" class="contact-form">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>

        <label for="email">E-Mail</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

        <label for="category">Anliegen</label>
        <select id="category" name="category">
          <?php foreach (CONTACT_CATEGORIES as $key => $label): ?>
            <option value="<?php echo htmlspecialchars($key); ?>" <?php echo $category === $key ? 'selected' : ''; ?>><?php echo htmlspecialchars($label); ?></option>
          <?php endforeach; ?>
        </select>

        <label for="message">Nachricht</label>
        <textarea id="message" name="message" rows="6" required><?php echo htmlspecialchars($message); ?></textarea>

        <div class="contact-honeypot" aria-hidden="true">
          <label for="website">Website</label>
          <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
        </div>

        <button type="submit" class="cta-button">Nachricht senden</button>
      </form>
    <?php endif; ?>
  </main>

  <?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
