<?php
require_once 'config.php';

$db = connectDatabase($hostname, $database, $username, $password);

if (!$db) {
    echo "❌ Підключення до бази не вдалося!";
    exit;
}

// Дані для вставки
$email = 'newuser@example.com';
$password = password_hash('securepassword', PASSWORD_DEFAULT); // Хешуєм, бо безпека!
$role = 'user';

try {
    $stmt = $db->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, ?)");
    $stmt->execute([$email, $password, $role]);

    echo "✅ Юзер успішно доданий!";
} catch (PDOException $e) {
    echo "❌ Помилка вставки: " . $e->getMessage();
}
