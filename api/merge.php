<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../vendor/autoload.php';

use setasign\Fpdi\Fpdi;

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

header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="merged.pdf"');
$merged->Output('I', 'merged.pdf');
