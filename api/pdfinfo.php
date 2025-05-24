<?php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config.php'; // 👈 Путь до конфіга

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

// === ЛОГУЄМО В HISTORY ===
$conn = connectDatabase($hostname, $database, $username, $password);
if ($conn) {
    $user_id = $_SESSION['user_id'] ?? null;
    $action = 'read_pdf_info';
    $ip = $_SERVER['REMOTE_ADDR'];
    $location = ''; // можна буде прикрутити GeoIP
    $used_api = 'pdf_info';
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

header('Content-Type: application/json');
echo json_encode(['info' => $output]);
exit;
