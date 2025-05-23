<?php
session_start();

require_once __DIR__ . '/../config.php';

echo "<pre>";
print_r($_FILES);
echo "</pre>";

exec("which gs", $out);
echo "GS path: " . implode(', ', $out) . "<br>";

echo "Temp dir writable: " . (is_writable(__DIR__ . '/../temp/') ? 'YES' : 'NO');


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Перевірка методу
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Перевірка файлу
if (!isset($_FILES['file']) || $_FILES['file']['error'] !== 0) {
    http_response_code(400);
    echo json_encode(['error' => 'No file uploaded']);
    exit;
}

// Створюємо тимчасові імена
$input = $_FILES['file']['tmp_name'];
$output = __DIR__ . '/../temp/compressed_' . uniqid() . '.pdf';

// Перевірка чи Ghostscript доступний
exec("which gs", $out);
if (empty($out)) {
    http_response_code(500);
    echo json_encode(['error' => 'Ghostscript is not installed']);
    exit;
}

// Команда для стискання
$cmd = "gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=/screen " .
    "-dNOPAUSE -dQUIET -dBATCH -sOutputFile=" . escapeshellarg($output) . " " . escapeshellarg($input);

exec($cmd, $null, $code);

if ($code !== 0 || !file_exists($output)) {
    http_response_code(500);
    echo json_encode(['error' => 'Compression failed']);
    exit;
}
// === ЛОГУЄМО У HISTORY ===
$conn = connectDatabase($hostname, $database, $username, $password);
if ($conn) {
    $user_id = $_SESSION['user_id'];
    $action = 'compress_pdf';
    $ip = $_SERVER['REMOTE_ADDR'];
    $location = ''; // Можна прикрутити GeoIP
    $used_api = 'pdf_compression';
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

// Виводимо PDF
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="compressed.pdf"');
readfile($output);
unlink($output); // Видаляємо після відправки
exit;
