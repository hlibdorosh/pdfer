<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('defaultFont', 'DejaVu Sans');
$dompdf = new Dompdf($options);

// Інформує manual.php, що генеруємо PDF
$isPdf = true;

ob_start();
include __DIR__ . '/../manual.php';
$html = ob_get_clean();

// Додаємо клас pdf до body, щоб .noprint не потрапила в PDF
$html = str_replace('<body>', '<body class="pdf">', $html);

$dompdf->loadHtml($html);
$dompdf->setPaper('A4');
$dompdf->render();
$dompdf->stream("user_manual.pdf", ["Attachment" => true]);
exit;
