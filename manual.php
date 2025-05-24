<?php

session_start();

$lang = $_GET['lang'] ?? $_SESSION['lang'] ?? 'sk';
$_SESSION['lang'] = $lang;

$lang_file = __DIR__ . "/lang/$lang.php";
$t = file_exists($lang_file) ? require $lang_file : require __DIR__ . "/lang/sk.php";
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $lang === 'sk' ? 'Používateľská príručka' : 'User Guide' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: "DejaVu Sans", sans-serif;
            line-height: 1.6;
            font-size: 16px;
            color: #222;
        }

        h2, h3 {
            color: #003366;
            margin-top: 1em;
        }

        .noprint {
            display: block;
        }

        @media print {
            .noprint {
                display: none !important;
            }
        }

        .pdf .noprint {
            display: none !important;
        }
    </style>
</head>
<body class="bg-light">


<div class="container py-4">


    <!-- Language switcher -->
    <div class="d-flex justify-content-end mb-3 noprint">
        <div class="d-flex flex-wrap gap-2" role="group" aria-label="Language selector">
            <a href="pdfile.php" class="btn btn-success btn-sm"><?= $t['back'] ?? 'Back' ?></a>

            <a href="?lang=en" class="btn btn-outline-primary btn-sm<?= $lang === 'en' ? ' active' : '' ?>">EN</a>
            <a href="?lang=sk" class="btn btn-outline-primary btn-sm<?= $lang === 'sk' ? ' active' : '' ?>">SK</a>
        </div>
    </div>



    <div class="card shadow-sm">
        <div class="card-body">
            <?= $t['user_manual_html'] ?>

            <?php if (!isset($isPdf) || !$isPdf): ?>
                <div class="text-end mt-4">
                    <a href="api/manual_pdf.php?lang=<?= $lang ?>" class="btn btn-primary noprint">📄 <?= $lang === 'sk' ? 'Stiahnuť PDF' : 'Download PDF' ?></a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
