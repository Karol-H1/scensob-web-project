<?php
/**
 * Receives the Contact page form (assets/js/site.js posts a FormData body here)
 * and stores it in the group_enquiries table.
 */

declare(strict_types=1);

header('Content-Type: application/json');

function respond($status, array $body) {
    http_response_code($status);
    echo json_encode($body);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(405, ['ok' => false, 'error' => 'Method not allowed']);
}

// Honeypot: a real visitor never sees or fills this field in. Reply success so
// the bot doesn't learn to adapt, but skip the database entirely.
if (!empty($_POST['website'])) {
    respond(200, ['ok' => true]);
}

$configPath = __DIR__ . '/config.php';

// Fail clearly rather than with a raw PHP fatal error, which would leak the
// server path and return HTML the form's JS can't parse.
if (!is_file($configPath)) {
    error_log('config.php is missing. Copy config.sample.php to config.php and fill in the database credentials.');
    respond(500, ['ok' => false, 'error' => 'This form is not configured yet. Please contact the site administrator.']);
}

$config = require $configPath;

$enquiryType = trim((string) ($_POST['enquiry_type'] ?? ''));
$name        = trim((string) ($_POST['name'] ?? ''));
$company     = trim((string) ($_POST['company'] ?? ''));
$email       = trim((string) ($_POST['email'] ?? ''));
$phone       = trim((string) ($_POST['phone'] ?? ''));
$message     = trim((string) ($_POST['message'] ?? ''));

// Only the three fields the form itself marks with * are required here. The
// enquiry-type buttons, company and phone are optional on the page, so
// rejecting them server-side would fail a submission the page called valid.
$errors = [];
if ($name === '') { $errors[] = 'Full name is required.'; }
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors[] = 'A valid email address is required.'; }
if ($message === '') { $errors[] = 'A message is required.'; }

if ($errors) {
    respond(422, ['ok' => false, 'error' => implode(' ', $errors)]);
}

try {
    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $config['host'], $config['database'], $config['charset']);
    $pdo = new PDO($dsn, $config['username'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    $stmt = $pdo->prepare(
        'INSERT INTO group_enquiries
            (enquiry_type, name, company, email, phone, message)
         VALUES
            (:enquiry_type, :name, :company, :email, :phone, :message)'
    );

    $stmt->execute([
        ':enquiry_type' => $enquiryType !== '' ? $enquiryType : null,
        ':name' => $name,
        ':company' => $company !== '' ? $company : null,
        ':email' => $email,
        ':phone' => $phone !== '' ? $phone : null,
        ':message' => $message,
    ]);

    respond(200, ['ok' => true, 'id' => (int) $pdo->lastInsertId()]);
} catch (Throwable $e) {
    error_log('group_enquiries insert failed: ' . $e->getMessage());
    respond(500, ['ok' => false, 'error' => 'Could not save your enquiry. Please try again shortly.']);
}
