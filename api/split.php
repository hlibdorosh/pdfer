<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../config.php'; // Додаємо конфіг

use setasign\Fpdi\Fpdi;

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

if (!isset($_FILES['file']) || $_FILES['file']['error'] !== 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid file upload']);
    exit;
}

if (empty($_POST['pages'])) {
    http_response_code(400);
    echo json_encode(['error' => 'No page selection']);
    exit;
}

function parsePages($input, $maxPage) {
    $result = [];
    $parts = explode(',', $input);
    foreach ($parts as $part) {
        if (strpos($part, '-') !== false) {
            [$start, $end] = explode('-', $part);
            $start = (int)$start;
            $end = (int)$end;
            if ($start > 0 && $end <= $maxPage && $start <= $end) {
                $result = array_merge($result, range($start, $end));
            }
        } else {
            $num = (int)$part;
            if ($num > 0 && $num <= $maxPage) {
                $result[] = $num;
            }
        }
    }
    return array_unique($result);
}

$tmpFile = $_FILES['file']['tmp_name'];
$pdf = new Fpdi();
$pageCount = $pdf->setSourceFile($tmpFile);

$pagesToExtract = parsePages($_POST['pages'], $pageCount);

if (empty($pagesToExtract)) {
    http_response_code(400);
    echo json_encode(['error' => 'No valid pages selected']);
    exit;
}

foreach ($pagesToExtract as $pageNum) {
    $tpl = $pdf->importPage($pageNum);
    $size = $pdf->getTemplateSize($tpl);
    $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
    $pdf->useTemplate($tpl);
}

// === ЛОГУЄМО В ІСТОРІЮ ===
$conn = connectDatabase($hostname, $database, $username, $password);
if ($conn) {
    $user_id = $_SESSION['user_id'] ?? null;
    $action = 'split_pdf';
    $ip = $_SERVER['REMOTE_ADDR'];
    $location = ''; // Геолокація, якщо буде бажання прикрутить
    $used_api = 'pdf_split';
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
header('Content-Disposition: attachment; filename="split.pdf"');
$pdf->Output('I', 'split.pdf');
exit;
