<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "config.php";

if (isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // فحص بيانات المسؤول (لتسهيل المناقشة سنضع حساباً ثابتاً، ويمكنكِ تسجيله في جدول users لاحقاً)
    if ($username === 'admin' && $password === 'AuraTech2026') {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_user'] = $username;
        header("Location: admin-dashboard.php");
        exit();
    } else {
        $error = "Invalid Administrative Node Credentials.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal Gateway | AuraTech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #0A2540 0%, #1A365D 100%); height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { border-radius: 15px; max-width: 400px; width: 100%; box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
    </style>
</head>
<body>

    <div class="card login-card p-4 bg-white">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-dark">✨ AuraTech Central</h3>
            <span class="text-muted small">Authorized Mainframe Authentication</span>
        </div>
        
        <?php if(isset($error)): ?>
            <div class="alert alert-danger small py-2"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="admin-login.php" method="POST">
            <div class="mb-3">
                <label class="form-label small fw-bold">Admin Identifier</label>
                <input type="text" name="username" class="form-control" required placeholder="username">
            </div>
            <div class="mb-4">
                <label class="form-label small fw-bold">Security Passphrase</label>
                <input type="password" name="password" class="form-control" required placeholder="••••••••">
            </div>
            <button type="submit" class="btn btn-dark w-100 rounded-pill py-2 fw-bold">Execute Authentication</button>
        </form>
    </div>

</body>
</html>