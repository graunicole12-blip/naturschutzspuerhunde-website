<?php

require __DIR__ . '/includes/session.php';

startSecureSession();
$_SESSION = [];
session_destroy();

header('Location: /admin/login.php');
exit;
