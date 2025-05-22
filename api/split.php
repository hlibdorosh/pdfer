<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../vendor/autoload.php';

use setasign\Fpdi\Fpdi;


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

// Парсимо сторінки: "1-3,5,7" => [1,2,3,5,7]
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

// Читаємо PDF
$tmpFile = $_FILES['file']['tmp_name'];
$pdf = new Fpdi();
$pageCount = $pdf->setSourceFile($tmpFile);

$pagesToExtract = parsePages($_POST['pages'], $pageCount);

if (empty($pagesToExtract)) {
    http_response_code(400);
    echo json_encode(['error' => 'No valid pages selected']);
    exit;
}

// Створюємо новий PDF з вибраними сторінками
foreach ($pagesToExtract as $pageNum) {
    $tpl = $pdf->importPage($pageNum);
    $size = $pdf->getTemplateSize($tpl);
    $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
    $pdf->useTemplate($tpl);
}

header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="split.pdf"');
$pdf->Output('I', 'split.pdf');
exit;
