<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// === Language loader ===
$lang = $_GET['lang'] ?? $_SESSION['lang'] ?? 'en';
$_SESSION['lang'] = $lang;
$lang_file = __DIR__ . "/lang/$lang.php";
$t = file_exists($lang_file) ? require $lang_file : require __DIR__ . "/lang/en.php";
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title>PDF Tools</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .tool-card {
            transition: 0.3s ease;
        }
        .tool-card:hover {
            transform: scale(1.05);
            background-color: #e9ecef;
        }
        .tool-icon {
            font-size: 2rem;
        }
    </style>
</head>
<body class="bg-light text-dark">
<div class="container py-4">

    <!-- Language & Logout -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <span class="me-3"><?= $t['welcome'] ?? 'Welcome' ?>, <?= $_SESSION['email'] ?></span>
            <a href="logout.php" class="btn btn-outline-danger btn-sm"><?= $t['logout'] ?? 'Logout' ?></a>
        </div>
        <div>
            <a href="?lang=en" class="btn btn-primary btn-sm">EN</a>
            <a href="?lang=sk" class="btn btn-primary btn-sm">SK</a>

        </div>
    </div>

    <h2 class="text-center mb-4">PDF Tools</h2>

    <div class="row row-cols-2 row-cols-md-4 g-4">
        <?php
        $tools = [
            ['Merge PDF', 'merge'],
            ['Split PDF', 'split'],
            ['Compress PDF', 'compress'],
            ['Rotate Pages', 'rotate'],
            ['Add Watermark', 'watermark'],
            ['Remove Pages', 'remove'],
            ['Extract Images', 'extract'],
            ['PDF to Images', 'pdf2img'],
        ];

        foreach ($tools as $tool) {
            echo '
            <div class="col">
                <div class="card text-center text-dark h-100 tool-card p-3">
                    <div class="tool-icon">📄</div>
                    <h6 class="mt-2">' . $tool[0] . '</h6>
                    <a href="#" class="btn btn-outline-primary btn-sm mt-2 disabled">Coming soon</a>
                </div>
            </div>';
        }
        ?>
    </div>
</div>
</body>
</html>
