<?php
/**
 * Receives the Contact page enquiry form (assets/js/site.js posts a FormData
 * body here) and stores it in the transport_enquiries table.
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

$enquiryType = trim((string) ($_POST['enquiry'] ?? ''));
$detail      = trim((string) ($_POST['detail'] ?? ''));
$firstName   = trim((string) ($_POST['first-name'] ?? ''));
$lastName    = trim((string) ($_POST['last-name'] ?? ''));
$company     = trim((string) ($_POST['company'] ?? ''));
$email       = trim((string) ($_POST['email'] ?? ''));
$phone       = trim((string) ($_POST['phone'] ?? ''));
$headcount   = trim((string) ($_POST['headcount'] ?? ''));
$location    = trim((string) ($_POST['location'] ?? ''));
$licence     = trim((string) ($_POST['licence'] ?? ''));
$notes       = trim((string) ($_POST['notes'] ?? ''));

$errors = [];
if ($enquiryType === '') { $errors[] = 'Missing enquiry type.'; }
if ($firstName === '') { $errors[] = 'First name is required.'; }
if ($lastName === '') { $errors[] = 'Last name is required.'; }
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors[] = 'A valid email address is required.'; }
if ($phone === '') { $errors[] = 'Phone number is required.'; }

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
        'INSERT INTO transport_enquiries
            (enquiry_type, detail, first_name, last_name, company, email, phone, headcount, location, licence, notes)
         VALUES
            (:enquiry_type, :detail, :first_name, :last_name, :company, :email, :phone, :headcount, :location, :licence, :notes)'
    );

    $stmt->execute([
        ':enquiry_type' => $enquiryType,
        ':detail' => $detail !== '' ? $detail : null,
        ':first_name' => $firstName,
        ':last_name' => $lastName,
        ':company' => $company !== '' ? $company : null,
        ':email' => $email,
        ':phone' => $phone,
        ':headcount' => $headcount !== '' ? $headcount : null,
        ':location' => $location !== '' ? $location : null,
        ':licence' => $licence !== '' ? $licence : null,
        ':notes' => $notes !== '' ? $notes : null,
    ]);

    respond(200, ['ok' => true, 'id' => (int) $pdo->lastInsertId()]);
} catch (Throwable $e) {
    error_log('transport_enquiries insert failed: ' . $e->getMessage());
    respond(500, ['ok' => false, 'error' => 'Could not save your enquiry. Please try again shortly.']);
}
