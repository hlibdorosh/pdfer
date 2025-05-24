<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('defaultFont', 'DejaVu Sans');
$dompdf = new Dompdf($options);


// Встановлюємо мову з параметра URL або за замовчуванням 'sk'
$lang = $_GET['lang'] ?? $_SESSION['lang'] ?? 'sk';
$_SESSION['lang'] = $lang;

// Інформує manual.php, що генеруємо PDF
$isPdf = true;

ob_start();
include __DIR__ . '/../manual.php';
$html = ob_get_clean();

// Додаємо клас pdf до <body>
$html = preg_replace('/<body([^>]*)class="([^"]*)"/', '<body$1class="$2 pdf"', $html);
$html = preg_replace('/<body([^>]*)>/', '<body$1 class="pdf">', $html);


$dompdf->loadHtml($html);
$dompdf->setPaper('A4');
$dompdf->render();
$dompdf->stream("user_manual.pdf", ["Attachment" => true]);
exit;
