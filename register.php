<?php
session_start();
require_once 'config.php';
require_once '../secret.php';
$lang = $_GET['lang'] ?? $_SESSION['lang'] ?? 'en';
$_SESSION['lang'] = $lang;
$lang_file = __DIR__ . "/lang/$lang.php";
$t = file_exists($lang_file) ? require $lang_file : require __DIR__ . "/lang/en.php";

$db = connectDatabase($hostname, $database, $username, $password);



$admin_checked = false;
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'user';

    // Перевірка адмінського пароля
    if (!empty($_POST['admin_password'])) {
        $admin_password = $_POST['admin_password'];
        if ($admin_password === $admin_secret) {
            $role = 'admin';
            $admin_checked = true;
        }
    }

    try {
        $stmt = $db->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, ?)");
        $stmt->execute([$email, $password, $role]);
        $success = true;
    } catch (PDOException $e) {
        $error = $t['error_register'];
    }
}
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $t['register_title'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function toggleAdmin() {
            document.getElementById("admin-section").style.display = 'block';
        }
    </script>
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="text-end mb-3">
        <a href="?lang=en" class="btn btn-outline-secondary btn-sm">EN</a>
        <a href="?lang=sk" class="btn btn-outline-secondary btn-sm">SK</a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow p-4">
                <h3 class="text-center mb-4"><?= $t['register_title'] ?></h3>

                <?php if (isset($success)): ?>
                    <div class="alert alert-success text-center">
                        ✅ <?= $t['success_register'] ?> <a href="login.php?lang=<?= $lang ?>"><?= $t['login_button'] ?></a>
                    </div>
                <?php elseif (isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label><?= $t['email'] ?></label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label><?= $t['password'] ?></label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <button type="button" onclick="toggleAdmin()" class="btn btn-warning w-100 mb-3">Are you admin?</button>

                    <div id="admin-section" style="display:none;" class="mb-3">
                        <label>Admin password</label>
                        <input type="password" name="admin_password" class="form-control">
                    </div>

                    <button class="btn btn-primary w-100"><?= $t['register_button'] ?></button>
                </form>

                <div class="mt-3 text-center">
                    <a href="login.php?lang=<?= $lang ?>"><?= $t['already_have_account'] ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
