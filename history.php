<?php
session_start();
require_once __DIR__ . '/config.php';

// Вибір мови
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}
$lang = $_SESSION['lang'] ?? 'sk';

$langFile = __DIR__ . "/lang/{$lang}.php";
if (file_exists($langFile)) {
    $t = require $langFile;
} else {
    $t = require __DIR__ . "/lang/sk.php"; // fallback
}

// Перевірка доступу
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: pdfile.php");
    exit;
}

// Видалення запису
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $deleteId = (int) $_POST['delete_id'];
    $conn = connectDatabase($hostname, $database, $username, $password);
    if ($conn) {
        try {
            $stmt = $conn->prepare("DELETE FROM history WHERE id = :id");
            $stmt->execute([':id' => $deleteId]);
        } catch (PDOException $e) {
            error_log("Delete failed: " . $e->getMessage());
        }
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Отримання історії
$conn = connectDatabase($hostname, $database, $username, $password);
$history = [];

if ($conn) {
    try {
        $stmt = $conn->query("
            SELECT h.id, h.action, h.created_at as timestamp, h.ip, u.email as user
            FROM history h
            LEFT JOIN users u ON h.user_id = u.id
            ORDER BY h.created_at DESC
        ");
        $history = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Fetch history failed: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $t['title'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .topbar {
            background-color: #007bff;
            color: white;
        }
        .btn-yellow {
            background-color: #ffc107;
            color: black;
        }
        .btn-yellow:hover {
            background-color: #e0a800;
            color: white;
        }
        thead.table-blue th {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold"> <?= $t['title'] ?></h2>
        <div>
            <a href="?lang=sk" class="btn btn-primary btn-sm">SK</a>
            <a href="?lang=en" class="btn btn-primary btn-sm">EN</a>
        </div>
    </div>

    <div class="mb-3">
        <a href="pdfile.php" class="btn btn-success btn-sm"><?= $t['back'] ?></a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover shadow-sm border">
            <thead class="table-blue">
            <tr>
                <th>#</th>
                <th><?= $t['action'] ?></th>
                <th><?= $t['user'] ?></th>
                <th><?= $t['ip'] ?></th>
                <th><?= $t['timestamp'] ?></th>
                <th>🗑️</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($history)): ?>
                <?php foreach ($history as $index => $row): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($row['action']) ?></td>
                        <td><?= htmlspecialchars($row['user'] ?? 'Unknown') ?></td>
                        <td><?= htmlspecialchars($row['ip']) ?></td>
                        <td><?= $row['timestamp'] ?></td>
                        <td>
                            <form method="post" onsubmit="return confirm('<?= $t['confirm_delete'] ?>')">
                                <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm"><?= $t['delete'] ?></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center"><?= $t['no_history'] ?></td>
                </tr>
            <?php endif ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
