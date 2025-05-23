<?php
session_start();

// Перевірка, чи адмін
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: pdfile.php");
    exit;
}

// Псевдодані історії (можеш підключити з бази, якщо треба)
$history = [
    ['action' => 'Merged PDFs', 'timestamp' => '2025-05-23 14:12:34', 'user' => 'admin@site.com'],
    ['action' => 'Compressed PDF', 'timestamp' => '2025-05-23 13:01:10', 'user' => 'admin@site.com'],
    ['action' => 'Split PDF', 'timestamp' => '2025-05-22 18:45:00', 'user' => 'user@site.com'],
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light text-dark">

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📜 Admin History Log</h2>
        <a href="pdfile.php" class="btn btn-secondary btn-sm">← Back</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover shadow-sm border">
            <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Action</th>
                <th>User</th>
                <th>Timestamp</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($history as $index => $row): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($row['action']) ?></td>
                    <td><?= htmlspecialchars($row['user']) ?></td>
                    <td><?= $row['timestamp'] ?></td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
