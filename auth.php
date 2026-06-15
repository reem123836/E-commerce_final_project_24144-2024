<?php
// auth.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
require_once 'config.php';

$error = "";
$success = "";

// 1. Handle Sign Up Request (Force Standard User Role)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $user_name = trim($_POST['username'] ?? '');
    $pass = $_POST['password'] ?? '';

    if (empty($user_name) || empty($pass)) {
        $error = "All authorization fields are required.";
    } else {
        try {
            // Check if username matrix already exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
            $stmt->bind_param("s", $user_name);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $error = "Identity node already exists. Choose another identity.";
            } else {
                // Securely hash the password string using Bcrypt
                $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
                
                // CRITICAL SECURITY: Hardcode 'user' to block privilege escalation from registration form
                $insert_stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'user')");
                $insert_stmt->bind_param("ss", $user_name, $hashed_password);
                
                if ($insert_stmt->execute()) {
                    $success = "Identity initialized successfully! Proceed to system authorization.";
                } else {
                    $error = "Pipeline error during node injection.";
                }
                $insert_stmt->close();
            }
            $stmt->close();
        } catch (mysqli_sql_exception $e) {
            $error = "Execution failure: " . $e->getMessage();
        }
    }
}

// 2. Handle Sign In Request (With Dynamic Routing Logic)
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
                
                // Verify provided password against secured database hash
                if (password_verify($pass, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'] ?? 'user';

                    // Dynamic Routing based on identity role matrix
                    if ($_SESSION['role'] === 'admin') {
                        header("Location: admin_dashboard.php");
                    } else {
                        header("Location: index.php");
                    }
                    exit();
                } else {
                    $error = "Invalid authorization credentials matrix.";
                }
            } else {
                $error = "Invalid authorization credentials matrix.";
            }
            $stmt->close();
        } catch (mysqli_sql_exception $e) {
            $error = "Authentication failure stream: " . $e->getMessage();
        }
    }
}

// 3. Handle Logout Request Sequence
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
    header("Location: auth.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AuraTech Agency - Secure Authentication</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --cyber-bg-top: #0b0f19;
            --cyber-bg-bottom: #1e1b4b;
            --neon-purple: #7c3aed;
            --neon-cyan: #06b6d4;
            --text-light: #f8fafc;
            --card-glass: rgba(255, 255, 255, 0.02);
            --border-glass: rgba(255, 255, 255, 0.05);
        }

        body {
            background: linear-gradient(180deg, var(--cyber-bg-top) 0%, var(--cyber-bg-bottom) 100%);
            color: var(--text-light);
            font-family: 'Segoe UI', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-container {
            width: 100%;
            max-width: 450px;
            background: var(--card-glass);
            backdrop-filter: blur(15px);
            border: 1px solid var(--border-glass);
            border-top: 4px solid var(--neon-purple);
            border-radius: 14px;
            padding: 40px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
        }

        .brand-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .brand-header h2 {
            font-weight: 800;
            background: linear-gradient(90deg, #ffffff, var(--neon-cyan));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: 1px;
        }

        .form-control-auratech {
            background-color: rgba(11, 15, 25, 0.6) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: #ffffff !important;
            border-radius: 8px !important;
            padding: 12px !important;
        }

        .form-control-auratech:focus {
            border-color: var(--neon-purple) !important;
            box-shadow: 0 0 10px rgba(124, 58, 237, 0.4) !important;
        }

        .btn-auratech {
            background: linear-gradient(90deg, var(--neon-purple), #6d28d9);
            color: #ffffff !important;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            padding: 12px;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 15px;
        }

        .btn-auratech:hover {
            transform: translateY(-1px);
            box-shadow: 0 0 15px rgba(124, 58, 237, 0.6);
        }

        .toggle-link {
            color: var(--neon-cyan);
            text-decoration: none;
            cursor: pointer;
            font-weight: 500;
        }

        .toggle-link:hover {
            text-decoration: underline;
        }

        .alert-custom-danger {
            background-color: rgba(26, 15, 10, 0.5);
            border: 1px solid #4a2306;
            color: #ff9e59;
            border-radius: 8px;
        }

        .alert-custom-success {
            background-color: rgba(10, 26, 15, 0.5);
            border: 1px solid #064a1c;
            color: #59ff8b;
            border-radius: 8px;
        }
    </style>
</head>
<body>

<div class="auth-container">
    <div class="brand-header">
        <h2>AURATECH SUITE</h2>
        <p class="text-white-50 small" id="auth-subtitle">Secure Gateway Node Access</p>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-custom-danger text-center mb-3 small"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-custom-success text-center mb-3 small"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    <form id="login-form" action="auth.php" method="POST">
        <div class="mb-3">
            <label class="form-label small text-white-50">Identity Username</label>
            <input type="text" name="username" class="form-control form-control-auratech" required autocomplete="off">
        </div>
        <div class="mb-3">
            <label class="form-label small text-white-50">Secret Matrix Password</label>
            <input type="password" name="password" class="form-control form-control-auratech" required>
        </div>
        <button type="submit" name="login" class="btn btn-auratech">Sign In Pipeline</button>
        <p class="text-center mt-4 small text-white-50">
            New node infrastructure? <span class="toggle-link" onclick="toggleAuthView(true)">Create Account</span>
        </p>
    </form>

    <form id="register-form" action="auth.php" method="POST" style="display: none;">
        <div class="mb-3">
            <label class="form-label small text-white-50">Initialize Username</label>
            <input type="text" name="username" class="form-control form-control-auratech" required autocomplete="off">
        </div>
        <div class="mb-3">
            <label class="form-label small text-white-50">Initialize Secure Password</label>
            <input type="password" name="password" class="form-control form-control-auratech" required>
        </div>
        <button type="submit" name="register" class="btn btn-auratech" style="background: linear-gradient(90deg, var(--neon-cyan), #0891b2); color: #0b0f19 !important;">Register Pipeline Node</button>
        <p class="text-center mt-4 small text-white-50">
            Identity already initialized? <span class="toggle-link" onclick="toggleAuthView(false)">Sign In</span>
        </p>
    </form>
</div>

<script>
    function toggleAuthView(isRegister) {
        const loginForm = document.getElementById('login-form');
        const registerForm = document.getElementById('register-form');
        const subtitle = document.getElementById('auth-subtitle');

        if (isRegister) {
            loginForm.style.display = 'none';
            registerForm.style.display = 'block';
            subtitle.innerText = 'Provision New Cloud Account';
        } else {
            loginForm.style.display = 'block';
            registerForm.style.display = 'none';
            subtitle.innerText = 'Secure Gateway Node Access';
        }
    }
</script>
</body>
</html>