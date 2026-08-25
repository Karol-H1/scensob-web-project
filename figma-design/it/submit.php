<?php
/**
 * Receives the quote wizard (assets/js/site.js posts a FormData body here)
 * and stores it in the it_quote_requests table.
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

$servicesRaw = (string) ($_POST['services'] ?? '[]');
$services = json_decode($servicesRaw, true);
if (!is_array($services)) {
    $services = [];
}
$services = array_values(array_filter(array_map('strval', $services), function ($v) {
    return $v !== '';
}));

$timeline = trim((string) ($_POST['timeline'] ?? ''));
$size     = trim((string) ($_POST['size'] ?? ''));
$budget   = trim((string) ($_POST['budget'] ?? ''));
$brief    = trim((string) ($_POST['brief'] ?? ''));
$name     = trim((string) ($_POST['name'] ?? ''));
$company  = trim((string) ($_POST['company'] ?? ''));
$email    = trim((string) ($_POST['email'] ?? ''));
$phone    = trim((string) ($_POST['phone'] ?? ''));

$errors = [];
if (!$services) { $errors[] = 'Select at least one service.'; }
if ($name === '') { $errors[] = 'Full name is required.'; }
if ($company === '') { $errors[] = 'Company is required.'; }
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors[] = 'A valid email address is required.'; }

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
        'INSERT INTO it_quote_requests
            (services, timeline, company_size, budget, brief, name, company, email, phone)
         VALUES
            (:services, :timeline, :company_size, :budget, :brief, :name, :company, :email, :phone)'
    );

    $stmt->execute([
        ':services' => json_encode($services),
        ':timeline' => $timeline !== '' ? $timeline : null,
        ':company_size' => $size !== '' ? $size : null,
        ':budget' => $budget !== '' ? $budget : null,
        ':brief' => $brief !== '' ? $brief : null,
        ':name' => $name,
        ':company' => $company,
        ':email' => $email,
        ':phone' => $phone !== '' ? $phone : null,
    ]);

    respond(200, ['ok' => true, 'id' => (int) $pdo->lastInsertId()]);
} catch (Throwable $e) {
    error_log('it_quote_requests insert failed: ' . $e->getMessage());
    respond(500, ['ok' => false, 'error' => 'Could not save your request. Please try again shortly.']);
}
