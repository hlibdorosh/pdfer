<?php
session_start();
require_once 'config.php';

// === Language loader ===
$lang = $_GET['lang'] ?? $_SESSION['lang'] ?? 'en';
$_SESSION['lang'] = $lang;
$lang_file = __DIR__ . "/lang/$lang.php";
$t = file_exists($lang_file) ? require $lang_file : require __DIR__ . "/lang/en.php";

// === DB connection ===
$db = connectDatabase($hostname, $database, $username, $password);

// === Login logic ===
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['email'] = $user['email'];

        // === LOG TO HISTORY ===
        $user_id = $user['id'];
        $action = 'login';
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $location = ''; // можна GeoIP прикрутити
        $used_api = 'login_form';
        $created_at = date('Y-m-d H:i:s');

        try {
            $stmt = $db->prepare("INSERT INTO history (user_id, action, ip, location, used_api, created_at) 
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
            error_log("Login history insert failed: " . $e->getMessage());
        }

        header("Location: pdfile.php");
        exit;
    } else {
        $error = $t['invalid_login'];
    }
}
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $t['login_title'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">

    <!-- Language Switch -->
    <div class="text-end mb-3">
        <a href="?lang=en" class="btn btn-outline-secondary btn-sm">EN</a>
        <a href="?lang=sk" class="btn btn-outline-secondary btn-sm">SK</a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow p-4">
                <h3 class="mb-4 text-center"><?= $t['login_title'] ?></h3>

                <?php if (isset($error)): ?>
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
                    <button class="btn btn-primary w-100"><?= $t['login_button'] ?></button>
                </form>

                <div class="mt-3 text-center">
                    <a href="register.php?lang=<?= $lang ?>"><?= $t['no_account'] ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
