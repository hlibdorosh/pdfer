<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../config.php'; // Підключаємо базу

use setasign\Fpdi\Fpdi;

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit("Method not allowed");
}

if (!isset($_FILES['files'])) {
    http_response_code(400);
    exit("No files uploaded");
}

$tmpFiles = $_FILES['files']['tmp_name'];
$merged = new Fpdi();

foreach ($tmpFiles as $tmp) {
    $pageCount = $merged->setSourceFile($tmp);
    for ($i = 1; $i <= $pageCount; $i++) {
        $tpl = $merged->importPage($i);
        $size = $merged->getTemplateSize($tpl);
        $merged->AddPage($size['orientation'], [$size['width'], $size['height']]);
        $merged->useTemplate($tpl);
    }
}

// === ЛОГУЄМО В HISTORY ===
$conn = connectDatabase($hostname, $database, $username, $password);
if ($conn) {
    $user_id = $_SESSION['user_id'] ?? null;
    $action = 'merge_pdf';
    $ip = $_SERVER['REMOTE_ADDR'];
    $location = ''; // Шо, може GeoIP прикрутим? Колись.
    $used_api = 'pdf_merge';
    $created_at = date('Y-m-d H:i:s');

    try {
        $stmt = $conn->prepare("INSERT INTO history (user_id, action, ip, location, used_api, created_at)
            VALUES (:user_id, :action, :ip, :location, :used_api, :created_at)");
        $stmt->execute([
            ':user_id' => $user_id,
            ':action' => $action,
            ':ip' => $ip,
            ':location' => $location,
            ':used_api' => $used_api,
            ':created_at' => $created_at
        ]);
    } catch (PDOException $e) {
        error_log("History insert failed: " . $e->getMessage());
    }
}

header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="merged.pdf"');
$merged->Output('I', 'merged.pdf');
