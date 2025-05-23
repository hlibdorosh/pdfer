<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['file'])) {
    http_response_code(400);
    echo json_encode(['error' => 'No file uploaded']);
    exit;
}

$tmpFile = $_FILES['file']['tmp_name'];
$cmd = 'pdfinfo ' . escapeshellarg($tmpFile);
$output = shell_exec($cmd);

if (!$output) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to read PDF metadata']);
    exit;
}

header('Content-Type: application/json');
echo json_encode(['info' => $output]);
exit;
